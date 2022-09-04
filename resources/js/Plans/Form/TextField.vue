<template>
  <input
    v-model="actualValue"
    type="text"
    class="form-control form-control-input w-full"
    :name="name"
  >
</template>

<script>
import { IsAFormField } from 'architect-js-helpers';

export default {
  mixins: [IsAFormField],
};
</script>
