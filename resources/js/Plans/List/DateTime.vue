<template>
  <div class="whitespace-no-wrap">
    {{ getValue() }}
  </div>
</template>

<script>
import moment from 'moment';

export default {
  props: {
    name: {
      type: String,
      required: true,
    },
    label: {
      type: String,
      required: true,
    },
    value: {
      type: String,
      required: false,
    },
  },

  methods: {
    getValue() {
      return moment(this.value).format('Do MMM YY HH:mm:ss');
    },
  },
};
</script>
