import {applyAppTokenRefreshInterceptor} from "./refreshToken";

const Axios = require("axios");

const axiosApiInstance = Axios.create();

// Request interceptor for API calls
axiosApiInstance.interceptors.request.use(
    async config => {
        const token = JSON.parse(localStorage.getItem('token'))
        config.headers = {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json',
            'Content-Type': 'application/x-www-form-urlencoded'
        }
        return config;
    },
    error => {
        Promise.reject(error)
    });
applyAppTokenRefreshInterceptor(axiosApiInstance);
export default axiosApiInstance;