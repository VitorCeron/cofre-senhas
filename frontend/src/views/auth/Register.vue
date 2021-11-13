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
            :rules="[validation.required]"
            />

          <q-input 
            v-model="state.email" 
            label="E-mail" 
            type="email"
            placeholder="joaosilva@mail.com"
            :rules="[validation.required, validation.email]"
            />

          <q-input 
            v-model="state.password" 
            label="Senha" 
            type="password"
            placeholder="********"
            ref="password_field"
            lazy-rules
            :rules="[validation.required, validation.lengthMoreThan6]"
            />

          <q-input 
            v-model="state.confirm_password" 
            label="Confirme a Senha" 
            type="password"
            placeholder="********"
            lazy-rules
            :rules="[validation.required, validation.sameAs(state.password)]"
            />

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

export default {
  setup() {
    const $q = useQuasar();
    const router = useRouter();

    const state = reactive({
        email: '',
        password: ''
    })

    const validation = {
        required,
        email,
        lengthMoreThan6,
        sameAs
    }

    function createAccount () {
        console.log(state)
    }

    function goToLogin() {
      router.push({ name: "Login" });
    }

    return {
      state,
      validation,
      createAccount,
      goToLogin,
    };
  },
};
</script>