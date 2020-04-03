<template>
    <textarea class="form-control form-control-input w-full" :name="name" :rows="rows" :ref="'textarea-'+name"
              v-model="actualValue"></textarea>
</template>

<script>
    import {IsAFormField} from 'architect-js-helpers';

    export default {
        mixins: [IsAFormField],

        mounted() {
            window.Architect.$on(this.name + '-append', append => {
                let cursorPosition = this.$refs['textarea-' + this.name].selectionStart;

                let textBefore = this.actualValue.substr(0, cursorPosition);
                let textAfter = this.actualValue.substr(cursorPosition, this.actualValue.length);

                this.actualValue = textBefore + append + textAfter;
            });
        },

        computed: {
            rows() {
                if (this.metas.rows !== undefined && this.metas.rows !== null) {
                    return this.metas.rows;
                }

                return 3;
            },
        }
    }
</script>