<template>
  <q-layout view="hHh Lpr lff">
    <q-header class="glossy" v-if="state.showTopbarAndMenu">
      <q-toolbar class="bg-primary text-white">
        <q-btn
          flat
          dense
          round
          @click="leftDrawerOpen = !leftDrawerOpen"
          aria-label="Menu"
          icon="menu"
        />

        <q-toolbar-title @click="goToHome" class="cursor-pointer">
          Cofre de senhas
        </q-toolbar-title>
        
        <q-btn flat round dense @click="logout">
          <q-icon name="power_settings_new" />
        </q-btn>
        
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
      class="bg-primary text-white"
      mini
      persistent
      v-if="state.showTopbarAndMenu"
    >
      <q-list>
        <q-item-label header>Essential Links</q-item-label>
        <q-item class="active-class" :to="{ name: 'ListPasswords' }">
          <q-tooltip
            transition-show="scale"
            transition-hide="scale"  
            anchor="center right" 
            self="center middle"
          >
            Senhas
          </q-tooltip>
          <q-item-section avatar>
            <q-icon name="lock" />
          </q-item-section>
        </q-item>
        
        <q-item class="active-class" :to="{ name: 'ListLogs' }">
          <q-tooltip
            transition-show="scale"
            transition-hide="scale"  
            anchor="center right" 
            self="center middle"
          >
            Logs
          </q-tooltip>
          <q-item-section avatar>
            <q-icon name="history" />
          </q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view v-slot="{ Component }">
        <transition name="fade">
          <component :is="Component" />
        </transition>
      </router-view>
    </q-page-container>
  </q-layout>
</template>

<script>
import { reactive, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import services from '@/services';
import { toast } from "@/utils/notification";

export default {
  name: 'LayoutDefault',

  setup () {
    const route = useRoute()
    const router = useRouter()
    const state = reactive({
      showTopbarAndMenu: false
    })

    watch(() => route.path, async () => {
      if (route.meta.hasAuth) {
        state.showTopbarAndMenu = true
        const token = window.localStorage.getItem('cofre_senhas_token')
        if (!token) {
          state.showTopbarAndMenu = false;
          toast({
              type: "warning",
              message: "Sua sessão foi encerrada, faça o login novamente.",
          });
          router.push({ name: 'Login' });
          return
        }
      }
    })

    async function logout () {
      await services.users.logout();
      state.showTopbarAndMenu = false;
      window.localStorage.removeItem("cofre_senhas_token");
      window.localStorage.removeItem("cofre_senhas_user");
      router.push({ name: 'Login' });
    }

    function goToHome () {
      router.push({ name: 'ListPasswords' })
    }

    return {
      leftDrawerOpen: ref(false),
      state,
      logout,
      goToHome,
    }
  }
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}


.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.active-class {
  color: white !important;
}
</style>
