<template>
    <datetime v-model="actualValue"
              type="datetime"
              input-class="form-control form-control-input w-full"
              :hidden-name="name"></datetime>
</template>

<script>
    import {IsAFormField} from 'architect-js-helpers';
    import 'vue-datetime/dist/vue-datetime.css'

    export default {
        mixins: [IsAFormField],
    }
</script>
