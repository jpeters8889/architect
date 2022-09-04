<template>
  <div>
    <component
      :is="getPageComponent()"
      :id="$route.params.id"
      :page="$route.params.page"
    />
  </div>
</template>

<script>
export default {
  methods: {
    getPageComponent() {
      return `${this.$route.params.page}-page`;
    },
  },
};
</script>
