<template>
    <div>
        <select class="form-control form-control-input w-full" v-model="actualValue" :name="name">
            <option v-for="(description, value) in metas.options" :value="value">
                {{ description }}
            </option>
        </select>

        <div class="p-1 bg-8 rounded-lg shadow-inner-lg shadow mt-2" v-if="actualValue !== ''">
            <div v-for="(plans, option) in metas.switches" v-if="parseInt(actualValue) === parseInt(option)">
                <plan-form-field
                        class="px-2 my-2"
                        v-for="plan in plans"
                        v-bind:key="plan.column"
                        :plan="plan"
                        :listener="listenerName"
                        :emitter="emitterName"
                ></plan-form-field>
            </div>
        </div>
    </div>
</template>

<script>
    import {IsAFormField} from 'architect-js-helpers';

    export default {
        mixins: [IsAFormField],

        computed: {
            listenerName() {
                return `prepare-${this.name}-option-form-data`;
            },

            emitterName() {
                return `${this.name}-option-form-field-change`;
            }
        },

        data: () => ({
            switches: {},
            values: {},
        }),

        mounted() {
            this.switches = this.metas.switches;

            window.Architect.$on(this.emitterName, (field) => {
                this.$set(this.values, field.name, field.value);
            });
        },

        methods: {
            getFormData() {
                this.$set(this.values, 'type_id', this.actualValue);

                window.Architect.$emit(this.listenerName);

                return {
                    name: this.name,
                    value: JSON.stringify(this.values),
                }
            },
        },
    }
</script>