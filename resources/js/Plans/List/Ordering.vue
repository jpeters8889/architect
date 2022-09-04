<template>
  <div>
    {{ value }}
    <font-awesome-icon
      :icon="['fas', 'caret-down']"
      class="text-xl text-primary cursor-pointer"
      @click.prevent="changeOrder('down')"
    />

    <font-awesome-icon
      v-if="parseInt(value) > 1"
      :icon="['fas', 'caret-up']"
      class="text-xl text-primary cursor-pointer"
      @click.prevent="changeOrder('up')"
    />
  </div>
</template>

<script>
export default {
  props: {
    name: {
      type: String,
      required: true,
    },
    label: {
      type: String,
      required: true,
    },
    value: {
      type: String,
      required: true,
    },
  },

  methods: {
    changeOrder(direction) {
      Architect.request().post('/order', {
        blueprint: this.$route.params.blueprint,
        name: this.name,
        value: this.value,
        order: direction,
      }).then(() => {
        Architect.$emit('reload-page');
      });
    },
  },
};
</script>
