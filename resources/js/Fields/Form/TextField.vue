<template>
    <input type="text" class="form-control form-control-input w-full" :value="value" :name="name"/>
</template>

<script>
    export default {
        props: ['name','value'],
    }
</script>