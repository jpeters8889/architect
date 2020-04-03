<template>
    <component
            :is="component"
            :value="value"
            :name="name"
            :label="label"
    ></component>
</template>

<script>
    export default {
        props: ['component', 'value', 'name', 'label'],
    }
</script>