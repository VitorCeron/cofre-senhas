<template>
  <div class="window-height window-width row">
    <div class="col-7 flex justify-center items-center">
      <div class="q-pa-md" style="max-width: 400px; width: 400px">
        <h4 class="text-center text-weight-bold">Faça o login</h4>

        <q-form @submit="onSubmit" class="q-gutter-md">
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
            lazy-rules
            :rules="[validation.required, validation.lengthMoreThan6]"
            />

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
import { useQuasar } from "quasar";
import { reactive, ref } from "vue";
import { required, email, lengthMoreThan6 } from '@/utils/validations';
import { useRouter } from "vue-router";

export default {
  setup() {
    const $q = useQuasar();
    const router = useRouter();

    const state = reactive({
        email: '',
        password: '',
    })

    const validation = {
        required,
        email,
        lengthMoreThan6
    }

    function goToRegister() {
      router.push({ name: "Register" });
    }

    return {
      state,
      validation,
      goToRegister,

      onSubmit() {
        console.log(state)
      },
    };
  },
};
</script>