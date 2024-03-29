<template>
  <button
    :class="classList"
    :style="styleList"
    @click.prevent="handleClick()"
  >
    <slot />
  </button>
</template>

<script>
export default {

  props: {
    size: {
      type: String,
      default: 'normal',
      validator: (value) => ['small', 'normal', 'large'].indexOf(value) !== -1,
    },
    classes: {
      type: Array,
      default: () => [],
    },
    styles: {
      type: Object,
      default: () => ({}),
    },
    theme: {
      type: String,
      default: 'neutral',
      validator: (value) => ['negative', 'neutral', 'positive'].indexOf(value) !== -1,
    },
    errorEventListener: {
      type: String,
      default: null,
    },
    click: {
      type: Function,
      default: () => () => {},
    },
  },

  data: () => ({
    classList: [],
    styleList: {},
  }),
  mounted() {
    this.initComponent();

    if (this.errorEventListener) {
      Architect.$on(this.errorEventListener, () => {
        this.classList.push('animate-shake', 'error-bg');

        this.classList = this.classList.filter((item) => item !== 'button');

        setTimeout(() => {
          this.buildClassMap();
          this.buildStyleMap();
        }, 500);
      });
    }
  },

  methods: {
    initComponent() {
      this.buildClassMap();
      this.buildStyleMap();
    },

    handleClick() {
      this.click();
    },

    buildClassMap() {
      const map = [
        'rounded-lg',
        'text-white',
        'uppercase',
        'relative',
        'overflow-hidden',
        'tracking-wider',
        'slider-bg',
      ];

      switch (this.size) {
        case 'large':
          map.push(
            'text-xl',
            'px-6',
            'py-3',
          );
          break;
        case 'small':
          map.push(
            'text-base',
            'px-3',
            'py-1',
          );
          break;
        default:
          map.push(
            'text-sm',
            'px-4',
            'py-2',
          );
          break;
      }

      switch (this.theme) {
        case 'positive':
          map.push('bg-green-500');
          break;
        case 'negative':
          map.push('bg-red-700');
          break;
        default:
          map.push('bg-blue-600');
          break;
      }

      this.classList = [...map, ...this.classes];
    },

    buildStyleMap() {
      const styles = {
        transition: 'all .3s',
      };

      this.styleList = { ...styles, ...this.styles };
    },
  },
};
</script>
