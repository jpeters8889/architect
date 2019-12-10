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

Vue.component('bool-list', BoolList);
Vue.component('date-time-list', DateTimeList);
Vue.component('label-list', LabelList)
Vue.component('text-area-list', TextAreaList);
Vue.component('text-field-list', TextFieldList);

Vue.component('plan-form-field', PlanFormField);

Vue.component('bool-form', Bool);
Vue.component('group-form', Group);
Vue.component('lookup-form', Lookup);
Vue.component('select-form', Select);
Vue.component('text-area-form', TextArea);
Vue.component('text-field-form', TextField);
