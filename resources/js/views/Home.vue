<template>
</template>

<script>
    export default {
        mounted() {
            window.Architect.request().get('/dashboard').then((response) => {
                if (response.data.redirect instanceof Object) {
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
        }
    }
</script>
