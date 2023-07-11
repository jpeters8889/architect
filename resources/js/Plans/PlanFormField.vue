<template>
  <div>
    <div
      v-if="shouldDisplayLabel"
      class="text-lg font-semibold text-highlight mb-1"
    >
      <label>
        {{ plan.label }}
      </label>
    </div>

    <div>
      <component
        :is="plan.component"
        :name="plan.name.toString()"
        :value="value"
        :metas="plan.metas"
        :listener="listener"
        :emitter="emitter"
        :index="index"
      />
    </div>
  </div>
</template>

<script>
export default {
  props: {
    plan: {
      type: Object,
      required: true,
    },
    listener: {
      type: String,
      default: 'prepare-form-data',
    },
    emitter: {
      type: String,
      default: 'form-field-change',
    },
    index: {
      type: Number,
      default: 0,
    },
  },

  computed: {
    value() {
      if (!this.plan.value && this.plan.value !== 0) {
        return '';
      }

      if (!isNaN(this.plan.value)) {
        return this.plan.value.toString();
      }

      return this.plan.value;
    },

    shouldDisplayLabel() {
      if ('metas' in this.plan && 'hide_label' in this.plan.metas) {
        return this.plan.hide_label;
      }

      return true;
    }
  },
};
</script>
