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
            @blur="scorePassword"
            v-model="state.password"
            label="Senha"
            :type="state.type_field_password"
            placeholder="********"
            ref="password_field"
            lazy-rules
            :rules="[validation.required]"
          >
            <template v-slot:append>
              <q-icon
                @click="copyPassword"
                name="content_copy"
                class="cursor-pointer q-mr-sm"
              >
                <q-tooltip> Copiar senha </q-tooltip>
              </q-icon>

              <q-icon
                @click="generatePassword"
                name="lock_reset"
                class="cursor-pointer q-mr-sm"
              >
                <q-tooltip> Gerar senha aleatória </q-tooltip>
              </q-icon>

              <q-icon
                :name="state.icon_field_password"
                @click="toggleVisibilityPassword"
                class="cursor-pointer"
              >
                <q-tooltip> Esconder / mostrar senha </q-tooltip>
              </q-icon>
            </template>

            <template v-slot:hint>
              Força da senha {{ state.score_password }} de 100
              <q-linear-progress
                size="sm"
                :value="state.score_password / 100"
                :color="state.color_score_password"
              />
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
                <q-popup-proxy
                  ref="qDateProxy"
                  cover
                  transition-show="scale"
                  transition-hide="scale"
                >
                  <q-date
                    v-model="state.expire"
                    mask="DD/MM/YYYY"
                    :options="optionsFn"
                  >
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
              class="q-mt-md q-mr-md"
            />

            <q-btn
              type="submit"
              color="primary"
              label="Salvar"
              icon="save"
              class="q-mt-md"
            />
          </div>
        </q-form>
      </div>
    </div>
  </q-page>
</template>

<script>
import { reactive, watch } from "vue";
import { useRouter } from "vue-router";
import { toast } from "@/utils/notification";
import { required, lengthMoreThan2 } from "@/utils/validations";
import services from "@/services";

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
      user_id: JSON.parse(window.localStorage.getItem("cofre_senhas_user")).id,
      type_field_password: "password",
      icon_field_password: "visibility",
      score_password: 0,
      color_score_password: "negative",
    });

    const validation = {
      required,
      lengthMoreThan2,
    };

    function goToList() {
      router.push({ name: "ListPasswords" });
    }

    async function createPassword() {
      let response = await services.user_passwords.create({
        name: state.name,
        login: state.login,
        password: state.password,
        expire: state.expire,
        link: state.link,
        observation: state.observation,
        score_password: state.score_password,
        user_id: state.user_id,
      });

      if (response.error) {
        toast({
          type: "negative",
          message: "Erro ao criar senha",
        });
        return;
      }

      toast({
        type: "positive",
        message: "Senha criada com sucesso",
      });
      goToList();
    }

    function toggleVisibilityPassword() {
      state.type_field_password =
        state.type_field_password == "text" ? "password" : "text";
      state.icon_field_password =
        state.icon_field_password == "visibility"
          ? "visibility_off"
          : "visibility";
    }

    function optionsFn(date) {
      let date1 = new Date(date);
      let date2 = new Date(Date.now());
      return date1 > date2;
    }

    function generatePassword() {
      let chars =
        "0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()ABCDEFGHIJKLMNOPQRSTUVWXYZ";
      let passwordLength = 12;
      let password = "";
      for (let i = 0; i <= passwordLength; i++) {
        let randomNumber = Math.floor(Math.random() * chars.length);
        password += chars.substring(randomNumber, randomNumber + 1);
      }
      state.password = password;
    }

    function copyPassword() {
      if (!state.password) {
        return;
      }

      navigator.clipboard.writeText(state.password);
      toast({
        type: "positive",
        message: "Senha copiada com sucesso",
      });
    }

    function scorePassword() {
      let pass = state.password;
      var score = 0;
      if (!pass) return score;

      var letters = new Object();
      for (var i = 0; i < pass.length; i++) {
        letters[pass[i]] = (letters[pass[i]] || 0) + 1;
        score += 5.0 / letters[pass[i]];
      }

      var variations = {
        digits: /\d/.test(pass),
        lower: /[a-z]/.test(pass),
        upper: /[A-Z]/.test(pass),
        nonWords: /\W/.test(pass),
      };

      var variationCount = 0;
      for (var check in variations) {
        variationCount += variations[check] == true ? 1 : 0;
      }
      score += (variationCount - 1) * 10;

      state.score_password = parseInt(score) > 100 ? 100 : parseInt(score);
    }

    watch(
      () => state.score_password,
      () => {
        if (state.score_password > 80) { 
          state.color_score_password = "positive";
          return;
        }

        if (state.score_password > 60) { 
          state.color_score_password = "info";
          return;  
        }

        if (state.score_password >= 30) {
          state.color_score_password = "warning";
          return;
        } 

        state.color_score_password = "negative";
      }
    );

    return {
      state,
      validation,
      goToList,
      createPassword,
      toggleVisibilityPassword,
      optionsFn,
      generatePassword,
      copyPassword,
      scorePassword,
    };
  },
};
</script>

<style>
</style>