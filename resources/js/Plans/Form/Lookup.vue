<template>
  <div>
    <input type="text"
           class="form-control form-control-input w-full"
           v-model="actualValue"
           :name="name"
           @keyup="lookup()"/>

    <div :class="searchResultsDisplay" class="relative">
      <ul class="absolute bg-blue-700 w-auto b-1 border-blue-900 shadow rounded-lg text-white">
        <li class="flex justify-between p-2 border-b-1 border-white-50 cursor-pointer hover:bg-white-10"
            v-for="result in lookupResults"
            @click="select(result)"
        >
          {{ result.search_name || result[metas.lookupVariable] }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import {IsAFormField} from 'architect-js-helpers';

export default {
  mixins: [IsAFormField],

  data: () => ({
    lookupResults: [],
    searchResultsDisplay: 'hidden',
    setEmitterValue: false,
    emitterValue: null,
  }),

  mounted() {
    this.actualValue = this.value[this.metas.lookupVariable];

    if (this.value) {
      this.emitterValue = this.value;
    }
  },

  methods: {
    getFormData() {
      return {
        index: this.index,
        name: this.name,
        value: this.metas.customValueAttribute ? this.emitterValue[this.metas.customValueAttribute] : this.actualValue,
      }
    },

    lookup() {
      this.lookupResults = [];
      Architect.request().post('/lookup', {
        blueprint: this.$route.params.blueprint,
        column: this.name,
        value: this.actualValue
      }).then((response) => {
        this.lookupResults = response.data;
        this.searchResultsDisplay = 'block';
      }).catch(error => {
        Architect.$emit(error.response.data.message);
      });
    },

    select(option) {
      this.searchResultsDisplay = 'hidden';
      this.lookupResults = [];

      let newValue = option;
      if (option[this.metas.lookupVariable]) {
        newValue = option[this.metas.lookupVariable];
      }

      option.index = this.index;

      this.$set(this, 'actualValue', newValue);
      this.emitterValue = option;
    }
  }
}
</script>
