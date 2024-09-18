import axios from 'axios'

const api_axios = axios.create()

api_axios.defaults.baseURL = import.meta.env.VITE_APP_API_ACCESS

const Plugin = {
    install(app, options) {
      // Request interceptor
      api_axios.interceptors.request.use(async request => {
        let token = await app.config.globalProperties.updateToken();
        request.headers = request.headers ?? {};
        request.headers.Authorization = `Bearer ${token}`;
        return request;
      });


      api_axios.interceptors.response.use(
        response => {
          if (response.headers['content-disposition']) {
            return response;
          }
          return ("data" in response)? response.data: response;
        },
        error => {
          return Promise.reject(error);
        });

        app.config.globalProperties.$AngelusApi = api_axios;
    },
};

export default Plugin;
