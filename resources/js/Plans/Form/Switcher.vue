<template>
  <div>
    <select
      v-model="actualValue"
      class="form-control form-control-input w-full"
      :name="name"
    >
      <option
        v-for="(description, value) in metas.options"
        :key="value"
        :value="value"
      >
        {{ description }}
      </option>
    </select>

    <div
      v-if="actualValue !== ''"
      class="p-1 bg-blue-100 rounded-lg shadow-inner-lg shadow mt-2"
    >
      <div
        v-for="(plans, option) in metas.switches"
        :key="option"
      >
        <template v-if="parseInt(actualValue) === parseInt(option)">
          <plan-form-field
            v-for="plan in plans"
            :key="plan.column"
            class="px-2 my-2"
            :plan="plan"
            :listener="listenerName"
            :emitter="emitterName"
          />
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import { IsAFormField } from 'architect-js-helpers';

export default {
  mixins: [IsAFormField],

  data: () => ({
    switches: {},
    values: {},
  }),

  computed: {
    listenerName() {
      return `prepare-${this.name}-option-form-data`;
    },

    emitterName() {
      return `${this.name}-option-form-field-change`;
    },
  },

  mounted() {
    this.switches = this.metas.switches;

    Architect.$on(this.emitterName, (field) => {
      this.$set(this.values, field.name, field.value);
    });
  },

  methods: {
    getFormData() {
      this.$set(this.values, 'type_id', this.actualValue);

      Architect.$emit(this.listenerName);

      return {
        name: this.name,
        value: JSON.stringify(this.values),
      };
    },
  },
};
</script>
