<template>
  <div class="flex flex-col space-y-3 border-b">
    <div
      v-for="(row, index) in values"
      :key="index"
      class="w-full p-3 border border-blue-900 rounded-lg"
    >
      <div
        v-for="plan in plans"
        :key="plan.column"
        class="w-full"
        :class="{'py-3': plan.component !== 'hidden-field-form'}"
      >
        <plan-form-field
          :key="plan.column"
          :index="index"
          :plan="{
            ...plan,
            value: row[plan.name]
          }"
          :listener="listenerName"
          :emitter="emitterName"
        />
      </div>
    </div>

    <div v-if="values.length === 0 && metas.hideIfEmpty">
      <span>No branches...</span>
    </div>

    <div class="w-full py-3 flex justify-end">
      <button
        class="button button-primary button-default"
        @click.prevent="values.push({})"
      >
        {{ metas.addLabel }}
      </button>
    </div>
  </div>
</template>

<script>
import { IsAFormField } from 'architect-js-helpers';

export default {
  mixins: [IsAFormField],

  data: () => ({
    plans: [],
    values: [{}],
  }),

  computed: {
    listenerName() {
      return `prepare-${this.name}-bulk-form-data`;
    },

    emitterName() {
      return `${this.name}-bulk-form-field-change`;
    },
  },

  mounted() {
    this.plans = this.metas.plans;
    this.wrap = this.metas.wrap;

    Architect.$on(this.emitterName, (field) => {
      this.$set(this.values[field.index], field.name, field.value);
    });

    if (this.value && this.value !== '') {
      this.values = this.value;
    } else if (this.metas.hideIfEmpty) {
      this.values = [];
    }
  },

  methods: {
    getFormData() {
      Architect.$emit(this.listenerName);

      return {
        name: this.name,
        value: JSON.stringify(this.values),
      };
    },
  },
};
</script>
