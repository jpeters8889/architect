<template>
    <div>
        {{ value }}
        <font-awesome-icon
                :icon="['fas', 'caret-down']"
                class="text-xl text-primary cursor-pointer"
                @click.prevent="changeOrder('down')"
        ></font-awesome-icon>

        <font-awesome-icon
                :icon="['fas', 'caret-up']"
                v-if="parseInt(value) > 1"
                class="text-xl text-primary cursor-pointer"
                @click.prevent="changeOrder('up')"
        ></font-awesome-icon>
    </div>
</template>

<script>
    export default {
        props: ['name', 'label', 'value'],

        methods: {
            changeOrder(direction) {
                window.Architect.request().post('/order', {
                    blueprint: this.$route.params.blueprint,
                    name: this.name,
                    value: this.value,
                    order: direction
                }).then(() => {
                   window.Architect.$emit('reload-page');
                });
            }
        }
    }
</script>