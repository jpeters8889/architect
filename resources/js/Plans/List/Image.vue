<template>
    <div>
        <img :src="value" alt="" style="max-width: 250px" />
    </div>
</template>

<script>
    export default {
        props: ['name', 'label', 'value']
    }
</script>