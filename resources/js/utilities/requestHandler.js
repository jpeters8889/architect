import axios from 'axios';

const request = axios.create();

request.defaults.baseURL = window.config.apiRoot;
request.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;

if (document.querySelector('meta[name="api-token"]')) {
  request.defaults.headers.common.Authorization = `Bearer ${document.querySelector('meta[name="api-token"]').content}`;
}

request.interceptors.response.use(
  (response) => response,
  (error) => {
    const status = error.response;

    if (status >= 500) {
      Architect.$emit('error', error.response.data.message);
    }

    return Promise.reject(error);
  },
);

export default request;
