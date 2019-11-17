<template>
    <textarea class="form-control form-control-input w-full" :name="name" :rows="rows">
        {{ value }}
    </textarea>
</template>

<script>
    export default {
        props: ['name','value','metas'],

        computed: {
            rows() {
                if(this.metas.rows !== undefined && this.metas.rows !== null) {
                    return this.metas.rows;
                }

                return 3;
            }
        }
    }
</script>