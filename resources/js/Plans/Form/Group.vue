<template>
    <div v-bind:class="[this.wrap ? 'flex-wrap' : '']" class="flex p-1 bg-8 rounded-lg shadow-inner-lg shadow">
        <plan-form-field class="m-1" v-for="plan in plans" v-bind:key="plan.column" :plan="plan"></plan-form-field>
    </div>
</template>

<script>
    import {IsAFormField} from 'architect-js-helpers';

    export default {
        mixins: [IsAFormField],

        data: () => ({
           plans: [],
            wrap: false,
        }),

        mounted() {
            this.plans = this.metas.plans;
            this.wrap = this.metas.wrap;
        }
    }
</script>