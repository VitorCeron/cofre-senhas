<template>
  <q-page padding>
    <span class="text-h4">Atualizar senha</span>
    <hr />

    <div class="row">
      <div class="col-md-6 offset-md-3">
        <form-password @submit-form="updatePassword" />
      </div>
    </div>
  </q-page>
</template>

<script>
import { useRouter } from "vue-router";
import { toast } from "@/utils/notification";
import services from "@/services";
import FormPassword from './FormPassword'

export default {
  components: {
    FormPassword
  },

  setup() {
    const router = useRouter();

    function goToList() {
      router.push({ name: "ListPasswords" });
    }

    async function updatePassword(password) {
      let response = await services.user_passwords.update({
        id: password.id,
        name: password.name,
        login: password.login,
        password: password.password,
        expire: password.expire,
        link: password.link,
        observation: password.observation,
        score_password: password.score_password,
        user_id: password.user_id,
      });

      if (response.error) {
        toast({
          type: "negative",
          message: "Erro ao atualizar senha",
        });
        return;
      }

      toast({
        type: "positive",
        message: "Senha atualizada com sucesso",
      });
      goToList();
    }

    return {
      goToList,
      updatePassword
    };
  },
};
</script>

<style>
</style>