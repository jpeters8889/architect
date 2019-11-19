import Vue from 'vue'

import Header from './Components/Header';
import Table from "./Components/Table";
import BlueprintField from "./Components/BlueprintField";
import Modal from "./Components/Modal";

Vue.component('blueprint-field', BlueprintField);
Vue.component('header-component', Header);
Vue.component('table-component', Table);
Vue.component('modal', Modal);