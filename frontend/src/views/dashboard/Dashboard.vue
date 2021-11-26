<template>
  <q-page padding>

    <div class="row q-gutter-md" v-if="state.empty_data">
        <div class="col flex justify-center items-center">
            <img src="../../assets/images/empty-state.svg" alt="Tela vazia" class="image-empty-state" />
            <div class="column q-ml-sm">
                <p class="text-h4">Você ainda não tem senhas cadastradas</p>
                <q-btn 
                    @click="goToForm"
                    type="submit" 
                    color="primary" 
                    class="q-mb-sm">
                    <q-icon name="add" size="lg" /> Criar minha primeira senha
                </q-btn>
            </div>
        </div>
    </div>
    
    <template v-else>
        <div class="row q-gutter-md">
            <div class="col">
                <div class="q-item q-item-type row no-wrap q-pa-none bg-indigo-7">
                    <div class="q-item__section column  q-pa-lg q-mr-none text-white q-item__section--side justify-center bg-indigo-8"><i aria-hidden="true" role="presentation"
                            class="material-icons q-icon notranslate text-white" style="font-size: 24px;">vpn_key</i></div>
                    <div class="q-item__section column  q-pa-md q-ml-none  text-white q-item__section--main justify-center">
                        <div class="q-item__label text-white text-h6 text-weight-bolder">{{ state.count_user_passwords }}</div>
                        <div class="q-item__label">Quantidade de senhas</div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="q-item q-item-type row no-wrap q-pa-none bg-orange-7">
                    <div class="q-item__section column  q-pa-lg q-mr-none text-white q-item__section--side justify-center bg-orange-8"><i aria-hidden="true" role="presentation"
                            class="material-icons q-icon notranslate text-white" style="font-size: 24px;">repeat</i></div>
                    <div class="q-item__section column  q-pa-md q-ml-none  text-white q-item__section--main justify-center">
                        <div class="q-item__label text-white text-h6 text-weight-bolder">{{ state.count_duplicate_passwords }}</div>
                        <div class="q-item__label">Quantidade de senhas repetidas</div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="q-item q-item-type row no-wrap q-pa-none bg-red-7">
                    <div class="q-item__section column  q-pa-lg q-mr-none text-white q-item__section--side justify-center bg-red-8"><i aria-hidden="true" role="presentation"
                            class="material-icons q-icon notranslate text-white" style="font-size: 24px;">lock_clock</i></div>
                    <div class="q-item__section column  q-pa-md q-ml-none  text-white q-item__section--main justify-center">
                        <div class="q-item__label text-white text-h6 text-weight-bolder">{{ state.count_passwords_expire_in_30_days }}</div>
                        <div class="q-item__label">Senhas perto do vencimento</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row q-gutter-md q-pt-md">
            <div class="col-5">
                <q-card class="my-card">
                    <div id="chart">
                    <apexchart type="radialBar" height="350" :options="state.chart.chartOptions" :series="state.chart.series"></apexchart>
                </div>
                </q-card>
                
            </div>

            <div class="col">
                <q-table
                    style="height:350.7px"
                    :rows="state.rows"
                    :columns="state.columns"
                    :loading="state.loading"
                    row-key="name"
                    title="Senhas perto do vencimento"
                    :hide-bottom="state.rows.length > 0"
                    >
                        <template v-slot:no-data>
                            <div class="full-width row flex-center q-gutter-sm">
                            <q-icon size="2em" name="sentiment_very_satisfied" />
                            <span class="text-weight-bold">
                                Não há senhas perto do vencimento
                            </span>
                            </div>
                        </template>
                    </q-table>
            </div>
        </div>
    </template>

  </q-page>
</template>

<script>
import { reactive, onMounted } from "vue";
import services from "@/services";
import { useRouter } from "vue-router";
import VueApexCharts from "vue3-apexcharts";

const columns = [
  { name: "name", align: "center", label: "Nome", field: "name" },
  {
    name: "expire",
    align: "center",
    label: "Data de expiração",
    field: "expire",
  },
];

export default {
    components: {
        apexchart: VueApexCharts,
    },
    setup() {
        const router = useRouter();

        const state = reactive({
            empty_data: false,
            count_user_passwords: 0,
            count_duplicate_passwords: 0,
            count_passwords_expire_in_30_days: 0,
            rows: [],
            columns: columns,
            loading: false,
            chart: {
                series: [],
                chartOptions: {
                    chart: {
                        height: 350,
                        type: 'radialBar',
                    },
                    plotOptions: {
                        radialBar: {
                            hollow: {
                                size: '70%',
                            },
                             dataLabels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '20px',
                                    fontWeight: 600,
                                    color: '#000000',
                                },
                                value: {
                                    show: true,
                                    fontSize: '20px',
                                    fontWeight: 400,
                                    offsetY: 16,
                                    formatter: function (val) {
                                        return val
                                    }
                                },
                            },
                            
                        },
                    },
                    labels: [],
                    title: {
                        text: 'Média de força de senha',
                        align: 'left',
                        margin: 20,
                        style: {
                            fontSize: '20px',
                            letterSpacing: '0.005em',
                            fontWeight: '400',
                            color: 'black'
                        }
                    },
                    fill: {
                        type: 'solid',
                        colors:[]
                    }
                },
            }
        });

        onMounted(async () => {
            let response = await services.dashboard.get();

            if(response.data.empty_data) {
                state.empty_data = true;
                return;
            }

            state.count_user_passwords = response.data.count_user_passwords;
            state.count_duplicate_passwords = response.data.count_duplicate_passwords;
            state.count_passwords_expire_in_30_days = response.data.count_passwords_expire_in_30_days;

            state.chart.series = [response.data.average_score_passwords];
            state.chart.chartOptions.labels.push(response.data.label_score_passwords);
            state.chart.chartOptions.fill.colors.push(response.data.color_score_passwords);
            state.chart.chartOptions.plotOptions.radialBar.dataLabels.name.color = response.data.color_score_passwords;

            state.rows = response.data.list_passwords_expire_in_30_days;
        });

        function goToForm () {
            router.push({ name: 'CreatePassword' });
        }

        return {
            state,
            goToForm,
        };
    },
};
</script>

<style>
.image-empty-state {
    width: 300px;
    height: 300px;
}
</style>