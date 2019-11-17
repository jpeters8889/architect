<template>
    <div>
        <div class="text-lg font-semibold text-highlight mb-1">
            <label>
                {{ field.label }}
            </label>
        </div>

        <div>
            <component
                    :is="field.component"
                    :name="field.name"
                    :value="field.value"
                    :metas="field.metas"
            ></component>
        </div>
    </div>
</template>

<script>
    import IsAFormField from '../traits/IsAFormField';

    export default {
        mixins: [IsAFormField],
    }
</script>