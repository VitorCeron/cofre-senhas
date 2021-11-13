<template>
  <div class="window-height window-width row">
    <div class="col-7 flex justify-center items-center">
      <div class="q-pa-md" style="max-width: 400px; width: 400px">
        <h4 class="text-center text-weight-bold">Faça o seu cadastro</h4>

        <q-form @submit="createAccount" class="q-gutter-md">
          <q-input 
            v-model="state.name" 
            label="Nome" 
            type="text"
            placeholder="João Silva"
            lazy-rules
            :rules="[validation.required]"
            />

          <q-input 
            v-model="state.email" 
            label="E-mail" 
            type="email"
            placeholder="joaosilva@mail.com"
            lazy-rules
            :rules="[validation.required, validation.email]"
            />

          <q-input 
            v-model="state.password" 
            label="Senha" 
            :type="state.type_field_password"
            placeholder="********"
            ref="password_field"
            lazy-rules
            :rules="[validation.required, validation.lengthMoreThan6]"
            >
                <template v-slot:append>
                    <q-icon :name="state.icon_field_password" @click="toggleVisibilityPassword" class="cursor-pointer" />
                </template>
            </q-input>

          <q-input
            v-model="state.password_confirmation" 
            label="Confirme a Senha" 
            :type="state.type_field_password"
            placeholder="********"
            lazy-rules
            :rules="[validation.required, validation.sameAs(state.password)]"
            >
                <template v-slot:append>
                    <q-icon :name="state.icon_field_password" @click="toggleVisibilityPassword" class="cursor-pointer" />
                </template>
            </q-input>

          <div>
            <q-btn 
                type="submit" 
                color="primary" 
                class="full-width q-mb-sm">
                <q-icon name="person_add" class="q-mr-sm" /> Criar conta
            </q-btn>

            <hr>

            <q-btn
                @click="goToLogin"
                outline
                color="primary" 
                class="full-width q-mt-sm">
                <q-icon name="login" class="q-mr-sm" /> Voltar para o login
            </q-btn>
          </div>
        </q-form>
      </div>
    </div>
    <div class="col-5 bg-grey-2 flex justify-center items-center">
        <img src="../../assets/images/register.png" alt="login" width="400" height="400"/>
    </div>
  </div>
</template>

<script>
import { useQuasar } from "quasar";
import { reactive } from "vue";
import { required, email, lengthMoreThan6, sameAs } from '@/utils/validations';
import { useRouter } from "vue-router";
import services from '@/services';
import { toast } from "@/utils/notification";

export default {
  setup() {
    const router = useRouter();

    const state = reactive({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        type_field_password: 'password',
        icon_field_password: 'visibility'
    })

    const validation = {
        required,
        email,
        lengthMoreThan6,
        sameAs
    }

    async function createAccount () {
        let response = await services.users.register({
            name: state.name,
            email: state.email,
            password: state.password,
            password_confirmation: state.password_confirmation,
        });

        if(response.error) {
            toast({
                type: "negative",
                message: "Erro ao criar a conta",
            });
            return;
        }

        toast({
            type: "positive",
            message: "Conta criada com sucesso"
        });
        goToLogin();
    }

    function goToLogin () {
      router.push({ name: "Login" });
    }

    function toggleVisibilityPassword () {
        state.type_field_password = (state.type_field_password == "text") ? "password" : "text";
        state.icon_field_password = (state.icon_field_password == "visibility") ? "visibility_off" : "visibility";
    }

    return {
      state,
      validation,
      createAccount,
      goToLogin,
      toggleVisibilityPassword,
    };
  },
};
</script>