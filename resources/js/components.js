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
import UserCircle from "./Components/UserCircle";
import ChangePassword from "./Components/ChangePassword";
import Chart from "./Components/Chart";

Vue.component('blueprint-field', BlueprintField);
Vue.component('blueprint-filter', Filters);
Vue.component('blueprint-search', Search);
Vue.component('change-password', ChangePassword)
Vue.component('header-component', Header);
Vue.component('table-component', Table);
Vue.component('pagination', Paginator);
Vue.component('modal', Modal);
Vue.component('card-component', Card);
Vue.component('loader', Loader);
Vue.component('user-info-circle', UserCircle);
Vue.component('chart', Chart);

Vue.component('form-input', Input);
Vue.component('form-button', Button);
