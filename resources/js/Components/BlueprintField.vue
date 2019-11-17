<template>
    <component
            :is="component"
            :value="value"
            :name="name"
    ></component>
</template>

<script>
    export default {
        props: ['component', 'value', 'name'],
    }
</script>