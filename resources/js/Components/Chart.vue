<template>
  <div class="h-full w-full">
    <div class="h-full w-full" :id="name+'_chart'"></div>
  </div>
</template>

<script>
export default {
  props: {
    name: {
      required: true,
      type: String,
    },

    data: {
      required: true,
      type: Object,
    },

    types: {
      required: true,
      type: Array,
    }
  },

  data: () => ({
    chart: null,
  }),

  mounted() {
    let hooks = null;

    if (this.hooks) {
      hooks = new ChartisanHooks();

      hooks = hooks.legend().tooltip();
    }

    this.chart = new Chartisan({
      el: `#${this.name}_chart`,
      data: this.data,
      hooks: new ChartisanHooks()
          .legend()
          .tooltip()
          .colors()
          .datasets(this.types),
    });
  }
}
</script>
