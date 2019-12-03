import Vue from 'vue';
import ArchitectApplication from "./ArchitectApplication";
import './plugins';

Vue.config.productionTip = false;

import './components';

import './plans';

(function() {
   this.architectBootstrapper = function(config) {
       return new ArchitectApplication(config);
   }
}).call(window);
