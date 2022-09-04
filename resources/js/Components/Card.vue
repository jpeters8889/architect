<template>
  <div class="w-full bg-white rounded-lg mb-2 p-4 shadow overflow-hidden">
    <component
      :is="component"
      :labels="labels"
      :data="data"
    />
  </div>
</template>

<script>
export default {
  props: {
    component: { type: String, required: true },
    labels: { type: [Object, Array], required: true },
    data: { type: [Object, Array], required: true },
  },
};
</script>
