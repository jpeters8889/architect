import Vue from 'vue';
import ArchitectApplication from './ArchitectApplication';
import './plugins';
import './components';
import './plans';

Vue.config.productionTip = false;

// (() => {
window.architectBootstrapper = (config) => new ArchitectApplication(config);
// }).call(window);
