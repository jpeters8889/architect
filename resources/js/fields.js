import Vue from 'vue'
import TextFieldList from "./Fields/List/TextField";
import TextAreaList from "./Fields/List/TextArea";
import BoolList from "./Fields/List/Bool";
import DateTimeList from "./Fields/List/DateTime";
import FormField from "./Fields/FormField";
import TextField from "./Fields/Form/TextField";
import TextArea from "./Fields/Form/TextArea";
import Bool from "./Fields/Form/Bool";
import Tags from "./Fields/Form/Tags";
import ImageManager from "./Fields/Form/ImageManager";

Vue.component('bool-list', BoolList);
Vue.component('date-time-list', DateTimeList);
Vue.component('text-area-list', TextAreaList);
Vue.component('text-field-list', TextFieldList);

Vue.component('form-field', FormField);

Vue.component('bool-form', Bool);
Vue.component('image-manager-form', ImageManager);
Vue.component('tags-form', Tags);
Vue.component('text-area-form', TextArea);
Vue.component('text-field-form', TextField);
