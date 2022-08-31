<template>
  <div class="h-full w-full">
    <div class="flex justify-between">
      <h1 class="text-xl font-semibold text-blue-700 w-1/2">
        {{ name }}
      </h1>

      <div class="flex justify-end flex-col">
        <div class="flex justify-end">
          <select class="form-control-input form-control-small" v-model="selectedDateRange" @change="getData()">
            <option value="today">Today</option>
            <option value="yesterday">Yesterday</option>
            <option value="thisWeek">So Far This Week</option>
            <option value="thisMonth">So Far This Month</option>
            <option value="thisYear">So Far This Year</option>
            <option value="last7">Past 7 Days</option>
            <option value="last14">Past 14 Days</option>
            <option value="lastMonth">Past Month</option>
            <option value="lastYear">Past Year</option>
            <option value="custom">Custom</option>
          </select>
        </div>
        <div v-if="selectedDateRange === 'custom'" class="mt-2 flex justify-end space-x-2">
          <input class="form-control-input form-control-small" type="date" v-model="customDateFrom" @change="getData()" />
          <input class="form-control-input form-control-small" type="date" v-model="customDateTo" @change="getData()" />
        </div>
      </div>
    </div>

    <canvas class="h-full w-full" ref="chartElem"></canvas>
  </div>
</template>

<script>
import {Chart, registerables} from 'chart.js';

export default {
  props: {
    name: {
      required: true,
      type: String,
    },

    slug: {
      required: true,
      type: String,
    },
  },

  data: () => ({
    chart: null,
    selectedDateRange: null,
    customDateFrom: null,
    customDateTo: null,
  }),

  methods: {
    getData() {
      if (this.selectedDateRange === 'custom' && (!this.customDateFrom || !this.customDateTo)) {
        return;
      }

      let url = `dashboard/${this.$route.params.dashboard}/chart/${this.slug}`;

      if (this.selectedDateRange) {
        url += `?range=${this.selectedDateRange}`;

        if(this.selectedDateRange === 'custom') {
          url += `&from=${this.customDateFrom}&to=${this.customDateTo}`
        }
      }

      Architect.request().get(url).then((response) => {
        if (this.chart) {
          this.chart.destroy();
        }

        this.chart = new Chart(this.$refs.chartElem, response.data.chart);
        this.selectedDateRange = response.data.selectedDateRange;
      });
    }
  },

  mounted() {
    Chart.register(...registerables);

    this.getData();
  }
}
</script>
