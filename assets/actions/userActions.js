import * as api from '../api/index.js';

import {
    CURRENT_USER_FAIL,
    CURRENT_USER_REQUEST,
    CURRENT_USER_SUCCESS, USER_REGISTER_FAIL, USER_REGISTER_REQUEST, USER_REGISTER_SUCCESS,
    USER_SIGNIN_REQUEST, USER_SIGNIN_SUCCESS, USER_TOKEN_FAIL, USER_TOKEN_REQUEST, USER_TOKEN_SUCCESS
} from "../constantes/userConstants";


export function requestToken() {
    return { type: USER_TOKEN_REQUEST} ;
}
export function successToken(retrieved) {
    return { type: USER_TOKEN_SUCCESS, payload:retrieved };
}
export function errorToken(error) {
    return { type: USER_TOKEN_FAIL, payload:error };
}

export function requestCurrentUser() {
    return { type: CURRENT_USER_REQUEST} ;
}
export function successCurrentUser(retrieved) {
    return { type: CURRENT_USER_SUCCESS, payload:retrieved };
}
export function errorCurrentUser(error) {
    return { type: CURRENT_USER_FAIL, payload:error };
}

export function requestSignin() {
    return { type: USER_SIGNIN_REQUEST} ;
}
export function successSignin(retrieved) {
    return { type: USER_SIGNIN_SUCCESS, payload:retrieved };
}

export function requestRegister() {
    return { type: USER_REGISTER_REQUEST} ;
}
export function successRegister(registered) {
    return { type: USER_REGISTER_SUCCESS, payload:registered };
}
export function errorRegister(error) {
    return { type: USER_REGISTER_FAIL, payload:error };
}

export const me = () => async (dispatch) => {
    dispatch(requestCurrentUser());
    try {
        const { data } = await api.currentUser();
        dispatch(successCurrentUser);
        localStorage.setItem('currentUser', JSON.stringify(data));
        dispatch(successSignin(data))
    } catch (error) {
        dispatch(errorCurrentUser(error));
    }
};
export const logIn = (email, password) => async (dispatch) => {
    dispatch(requestSignin());
    dispatch(requestToken());
    try {
        await api.signIn(email, password).then(({data}) => {

            dispatch(successToken(data));
            localStorage.setItem('token', JSON.stringify(data.token));
            localStorage.setItem('refresh_token', JSON.stringify(data.refresh_token));
        }).then(() => {
            dispatch(me());
        })
    } catch (error) {
         dispatch(errorToken(error))
    }
};

export const register = (firstname, lastname, email, password, allowExtraEmails) => async(dispatch) => {

    dispatch(requestRegister());
    try {
        const {data} = await api.register(firstname, lastname, email, password, allowExtraEmails)
        dispatch(successRegister(data));
        console.log(data)
        //dispatch(logIn(email, password));
    } catch (error) {
        dispatch(errorRegister(error))
    }
}

export const logout = () => {
    localStorage.removeItem('token');
    localStorage.removeItem('refresh_token');
    localStorage.removeItem('currentUser');
    window.location.replace('/')
}
export const deleteUser = (id) => async(dispatch) => {
    await api.deleteUser(id);
}
