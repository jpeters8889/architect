<template>
    <div>
        <component :is="getPageComponent()" :page="$route.params.page" :id="$route.params.id"></component>
    </div>
</template>

<script>
    export default {
        methods: {
            getPageComponent() {
                return this.$route.params.page + '-page'
            },
        }
    }
</script>
