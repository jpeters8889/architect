import axios from 'axios';
import lodash from 'lodash';
import moment from 'moment';
import icons from './icons';

window.axios = axios;
window._ = lodash;
window.moment = moment;

icons();
