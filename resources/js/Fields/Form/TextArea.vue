<template>
    <textarea class="form-control form-control-input w-full" :name="name" :rows="rows" :ref="'textarea-'+name" v-model="actualValue"></textarea>
</template>

<script>
    import IsAFormField from '../../traits/IsAFormField';

    export default {
        mixins: [IsAFormField],

        props: ['name', 'value', 'metas'],

        mounted() {
            window.Architect.$on(this.name + '-append', append => {
                let cursorPosition = this.$refs['textarea-' + this.name].selectionStart;

                let textBefore = this.actualValue.substr(0, cursorPosition);
                let textAfter = this.actualValue.substr(cursorPosition, this.actualValue.length);

                console.log(textBefore);
                console.log(append);
                console.log(textAfter);

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