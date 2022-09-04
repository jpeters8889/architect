<template>
  <div />
</template>

<script>
export default {
  mounted() {
    Architect.request().get('/dashboard').then((response) => {
      if (Object.keys(response.data.redirect).length > 0) {
        if (response.data.redirect.asVue) {
          this.$router.push({
            name: response.data.redirect.url,
            params: response.data.redirect.data,
          });
          return;
        }

        window.location.href = response.data.redirect.url;
      }
    });
  },
};
</script>
