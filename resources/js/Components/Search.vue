<template>
    <form-input type="search" placeholder="Search..." name="search" :value="searchText" classes="w-255"></form-input>
</template>

<script>
    export default {
        data: () => ({
            searchText: '',
        }),

        mounted() {
            this.$root.$on('search-set-value', (value) => {
                //
            });
        }
    }
</script>
