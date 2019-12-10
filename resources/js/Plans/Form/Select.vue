<template>
    <select class="form-control form-control-input w-full" v-model="actualValue" :name="name">
        <option v-for="(option, index) in metas.options" :value="index">
            {{ option }}
        </option>
    </select>
</template>

<script>
    import {IsAFormField} from 'architect-js-helpers';

    export default {
        mixins: [IsAFormField],
    }
</script>