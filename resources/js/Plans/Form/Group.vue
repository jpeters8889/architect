<template>
    <div v-bind:class="[this.wrap ? 'flex-wrap' : '']" class="flex p-1 bg-8 rounded-lg shadow-inner-lg shadow">
        <plan-form-field
                class="m-1"
                v-for="plan in plans"
                v-bind:key="plan.column"
                :plan="plan"
                :listener="listenerName"
                :emitter="emitterName"
        ></plan-form-field>
    </div>
</template>

<script>
    import {IsAFormField} from 'architect-js-helpers';

    export default {
        mixins: [IsAFormField],

        data: () => ({
            plans: [],
            wrap: false,
            values: {},
        }),

        computed: {
            listenerName() {
                return `prepare-${this.name}-group-form-data`;
            },

            emitterName() {
                return `${this.name}-group-form-field-change`;
            }
        },

        mounted() {
            this.plans = this.metas.plans;
            this.wrap = this.metas.wrap;

            window.Architect.$on(this.emitterName, (field) => {
                this.$set(this.values, field.name, field.value);
            });
        },

        methods: {
            getFormData() {
                window.Architect.$emit(this.listenerName);

                return {
                    name: this.name,
                    value: JSON.stringify(this.values),
                }
            }
        }
    }
</script>