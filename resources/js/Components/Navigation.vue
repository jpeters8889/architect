<template>
  <div class="h-full overflow-hidden">
    <div>
      <h3 class="flex items-center text-1 p-2 text-xl bg-blue-900 text-gray-100 rounded mb-2"
          @click="toggleDashboards()">
        <div class="mr-1 text-center w-8 text-gray-300">
          <font-awesome-icon :icon="['fas', 'home']"></font-awesome-icon>
        </div>
        <span>Dashboards</span>
      </h3>

      <ul class="list-none mb-8">
        <li v-if="showDashboards" v-for="dashboard in dashboards"
            class="leading-tight mb-2 overflow-hidden rounded"
            :class="isActiveRoute(dashboard.route, 'dashboard') ? 'bg-green-500' : 'bg-blue-200'">
          <router-link
              :to="{
                                name: 'dashboard',
                                params: {
                                    dashboard: dashboard.route,
                                }
                            }"
              @click="navigating()"
              class="slider-bg no-underline text-justify text-xl flex items-center p-2"
          >
            <div class="mr-1 text-center w-8">
              <font-awesome-icon :icon="['fas', 'door-closed']"></font-awesome-icon>
            </div>

            <div class="flex-1">{{ dashboard.label }}</div>
          </router-link>
        </li>
      </ul>
    </div>

    <div v-for="building in buildings">
      <h3 class="flex items-center text-1 p-2 text-xl bg-blue-900 text-gray-100 rounded mb-2"
          v-if="buildings.length > 1" @click="toggleBuilding(building)">
        <div class="mr-1 text-center w-8 text-gray-300">
          <font-awesome-icon :icon="['far', 'building']"></font-awesome-icon>
        </div>
        <span>{{ building }}</span>
      </h3>

      <ul class="list-none mb-8">
        <li v-if="show[building]" v-for="blueprint in blueprints[building]"
            class="leading-tight mb-2 overflow-hidden rounded"
            :class="isActiveRoute(blueprint.route) ? 'bg-green-500' : 'bg-blue-200'">
          <router-link
              :to="{
                                name: 'blueprintList',
                                params: {
                                    blueprint: blueprint.route,
                                }
                            }"
              @click="navigating()"
              class="slider-bg no-underline text-justify text-xl flex items-center p-2"
          >
            <div class="mr-1 text-center w-8">
              <font-awesome-icon :icon="['fas', 'cogs']"></font-awesome-icon>
            </div>

            <div class="flex-1">{{ blueprint.label }}</div>

            <div v-if="blueprint.count > 0"
                 class="bg-red-500 rounded-full p-2 text-sm text-white font-semibold">
              {{ blueprint.count }}
            </div>
          </router-link>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({
    show: {},
    showDashboards: true,
  }),

  computed: {
    buildings() {
      return window.config.navigation.buildings;
    },

    blueprints() {
      return window.config.navigation.blueprints;
    },

    dashboards() {
      return window.config.dashboards;
    }
  },

  mounted() {
    this.buildings.forEach((building) => {
      this.$set(this.show, building, true);
    });
  },

  methods: {
    isActiveRoute(route, check = 'blueprint') {
      return route === this.$route.params[check];
    },

    toggleBuilding(building) {
      this.show[building] = !this.show[building];
    },

    toggleDashboards() {
      this.showDashboads = !this.showDashboads;
    },

    navigating() {
      Architect.$emit('close-mobile-nav');
    }
  }
}
</script>
