<template>
    <component
            :is="component"
            :value="value"
            :name="name"
            :label="label"
            :id="id"
    ></component>
</template>

<script>
    export default {
        props: ['component', 'value', 'name', 'label', 'id'],
    }
</script>
