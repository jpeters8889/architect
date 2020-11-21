<template>
  <div>
    <div class="flex flex-col bg-white rounded-xl shadow mb-2">
      <header class="w-full p-4 flex">
        <h1 class="text-2xl font-semibold text-blue-700">
          {{ dashboard.title }}
        </h1>
      </header>
    </div>

    <!-- Cards -->
    <div class="flex flex-wrap -m-1">
      <div v-for="card in dashboard.cards"
           class="p-1" :class="cardWidth(card.width)">
        <div class="bg-white rounded-lg p-4 shadow overflow-hidden">
          <h1 class="text-xl font-semibold text-blue-700">
            {{ card.title }}
          </h1>

          <div v-if="card.type === 'card'" class="mt-2" v-html="card.content"></div>
          <div v-else-if="card.type === 'chart'" class="mt-2" :style="cardHeight(card.content.height)">
            <chart :name="card.content.name" :data="card.content.data" :types="card.content.types"></chart>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({
    dashboard: {
      title: '',
      cards: [],
    },
  }),

  mounted() {
    this.initComponent();
  },

  methods: {
    initComponent() {
      Architect.$emit('load-start');

      Architect.request().get(`/dashboard/${this.$route.params.dashboard}`).then((response) => {
        if (response.status === 200) {
          this.dashboard = response.data;

          return;
        }

        Architect.error('Dashboard not found');
      }).catch(() => {
        Architect.error('Dashboard not found');
      }).finally(() => {
        Architect.$emit('load-end');
      });
    },

    cardWidth(width) {
      switch (width) {
        case 'half':
          return 'w-full sm:w-1/2';
        case 'quarter':
          return 'w-full sm:w-1/2 md:w-1/4';
        case 'third':
          return 'w-full md:w-1/3';
        default:
          return 'w-full';
      }
    },

    cardHeight(height) {
      return {
        height: height + 'px',
      };
    }
  },

  watch: {
    $route() {
      this.initComponent();
    }
  },
}
</script>
