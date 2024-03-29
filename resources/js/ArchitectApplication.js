import Vue from 'vue';
import Toasted from 'vue-toasted';
import VTooltip from 'v-tooltip';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import PortalVue from 'portal-vue';
import Datetime from 'vue-datetime';
import ClickOutside from 'vue-click-outside';
import routes from './routes';
import request from './utilities/requestHandler';
import ArchitectApp from './views/ArchitectApp';
import Navigation from './Components/Navigation';

Vue.use(Toasted, {
  router: routes,
  position: 'bottom-right',
  duration: 6000,
});

export default class Architect {
  constructor(config) {
    this.vue = new Vue();
    this.config = config;
    this.afterBootCallbacks = [];
  }

  onBoot(callback) {
    this.afterBootCallbacks.push(callback);
  }

  build() {
    this.afterBoot();

    Vue.component('ArchitectApp', ArchitectApp);
    Vue.component('ArchitectNav', Navigation);
    Vue.component('FontAwesomeIcon', FontAwesomeIcon);
    Vue.use(Toasted);
    Vue.use(VTooltip);
    Vue.use(PortalVue);
    Vue.use(Datetime);

    this.app = new Vue({
      el: '#architect',
      router: routes,
      directives: {
        ClickOutside,
      },
      mounted: () => {
        if (document.querySelector('.hamburger')) {
          document.querySelector('.hamburger').addEventListener('click', () => {
            document.querySelector('.navigation').classList.toggle('hidden');
          });

          Architect.$on('error', (message) => {
            this.vue.$toasted.show(message, { type: 'error' });
          });
        }
      },
    });
  }

  afterBoot() {
    this.afterBootCallbacks.forEach((callback) => {
      callback(Vue);
    });

    this.afterBootCallbacks = [];
  }

  request(options) {
    if (options !== undefined) {
      return request(options);
    }

    return request;
  }

  $on(...args) {
    this.vue.$on(...args);
  }

  $emit(...args) {
    this.vue.$emit(...args);
  }

  success(message) {
    Vue.toasted.show(message, { type: 'success' });
  }

  error(message) {
    Vue.toasted.show(message, { type: 'error' });
  }
}
