<template>
    <select class="form-control form-control-input w-full" :name="name" v-model="actualValue">
        <option value="0">No</option>
        <option value="1">Yes</option>
    </select>
</template>

<script>
    import {IsAFormField} from 'architect-js-helpers';

    export default {
        mixins: [IsAFormField],

        // mounted() {
        //   if(this.actualValue==='') {
        //       this.actualValue = '0';
        //   }
        // },
    }
</script>