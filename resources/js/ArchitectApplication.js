import Vue from 'vue';
import Toasted from 'vue-toasted';
import VTooltip from 'v-tooltip';
import routes from './routes';
import request from "./utilities/requestHandler";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

Vue.use(Toasted, {
    router: routes,
    position: "bottom-right",
    duration: 6000,
});

export default class Architect {

    constructor(config) {
        this.vue = new Vue();
        this.config = config;
    }

    build() {
        let architect = this;

        Vue.component('font-awesome-icon', FontAwesomeIcon);
        Vue.use(VTooltip);

        this.app = new Vue({
            el: '#architect',
            router: routes,
            components: {},
            mounted: () => {
                document.querySelector('.hamburger').addEventListener('click', () => {
                    document.querySelector('.navigation').classList.toggle('hidden');
                });

                architect.$on('error', message => {
                    this.$toasted.show(message, {type: 'error'});
                });
            }
        });
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
        this.bus.$emit(...args)
    }

    success(message) {
        Vue.toasted.show(message, {type: 'success'});
    }

    error(message) {
        Vue.toasted.show(message, {type: 'error'});
    }
}