<template>
    <div class="whitespace-no-wrap">
        {{ getValue() }}
    </div>
</template>

<script>
    import moment from 'moment';

    export default {
        props: ['name', 'label', 'value'],

        methods: {
            getValue() {
                return moment(this.value).format('Do MMM YY');
            }
        }
    }
</script>