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
      <div
        v-for="(card, index) in dashboard.cards"
        :key="index"
        class="p-1"
        :class="cardWidth(card.width)"
      >
        <template v-if="card.type === 'card'">
          <div class="bg-white rounded-lg p-4 shadow overflow-hidden">
            <h1 class="text-xl font-semibold text-blue-700">
              {{ card.title }}
            </h1>

            <div
              class="mt-2"
              v-html="card.content"
            />
          </div>
        </template>

        <template v-else-if="card.type === 'chart'">
          <div class="bg-white rounded-lg p-4 shadow overflow-hidden">
            <chart
              :name="card.title"
              :slug="card.content.name"
            />
          </div>
        </template>
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

  watch: {
    $route() {
      this.initComponent();
    },
  },

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
      })
        .finally(() => {
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
        height: `${height}px`,
      };
    },
  },
};
</script>
