import {
    CURRENT_USER_FAIL,
    CURRENT_USER_REQUEST,
    CURRENT_USER_SUCCESS,
    USER_SIGNIN_FAIL,
    USER_SIGNIN_REQUEST,
    USER_SIGNIN_SUCCESS, USER_TOKEN_FAIL
} from "../../constantes/userConstants";

export const signInReducer = (state = {}, action) => {
    switch (action.type) {
        case USER_SIGNIN_REQUEST:
            return {loading: true};
        case USER_SIGNIN_SUCCESS:
            return {loading: false, ...action.payload};
        case CURRENT_USER_FAIL:
        case USER_TOKEN_FAIL:
            return {loading: false, error: action.payload}
        default:
            return state;
    }
}