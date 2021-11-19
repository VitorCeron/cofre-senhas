<template>
  <div class="window-height window-width row">
    <div class="col-7 flex justify-center items-center">
      <div class="q-pa-md" style="max-width: 400px; width: 400px">
        <h4 class="text-center text-weight-bold">Faça o login</h4>

        <q-form @submit="login" class="q-gutter-md">
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

          <div>
            <q-btn 
                type="submit" 
                color="primary" 
                class="full-width q-mb-sm">
                <q-icon name="login" class="q-mr-sm" /> Entrar
            </q-btn>

            <hr>

            <q-btn 
                @click="goToRegister"
                outline
                color="primary" 
                class="full-width q-mt-sm">
                <q-icon name="person_add" class="q-mr-sm" /> Criar conta
            </q-btn>
          </div>
        </q-form>
      </div>
    </div>
    <div class="col-5 bg-grey-2 flex justify-center items-center">
        <img src="../../assets/images/login.png" alt="login" width="400" height="400"/>
    </div>
  </div>
</template>

<script>
import { reactive } from "vue";
import { required, email, lengthMoreThan6 } from '@/utils/validations';
import { useRouter } from "vue-router";
import services from '@/services';
import { toast } from "@/utils/notification";

export default {
  setup() {
    const router = useRouter();

    const state = reactive({
        email: '',
        password: '',
        type_field_password: 'password',
        icon_field_password: 'visibility'
    })

    const validation = {
        required,
        email,
        lengthMoreThan6
    }

    function goToRegister () {
      router.push({ name: "Register" });
    }

    function goToHome () {
      router.push({ name: "ListPasswords" });
    }

    async function login () {
      let response = await services.users.login({
        email: state.email,
        password: state.password,
      });

      if(response.error) {
          toast({
              type: "negative",
              message: "Erro ao fazer login",
          });
          return;
      }

      window.localStorage.setItem('cofre_senhas_token', response.data.access_token);
      window.localStorage.setItem('cofre_senhas_user', JSON.stringify(response.data.user));
      goToHome();
    }

    function toggleVisibilityPassword () {
        state.type_field_password = (state.type_field_password == "text") ? "password" : "text";
        state.icon_field_password = (state.icon_field_password == "visibility") ? "visibility_off" : "visibility";
    }

    return {
      state,
      validation,
      goToRegister,
      toggleVisibilityPassword,
      login,
    };
  },
};
</script>