<template>
    <div class="flex bg-white w-full p-2 mb-2 items-center">
        <form-input type="search" placeholder="Search..." name="search" :value="searchText"></form-input>
    </div>
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
