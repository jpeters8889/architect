<template>
    <select class="form-control form-control-input w-full" :name="name">
        <option value="0" :selected="parseInt(value)===0">No</option>
        <option value="1" :selected="parseInt(value)===1">Yes</option>
    </select>
</template>

<script>
    export default {
        props: ['name','value'],
    }
</script>