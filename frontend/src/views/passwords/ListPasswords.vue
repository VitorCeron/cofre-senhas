<template>
  <q-page padding>
    <span class="text-h4">Lista de senhas</span>
    <hr />

    <div class="row">
      <div class="col-3">
        <q-input
          v-model="state.filter.name"
          label="Nome"
          type="text"
          placeholder="Ex: Facebook"
          dense
        />
      </div>
      <div class="col flex justify-end">
        <q-btn
          color="primary"
          label="Adicionar senha"
          size="md"
          icon="add"
          @click="goToCreate"
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
        />
      </div>
    </div>
  </q-page>
</template>

<script>
import { reactive, onMounted, watch } from "vue";
import services from "@/services";
import debounce from "lodash/debounce";
import { useRouter } from "vue-router";

const columns = [
  { name: "name", align: "center", label: "Nome", field: "name" },
  { name: "link", align: "center", label: "Link", field: "link" },
  {
    name: "updated_at",
    align: "center",
    label: "Última atualização",
    field: "updated_at",
  },
];

export default {
  setup() {
    const router = useRouter();

    const state = reactive({
      name: "",
      filter: {
        name: "",
      },
      rows: [],
      pagination: {},
      loading: false,
    });

    function goToCreate() {
      router.push({ name: "CreatePassword" });
    }

    async function getData(props) {
      state.loading = true;
      let { data, meta } = await services.user_passwords.get({
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

    return {
      state,
      columns,
      goToCreate,
      getData,
    };
  },
};
</script>

<style>
</style>