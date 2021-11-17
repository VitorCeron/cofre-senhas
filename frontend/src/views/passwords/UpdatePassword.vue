<template>
  <q-page padding>
    <span class="text-h4">Atualizar senha</span>
    <hr />

    <div class="row">
      <div class="col-md-6 offset-md-3">
        <q-form @submit="updatePassword" class="q-gutter-md">
          <q-input
            v-model="state.name"
            label="Nome"
            type="text"
            placeholder="Ex: Facebook"
            lazy-rules
            :rules="[validation.required, validation.lengthMoreThan2]"
          />

          <q-input 
            v-model="state.password" 
            label="Senha" 
            :type="state.type_field_password"
            placeholder="********"
            ref="password_field"
            lazy-rules
            :rules="[validation.required]"
            >
                <template v-slot:append>
                    <q-icon :name="state.icon_field_password" @click="toggleVisibilityPassword" class="cursor-pointer" />
                </template>
            </q-input>

          <q-input
            v-model="state.link"
            label="Link"
            type="text"
            placeholder="Ex: facebook.com.br"
          />

          <q-input
            v-model="state.observation"
            label="Observação"
            type="textarea"
            placeholder="Ex: Esta é minha senha atual do facebook"
          />

          <div class="flex justify-end">
            <q-btn 
              @click="goToList"
              color="white" 
              text-color="black" 
              label="Voltar" 
              icon="arrow_back" 
              class="q-mt-md q-mr-md" />

            <q-btn 
              type="submit"
              color="primary" 
              label="Salvar" 
              icon="save" 
              class="q-mt-md" />
          </div>
        </q-form>
      </div>
    </div>    
  </q-page>
</template>

<script>
import { reactive, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import { toast } from "@/utils/notification";
import { required, lengthMoreThan2 } from '@/utils/validations';
import services from '@/services';

export default {
  setup() {
    const router = useRouter();
    const route = useRoute();

    const state = reactive({
      id: "",
      name: "",
      password: "",
      link: "",
      observation: "",
      user_id: JSON.parse(window.localStorage.getItem('cofre_senhas_user')).id,
      type_field_password: 'password',
      icon_field_password: 'visibility'
    });

    const validation = {
        required,
        lengthMoreThan2,
    }

    function goToList() {
      router.push({ name: "ListPasswords" });
    }

    async function updatePassword () {
        let response = await services.user_passwords.update({
          id: state.id,
          name: state.name,
          password: state.password,
          link: state.link,
          observation: state.observation,
          user_id: state.user_id
        });

        if(response.error) {
            toast({
                type: "negative",
                message: "Erro ao atualizar senha",
            });
            return;
        }

        toast({
            type: "positive",
            message: "Senha atualizada com sucesso"
        });
        goToList();
    }

    function toggleVisibilityPassword () {
        state.type_field_password = (state.type_field_password == "text") ? "password" : "text";
        state.icon_field_password = (state.icon_field_password == "visibility") ? "visibility_off" : "visibility";
    }

    onMounted(async () => {
      const passwordId = route.params.id;
      let { data } = await services.user_passwords.getById({ passwordId });
      state.id = data.id;
      state.name = data.name;
      state.password = data.password;
      state.link = data.link;
      state.observation = data.observation;
    });

    return {
      state,
      validation,
      goToList,
      updatePassword,
      toggleVisibilityPassword,
    };
  },
};
</script>

<style>
</style>