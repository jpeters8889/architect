<template>
  <ul
    v-if="lastPage > 1"
    class="flex flex-wrap font-semibold leading-none justify-center"
  >
    <li
      v-if="canGoBack"
      class="border border-blue-900 bg-blue-900 text-white rounded m-px cursor-pointer transition-bg transition-color hover:bg-white hover:text-blue-900"
    >
      <a
        class="p-2 block"
        @click.prevent="goTo(current - 1)"
      >Previous</a>
    </li>

    <li
      v-for="page in pageArray"
      :key="page.label"
      class="border border-blue-900 rounded m-px cursor-pointer transition-bg transition-color"
      :class="page.goTo !== current ? 'bg-blue-900 text-white hover:bg-white hover:text-blue-900' : 'bg-white text-blue-900'"
    >
      <a
        class="p-2 block"
        @click.prevent="goTo(page.goTo)"
      >{{ page.label }}</a>
    </li>

    <li
      v-if="canGoForward"
      class="border border-blue-900 bg-blue-900 text-white rounded m-px cursor-pointer transition-bg hover:bg-white hover:text-blue-900"
    >
      <a
        class="p-2 block"
        @click.prevent="goTo(current + 1)"
      >Next</a>
    </li>
  </ul>
</template>

<script>
export default {
  props: {
    current: {
      required: true,
      type: Number,
    },
    lastPage: {
      required: true,
      type: Number,
    },
    canGoBack: {
      required: true,
      type: Boolean,
    },
    canGoForward: {
      required: true,
      type: Boolean,
    },
  },

  computed: {
    pageArray() {
      const data = [];
      const multiples = Math.ceil(this.lastPage / 5);
      const groups = [];

      for (let x = 0; x < multiples; x += 1) {
        const group = [];
        for (let y = 1; y <= 5; y += 1) {
          group.push((x * 5) + y);
        }
        groups.push(group);
      }

      data.push({
        label: '1',
        goTo: 1,
      });

      if (this.current > 5) {
        data.push({
          label: '...',
          goTo: this.current - 1,
        });
      }

      const currentGroup = groups.findIndex((page) => page.indexOf(this.current) !== -1);

      groups[currentGroup].forEach((page) => {
        if (page > 1 && page < this.lastPage) {
          data.push({
            label: page.toString(),
            goTo: page,
          });
        }
      });

      if (currentGroup + 1 < groups.length) {
        data.push({
          label: '...',
          goTo: groups[currentGroup + 1][0],
        });
      }

      data.push({
        label: this.lastPage.toString(),
        goTo: this.lastPage,
      });

      return data;
    },
  },

  methods: {
    goTo(page) {
      Architect.$emit('paginator-change', page);
    },
  },
};
</script>
