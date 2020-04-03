<template>
    <button
        class="button button-primary px-2 py-1 rounded shadow leading-none"
        v-text="label"
        @click.prevent="handleClick()"
    >
    </button>
</template>

<script>
    export default {
        props: ['name', 'label', 'value'],

        methods: {
            handleClick() {
                //
            }
        }
    }
</script>