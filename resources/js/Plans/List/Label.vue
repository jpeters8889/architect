<template>
    <div v-html="value"></div>
</template>

<script>
    export default {
        props: ['name', 'label', 'value']
    }
</script>
