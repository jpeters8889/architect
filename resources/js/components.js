import Vue from 'vue'

import Header from './Components/Header';
import Table from "./Components/Table";
import BlueprintField from "./Components/BlueprintField";
import Modal from "./Components/Modal";
import Paginator from "./Components/Paginator";
import Card from "./Components/Card";

Vue.component('blueprint-field', BlueprintField);
Vue.component('header-component', Header);
Vue.component('table-component', Table);
Vue.component('pagination', Paginator);
Vue.component('modal', Modal);
Vue.component('card-component', Card);