<template>
  <input
    v-model="actualValue"
    type="hidden"
    :name="name"
  >
</template>

<script>
import { IsAFormField } from 'architect-js-helpers';

export default {
  mixins: [IsAFormField],
};
</script>
