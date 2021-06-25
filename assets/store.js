import {applyMiddleware, combineReducers, compose, createStore} from "redux";
import thunk from "redux-thunk";
import {createBrowserHistory} from "history";
import {connectRouter, routerMiddleware} from "connected-react-router";
import {reducer as form} from "redux-form";
import {signInReducer} from "./reducers/user/userReducer";

const composeEnhancer = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose;
export const history = createBrowserHistory();
const rootReducer = (history) => combineReducers({
    router: connectRouter(history),
    form,
    currentUser: signInReducer,
    /* Add your reducers here */
})
const initialState = {
    currentUser: localStorage.getItem('currentUser')
        ? JSON.parse(localStorage.getItem('currentUser'))
        : {},
};
const store = createStore(
    rootReducer(history),
    initialState,
    composeEnhancer(compose(applyMiddleware(routerMiddleware(history), thunk)))
);

export default store;