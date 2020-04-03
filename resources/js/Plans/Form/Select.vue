<template>
    <select class="form-control form-control-input w-full" v-model="actualValue" :name="name">
        <option v-if="metas.showDefault" disabled value="">Please Select...</option>
            <template v-if="metas.options[Object.keys(metas.options)[0]] instanceof Object">
                <optgroup v-for="(values, label) in metas.options" :label="label">
                    <option v-for="(description, value) in values" :value="value">
                        {{ description }}
                    </option>
                </optgroup>
            </template>

            <template v-else>
                <option v-for="(description, value) in metas.options" :value="value">
                    {{ description }}
                </option>
            </template>
    </select>
</template>

<script>
    import {IsAFormField} from 'architect-js-helpers';

    export default {
        mixins: [IsAFormField],
    }
</script>