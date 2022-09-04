<template>
  <button
    class="button button-primary px-2 py-1 rounded shadow leading-none"
    @click.prevent="handleClick()"
    v-text="label"
  />
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
      type: [String, undefined],
      default: () => undefined,
      required: false,
    },
    id: {
      required: true,
      type: [String, Number],
    },
  },

  methods: {
    handleClick() {
      Architect.request().post('button', {
        blueprint: this.$route.params.blueprint,
        button: this.name,
        id: this.id,
      }).then((response) => {
        if (response.status === 200) {
          if (response.data !== '') {
            window.open(response.data);
          }
          return;
        }

        Architect.error('An error has occurred');
      });
    },
  },
};
</script>
