<template>
  <select
    ref="select"
    v-model="actualValue"
    class="form-control form-control-input w-full"
    :name="name"
    :multiple="metas.multiSelect"
  >
    <option
      v-if="metas.showDefault"
      disabled
      value=""
    >
      Please Select...
    </option>
    <template v-if="metas.options[Object.keys(metas.options)[0]] instanceof Object">
      <optgroup
        v-for="(values, label) in metas.options"
        :key="label"
        :label="label"
      >
        <option
          v-for="(description, value) in values"
          :key="value"
          :value="value"
        >
          {{ description }}
        </option>
      </optgroup>
    </template>

    <template v-else>
      <option
        v-for="(description, value) in metas.options"
        :key="value"
        :value="value"
      >
        {{ description }}
      </option>
    </template>
  </select>
</template>

<script>
import { IsAFormField } from 'architect-js-helpers';

export default {
  mixins: [IsAFormField],

  created() {
    if (this.value !== undefined) {
      this.actualValue = this.value;

      if (this.metas.multiSelect && typeof this.actualValue === 'string') {
        this.actualValue = this.actualValue.split(',');
      }
    }
  },

  mounted() {
    if (this.value !== undefined) {
      this.actualValue = this.value;

      if (this.metas.multiSelect && typeof this.actualValue === 'string') {
        this.actualValue = this.actualValue.split(',');
      }
    }

    this.bootstrapListeners();

    this.debouncedEvents = window._.debounce(this.dispatchEvents, 500);
  },

  methods: {
    getFormData() {
      let value = this.actualValue;

      if (this.metas.multiSelect) {
        value = Array.from(this.$refs.select.selectedOptions).map((option) => option.value);
      }

      return {
        index: this.index,
        name: this.name,
        value,
      };
    },
  },
};
</script>
