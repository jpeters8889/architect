<template>
  <div>
    <loader :show="showLoader"></loader>

    <template v-if="!isLoggedIn">
      <router-view></router-view>
    </template>

    <template v-else>
      <div v-cloak class="flex flex-col min-h-screen">
        <!-- Header bar -->
        <div class="flex justify-between items-center">
          <div class="w-full bg-blue-900 p-2 h-15 flex sm:w-255">
            <div
                class="mr-1 p-1 flex justify-center items-center text-blue-100 font-bold text-3xl cursor-pointer sm:hidden relative"
                @click="showMobileNav = true">
              <font-awesome-icon :icon="['fas', 'bars']"></font-awesome-icon>
              <div v-if="hasNotifications()" class="absolute bg-red-600 rounded-full w-3 h-3 top-0 right-0"></div>
            </div>
            <router-link :to="{name: 'home'}" class="-m-2">
              <img src="/vendor/architect/images/architect-logo.png" alt="" class="h-15"/>
            </router-link>
          </div>
          <div class="hidden sm:flex bg-gray-100 flex-1 p-2 h-15 shadow items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">{{ siteTitle }}</h1>
            <user-info-circle></user-info-circle>
          </div>
        </div>

        <div class="flex flex-1">
          <div class="hidden navigation w-255 max-w-255 sm:block sm:relative p-2 flex-1 bg-blue-700 shadow"
               style="flex: 0 0 255px">
            <architect-nav class="flex-1"></architect-nav>
          </div>

          <div class="p-4 mx-auto flex-1">
            <router-view></router-view>
          </div>
        </div>

      </div>

      <div v-if="showMobileNav" class="fixed top-0 left-0 w-full h-full bg-blue-700 overflow-auto">
        <div class="h-full p-4">
          <architect-nav class="pb-15 overflow-y-scroll"></architect-nav>
        </div>

        <div class="w-full fixed bottom-0 leading-tight cursor-pointer">
          <a class="no-underline text-center text-7 text-xl p-6 text-center text-gray-100 block"
             @click="showMobileNav = false">
            Close
          </a>
        </div>
      </div>

      <portal-target name="modal"></portal-target>
      <portal-target name="secondary-modal"></portal-target>
    </template>
  </div>
</template>

<script>
export default {
  data: () => ({
    showLoader: true,
    showMobileNav: false,
  }),

  methods: {
    hasNotifications() {
      let hasNotifications = false;

      Object.keys(Architect.config.navigation.blueprints).forEach((key) => {
        const blueprints = Architect.config.navigation.blueprints[key];

        if (blueprints.filter(blueprint => blueprint.count > 0).length > 0) {
          hasNotifications = true;
        }
      });

      return hasNotifications;
    }
  },

  computed: {
    isLoggedIn() {
      const routes = ['login', 'logout', 'error'];

      if (routes.includes(this.$route.name)) {
        return false;
      }

      return !!document.querySelector('meta[name="api-token"]');
    },

    siteTitle() {
      return window.config.siteName;
    },
  },

  mounted() {
    Architect.$on('load-start', () => {
      this.showLoader = true;
    });

    Architect.$on('load-end', () => {
      this.showLoader = false;
    });

    Architect.$emit('load-end');

    Architect.$on('close-mobile-nav', () => {
      this.showMobileNav = false;
    });
  },

  watch: {
    $route: function () {
      this.showMobileNav = false;
      window.scrollTo(0, 0);
    }
  }
}
</script>
