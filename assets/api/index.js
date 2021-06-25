import Axios from "axios";
import authHeader from "./auth_header";
import axiosApiInstance from "./AxiosRouter";

const post = (url, data) => axiosApiInstance.post(url, data);
const get = (url) => axiosApiInstance.get(url);
const remove = (url) => axiosApiInstance.delete(url);

/** User **/
export const signIn = (email, password) => Axios.post('/api/login', {email, password});
export const register = (firstname, lastname, email, password, allowExtraEmails) => Axios.post('/api/users', {firstname, lastname, email, password, allowExtraEmails});
export const currentUser = () => get('/api/users/me');
export const deleteUser = (id) => remove(`/api/users/${id}`);
/**
export const fetchProducts = () => Axios.get('/api/products');

export const likePost = (id) => Axios.patch(`${url}/${id}/likePost`);
export const updatePost = (id, updatedPost) => Axios.patch(`${url}/${id}`, updatedPost);
export const deletePost = (id) => Axios.delete(`${url}/${id}`);
 */
