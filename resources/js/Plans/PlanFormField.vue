<template>
    <div>
        <div class="text-lg font-semibold text-highlight mb-1">
            <label>
                {{ plan.label }}
            </label>
        </div>

        <div>
            <component
                    :is="plan.component"
                    :name="plan.name"
                    :value="plan.value"
                    :metas="plan.metas"
                    :listener="listener"
                    :emitter="emitter"
            ></component>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            plan: Object,
            listener: {
                type: String,
                default: 'prepare-form-data',
            },
            emitter: {
                type: String,
                default: 'form-field-change',
            }
        },
    }
</script>