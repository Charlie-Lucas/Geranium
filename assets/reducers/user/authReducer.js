import {USER_SIGNIN_FAIL, USER_SIGNIN_REQUEST, USER_SIGNIN_SUCCESS} from "../../constantes/userConstants";

export const authReducer = (state = {}, action) => {
    switch (action.type) {
        case USER_SIGNIN_REQUEST:
            return {loading: true};
        case USER_SIGNIN_SUCCESS:
            return {loading: true, auth : action.payload};
        case USER_SIGNIN_FAIL:
            return {loading: true, error: action.payload}
        default:
            return state;
    }
}
 export function refreshReducer(state = {}, action = {}) {
    switch (action.type) {
        case 'SET_TOKENS':
            return {
                ...state,
                auth: { tokens: action.payload },
            };
        default:
            return state;
    }
}