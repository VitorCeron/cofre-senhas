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
        >
          <template v-slot:body-cell-actions="props">
            <q-td :props="props">
              <q-btn 
                @click="goToUpdate(props.row.id)"
                flat 
                color="white" 
                text-color="black" 
                icon="edit" 
                size="sm"
                class="q-mr-md"
                />
  
              <q-btn 
                @click="openModalRemove(props.row)"
                flat 
                color="white" 
                text-color="black" 
                icon="delete" 
                size="sm"
                />
            </q-td>
          </template>
        </q-table>
      </div>
    </div>

    <q-dialog v-model="state.modal">
      <q-card style="width: 600px; max-width: 80vw;">
        <q-card-section>
          <div class="text-h6">Você confirma a remoção desse registro?</div>
        </q-card-section>

        <q-card-section class="q-pt-none">
          Você tem certeza que quer excluir o registro: <strong>{{state.selectedRemove.name}}</strong>?<br>
        </q-card-section>

        <q-card-section class="q-pt-none">
          Essa exclusão não poderá ser desfeita
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Cancelar" color="primary" v-close-popup />
          <q-btn 
            @click="confirmRemoveItem"
            flat 
            label="Excluir" 
            color="negative" 
            v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import { reactive, onMounted, ref } from "vue";
import services from "@/services";
import { useRouter } from "vue-router";
import { toast } from "@/utils/notification";

const columns = [
  { name: "name", align: "center", label: "Nome", field: "name" },
  { name: "score_password", align: "center", label: "Força da senha", field: "score_password" },
  {
    name: "updated_at",
    align: "center",
    label: "Última atualização",
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
    const router = useRouter();

    const state = reactive({
      name: "",
      filter: {
        name: "",
      },
      rows: [],
      pagination: {},
      loading: false,
      modal: false,
      selectedRemove: {}
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

    function goToUpdate (passwordId) {
      router.push({ name: 'UpdatePassword', params: { id: passwordId } });
    }

    function openModalRemove (password) {
      state.selectedRemove = password;
      state.modal = true;
    }
    
    async function confirmRemoveItem () {
      let response = await services.user_passwords.delete({
        id: state.selectedRemove.id
      });

      if(response.status != 204) {
        toast({
            type: "negative",
            message: "Erro ao excluir registro",
        });
        state.selectedRemove = {};
        return;
      }

      getData({
        pagination: state.pagination,
        filter: state.filter,
      });

      toast({
          type: "positive",
          message: "Registro excluído com sucesso",
      });
      state.selectedRemove = {};
      
    }

    return {
      alert,
      state,
      columns,
      goToCreate,
      getData,
      goToUpdate,
      openModalRemove,
      confirmRemoveItem,
    };
  },
};
</script>

<style>
</style>