<template>
  <component
    :is="component"
    :id="id"
    :value="value"
    :name="name"
    :label="label"
  />
</template>

<script>
export default {
  props: {
    component: {
      type: String,
      required: true,
    },
    value: {
      type: [String, Boolean, Number, Object, Array, undefined],
      default: () => undefined,
      required: false,
    },
    name: {
      type: String,
      required: true,
    },
    label: {
      type: String,
      required: true,
    },
    id: {
      type: [String, Number],
      required: true,
    },
  },
};
</script>
