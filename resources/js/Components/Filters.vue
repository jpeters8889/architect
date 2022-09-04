<template>
  <div>
    <div class="button button-primary px-4 py-2 rounded cursor-pointer hover:bg-highlight transition-bg"
         @click="showFilterBox = true">
      Filters
      <span v-if="Object.keys(appliedFilters).length > 0">
                ({{ Object.keys(appliedFilters).length }})
            </span>
    </div>

    <div class="fixed bg-black-90 fixed w-full h-full top-0 left-0 flex justify-center items-center z-50"
         v-if="showFilterBox">
      <div class="absolute top-0 right-0 bg-white w-300 h-full overflow-y-scroll overflow-x-hidden">
        <div
            class="flex justify-between p-2 bg-gray-100 text-blue-900 text-xl font-semibold border-b-2 border-blue-900 items-center">
          <h2>
            Available Filters
          </h2>
          <font-awesome-icon :icon="['fas', 'times']" @click="showFilterBox = false"></font-awesome-icon>
        </div>

        <div v-for="(filter, key) in filters" class="flex flex-col bg-blue-300 overflow-hidden rounded-xl m-2"
             :key="key">
          <div class="flex justify-between items-center p-2">
            <h2 class="text-lg font-semibold flex-1 cursor-pointer" @click="show[key] = !show[key]">
              {{ filter.name }}
            </h2>
            <a class="cursor-pointer" v-if="Object.keys(appliedFilters).includes(key)" @click="removeFilter(key)">
              Clear
            </a>
          </div>

          <ul v-show="show[key]">
            <li v-for="(name, value) in filter.options"
                class="transition-bg p-2 cursor-pointer"
                :class="isFilterApplied(key, value) ? 'bg-green-400' : 'bg-blue-200 hover:bg-blue-100'"
                @click="toggleFilter(key, value, filter.multi)">
              {{ name }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    filters: {
      type: Object,
      default: {},
    }
  },

  data: () => ({
    showFilterBox: false,
    appliedFilters: {},
    show: {},
  }),

  mounted() {
    Object.keys(this.filters).forEach((key) => {
      this.$set(this.show, key, false);

      if (this.filters[key].default) {
        this.$set(this.appliedFilters, key, this.filters[key].default)
      }
    });
  },

  methods: {
    toggleFilter(key, value, multi = false) {
      if (this.isFilterApplied(key, value)) {
        this.removeFilter(key, value, multi);
        return;
      }

      this.addFilter(key, value, multi);
    },

    addFilter(key, value, multi = false) {
      if (multi && this.appliedFilters[key]) {
        value = `${this.appliedFilters[key]},${value}`
      }

      this.$set(this.appliedFilters, key, value)
    },

    removeFilter(key, value, multi = false) {
      if (this.appliedFilters[key]) {
        if (multi) {
          let current = this.appliedFilters[key].split(',');

          const index = current.indexOf(value);
          if (index > -1) {
            current.splice(index, 1);
          }

          if (current.length) {
            this.$set(this.appliedFilters, key, current.join(','))

            return;
          }
        }

        this.$delete(this.appliedFilters, key);
      }
    },

    isFilterApplied(key, value) {
      if (!this.appliedFilters[key]) {
        return false;
      }

      const appliedFilters = this.appliedFilters[key].split(',');

      return appliedFilters.includes(value);
    },
  },

  watch: {
    appliedFilters: {
      deep: true,
      handler: function () {
        Architect.$emit('filter-change', {
          filters: this.appliedFilters,
        });
      },
    }
  }
}
</script>
