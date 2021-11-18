<template>
  <q-page padding>
    <span class="text-h4">Registro de atividades do sistema</span>
    <hr />

    <div class="row">
      <div class="col-3">
        <q-input
          v-model="state.filter.description"
          label="Descrição"
          type="text"
          dense
        />
      </div>
    </div>

    <div class="row q-mt-md">
      <div class="col">
        <q-table
          :rows="state.rows"
          :columns="columns"
          :loading="state.loading"
          v-model:pagination="state.pagination"
          :filter="state.filter"
          row-key="name"
          @request="getData"
        >
          <template v-slot:body-cell-actions="props">
            <q-td :props="props">
              <q-btn 
                @click="openModal(props.row)"
                flat 
                color="white" 
                text-color="black" 
                icon="add" 
                size="sm"
                class="q-mr-md"
                />
            </q-td>
          </template>
        </q-table>
      </div>
    </div>

    <q-dialog v-model="state.modal">
      <q-card style="width: 600px; max-width: 80vw;">
        <q-card-section>
          <div class="text-h6">Informações desse registro de alteração</div>
        </q-card-section>

        <q-card-section class="q-pt-none">
          Propriedades do recurso:
        </q-card-section>

        <q-card-section class="q-pt-none">
          <pre>
              {{ state.logSelected }}
          </pre>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Fechar" color="primary" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import { reactive, onMounted, computed } from "vue";
import services from "@/services";

const columns = [
  { name: "id", align: "center", label: "#", field: "id" },
  { name: "description", align: "center", label: "Descrição", field: "description" },
  {
    name: "updated_at",
    align: "center",
    label: "Hora",
    field: "updated_at",
  },
  {
    name: "actions",
    align: "center",
    label: "Ações",
    field: "actions",
  },
];

export default {
  setup() {
    const state = reactive({
      filter: {
        description: "",
      },
      rows: [],
      pagination: {},
      loading: false,
      modal: false,
      logSelected: {}
    });

    async function getData(props) {
      state.loading = true;
      let { data, meta } = await services.logs.get({
        page: props.pagination.page,
        limit: props.pagination.rowsPerPage,
        filter: props.filter,
      });

      state.rows = data;
      state.pagination.page = meta.current_page;
      state.pagination.rowsPerPage = meta.per_page;
      state.pagination.rowsNumber = meta.total[0];
      state.loading = false;
    }

    onMounted(async () => {
      await getData({
        pagination: state.pagination,
        filter: state.filter,
      });
    });

    function openModal (log) {
      state.logSelected = log;
      state.modal = true;
    }

    return {
      state,
      columns,
      getData,
      openModal,
    };
  },
};
</script>

<style>
</style>