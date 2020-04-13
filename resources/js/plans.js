import Vue from 'vue'
import TextFieldList from "./Plans/List/TextField";
import TextAreaList from "./Plans/List/TextArea";
import BoolList from "./Plans/List/Bool";
import DateTimeList from "./Plans/List/DateTime";
import PlanFormField from "./Plans/PlanFormField";
import TextField from "./Plans/Form/TextField";
import TextArea from "./Plans/Form/TextArea";
import Bool from "./Plans/Form/Bool";
import Group from "./Plans/Form/Group";
import LabelList from "./Plans/List/Label";
import Select from "./Plans/Form/Select";
import Lookup from "./Plans/Form/Lookup";
import Switcher from "./Plans/Form/Switcher";
import OrderingList from "./Plans/List/Ordering";
import ImageList from "./Plans/List/Image";
import ImageForm from "./Plans/Form/Image";
import Button from "./Plans/List/Button";
import DateTimeForm from "./Plans/Form/DateTime";

Vue.component('bool-list', BoolList);
Vue.component('button-list', Button);
Vue.component('date-time-list', DateTimeList);
Vue.component('image-list', ImageList);
Vue.component('label-list', LabelList);
Vue.component('ordering-list', OrderingList);
Vue.component('text-area-list', TextAreaList);
Vue.component('text-field-list', TextFieldList);

Vue.component('plan-form-field', PlanFormField);

Vue.component('bool-form', Bool);
Vue.component('date-time-form', DateTimeForm);
Vue.component('group-form', Group);
Vue.component('image-form', ImageForm);
Vue.component('lookup-form', Lookup);
Vue.component('select-form', Select);
Vue.component('switcher-form', Switcher);
Vue.component('text-area-form', TextArea);
Vue.component('text-field-form', TextField);
