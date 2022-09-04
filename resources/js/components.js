import Vue from 'vue';

import Header from './Components/Header';
import Table from './Components/Table';
import BlueprintField from './Components/BlueprintField';
import Modal from './Components/Modal';
import Paginator from './Components/Paginator';
import Card from './Components/Card';
import Loader from './Components/Loader';

import Input from './Forms/Input';
import Button from './Forms/Button';
import Search from './Components/Search';
import Filters from './Components/Filters';
import UserCircle from './Components/UserCircle';
import ChangePassword from './Components/ChangePassword';
import Chart from './Components/Chart';

Vue.component('BlueprintField', BlueprintField);
Vue.component('BlueprintFilter', Filters);
Vue.component('BlueprintSearch', Search);
Vue.component('ChangePassword', ChangePassword);
Vue.component('HeaderComponent', Header);
Vue.component('TableComponent', Table);
Vue.component('Pagination', Paginator);
Vue.component('Modal', Modal);
Vue.component('CardComponent', Card);
Vue.component('Loader', Loader);
Vue.component('UserInfoCircle', UserCircle);
Vue.component('Chart', Chart);

Vue.component('FormInput', Input);
Vue.component('FormButton', Button);
