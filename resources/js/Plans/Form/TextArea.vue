<template>
  <textarea
    :ref="'textarea-'+name"
    v-model="actualValue"
    class="form-control form-control-input w-full"
    :name="name"
    :rows="rows"
  />
</template>

<script>
import { IsAFormField } from 'architect-js-helpers';

export default {
  mixins: [IsAFormField],

  computed: {
    rows() {
      if (this.metas.rows !== undefined && this.metas.rows !== null) {
        return this.metas.rows;
      }

      return 3;
    },
  },

  mounted() {
    Architect.$on(`${this.name}-append`, (append) => {
      const cursorPosition = this.$refs[`textarea-${this.name}`].selectionStart;

      const textBefore = this.actualValue.substr(0, cursorPosition);
      const textAfter = this.actualValue.substr(cursorPosition, this.actualValue.length);

      this.actualValue = textBefore + append + textAfter;
    });
  },
};
</script>
