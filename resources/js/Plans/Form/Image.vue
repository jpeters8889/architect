<template>
    <div>
        <div class="pb-2 mb-2 border-primary-50 border-b-1" style="max-width: 250px">
            <img v-if="actualValue" :src="actualValue" alt="" />
        </div>
        <input type="file" accept="image/*" ref="imageSelector">
    </div>
</template>

<script>
    import {IsAFormField} from 'architect-js-helpers';

    export default {
        mixins: [IsAFormField],

        methods: {
            getFormData() {
                return {
                    name: this.name,
                    value: window._.get(this.$refs.imageSelector, 'files[0]', ''),
                }
            },
        }
    }
</script>