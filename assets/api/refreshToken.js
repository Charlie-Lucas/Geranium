import axios from "axios";

const shouldIntercept = (error) => {
    try {
        return error.response.status === 401  && error.response.data.message === "Expired JWT Token"
    } catch (e) {
        return false;
    }
};

const setTokenData = (tokenData = {}, axiosClient) => {
    localStorage.setItem('token', JSON.stringify(tokenData.token));
    localStorage.setItem('refresh_token', JSON.stringify(tokenData.refresh_token));
};
const destroyTokenData = (tokenData = {}, axiosClient) => {
    localStorage.removeItem('token');
    localStorage.removeItem('refresh_token');
};

const handleTokenRefresh = () => {
    const refreshToken = JSON.parse(window.localStorage.getItem('refresh_token'));
    return new Promise((resolve, reject) => {
        axios.post('/api/token/refresh', { refresh_token : refreshToken })
            .then(({data}) => {
                const tokenData = {
                    token: data.token,
                    refresh_token: data.refresh_token,
                };
                resolve(tokenData);
            })
            .catch((err) => {
                reject(err);
            })
    });
};

const attachTokenToRequest = (request, token) => {
    request.headers['Authorization'] = 'Bearer ' + token;
};

export const applyAppTokenRefreshInterceptor = (axiosClient, customOptions = {}) => {
    let isRefreshing = false;
    let failedQueue = [];

    const options = {
        attachTokenToRequest,
        handleTokenRefresh,
        setTokenData,
        destroyTokenData,
        shouldIntercept,
        ...customOptions,
    };
    const processQueue = (error, token = null) => {
        failedQueue.forEach(prom => {
            if (error) {
                prom.reject(error);
            } else {
                prom.resolve(token);
            }
        });

        failedQueue = [];
    };

    const interceptor = (error) => {
        if (!options.shouldIntercept(error)) {
            return Promise.reject(error);
        }

        if (error.config._retry || error.config._queued) {
            options.destroyTokenData();
            window.location.replace('/');
            return Promise.reject(error);
        }

        const originalRequest = error.config;
        if (isRefreshing) {
            return new Promise(function (resolve, reject) {
                failedQueue.push({resolve, reject})
            }).then(token => {
                originalRequest._queued = true;
                options.attachTokenToRequest(originalRequest, token);
                return axiosClient.request(originalRequest);
            }).catch(err => {
                return Promise.reject(error);
            })
        }

        originalRequest._retry = true;
        isRefreshing = true;
        return new Promise((resolve, reject) => {
            options.handleTokenRefresh.call(options.handleTokenRefresh)
                .then((tokenData) => {
                    options.setTokenData(tokenData, axiosClient);
                    options.attachTokenToRequest(originalRequest, tokenData.token);
                    processQueue(null, tokenData.token);
                    resolve(axiosClient.request(originalRequest));
                })
                .catch((err) => {
                    processQueue(err, null);
                    reject(err);
                })
                .finally(() => {
                    isRefreshing = false;
                })
        });
    };

    axiosClient.interceptors.response.use(undefined, interceptor);
};