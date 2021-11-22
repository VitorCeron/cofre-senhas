<template>
  <q-page padding>
    <span class="text-h4">Adicionar senha</span>
    <hr />

    <div class="row">
      <div class="col-md-6 offset-md-3">
        <q-form @submit="createPassword" class="q-gutter-md">
          <q-input
            v-model="state.name"
            label="Nome do serviço"
            type="text"
            placeholder="Ex: Facebook"
            lazy-rules
            :rules="[validation.required, validation.lengthMoreThan2]"
          />

          <q-input
            v-model="state.login"
            label="Login"
            type="text"
            placeholder="Ex: João Silva ou joaosilva@mail.com"
            lazy-rules
            :rules="[validation.required]"
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
            v-model="state.expire" 
            placeholder="Data de expiração"
            hint="Caso a sua senha possua uma data de troca ou data de expiração, preencha este campo"
            >
            <template v-slot:append>
              <q-icon name="event" class="cursor-pointer">
                <q-popup-proxy ref="qDateProxy" cover transition-show="scale" transition-hide="scale">
                  <q-date v-model="state.expire" mask="DD/MM/YYYY" :options="optionsFn">
                    <div class="row items-center justify-end">
                      <q-btn v-close-popup label="Close" color="primary" flat />
                    </div>
                  </q-date>
                </q-popup-proxy>
              </q-icon>
            </template>
          </q-input>

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
import { reactive } from "vue";
import { useRouter } from "vue-router";
import { toast } from "@/utils/notification";
import { required, lengthMoreThan2 } from '@/utils/validations';
import services from '@/services';

export default {
  setup() {
    const router = useRouter();

    const state = reactive({
      name: "",
      login: "",
      password: "",
      expire: "",
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

    async function createPassword () {
        let response = await services.user_passwords.create({
          name: state.name,
          login: state.login,
          password: state.password,
          expire: state.expire,
          link: state.link,
          observation: state.observation,
          user_id: state.user_id,
        });

        if(response.error) {
            toast({
                type: "negative",
                message: "Erro ao criar senha",
            });
            return;
        }

        toast({
            type: "positive",
            message: "Senha criada com sucesso"
        });
        goToList();
    }

    function toggleVisibilityPassword () {
        state.type_field_password = (state.type_field_password == "text") ? "password" : "text";
        state.icon_field_password = (state.icon_field_password == "visibility") ? "visibility_off" : "visibility";
    }

    function optionsFn(date) {
      let date1 = new Date(date);
      let date2 = new Date(Date.now());
      return date1 > date2;
    }

    return {
      state,
      validation,
      goToList,
      createPassword,
      toggleVisibilityPassword,
      optionsFn,
    };
  },
};
</script>

<style>
</style>