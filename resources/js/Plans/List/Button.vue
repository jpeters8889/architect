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
        props: ['name', 'label', 'value', 'id'],

        methods: {
            handleClick() {
                Architect.request().post('button', {
                    blueprint: this.$route.params.blueprint,
                    button: this.name,
                    id: this.id,
                }).then((response) => {
                    if(response.status === 200) {
                        if(response.data !== '') {
                            window.open(response.data);
                        }
                        return;
                    }

                    Architect.error('An error has occurred');
                });
            }
        }
    }
</script>
