import Vue from 'vue'

import Header from './Components/Header';
import Table from "./Components/Table";
import BlueprintField from "./Components/BlueprintField";
import Modal from "./Components/Modal";
import Paginator from "./Components/Paginator";
import Card from "./Components/Card";
import Loader from "./Components/Loader";

import Input from "./Forms/Input";
import Button from "./Forms/Button";
import Search from "./Components/Search";
import Filters from "./Components/Filters";

Vue.component('blueprint-field', BlueprintField);
Vue.component('blueprint-filter', Filters);
Vue.component('blueprint-search', Search);
Vue.component('header-component', Header);
Vue.component('table-component', Table);
Vue.component('pagination', Paginator);
Vue.component('modal', Modal);
Vue.component('card-component', Card);
Vue.component('loader', Loader);

Vue.component('form-input', Input);
Vue.component('form-button', Button);
