import {useSelector} from "react-redux";

export default function authHeader() {

    const token = JSON.parse(localStorage.getItem('token'))

    if (token) {
        return {headers: {'Authorization': `Bearer ${token}`} };
    } else {
        return {};
    }
}