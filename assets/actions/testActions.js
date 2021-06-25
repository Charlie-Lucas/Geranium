import * as api from "../api";

export const testAction = () => async (dispatch) => {
    await api.currentUser().then((data) =>
        console.log(data)
    ).catch((e)=> {
        console.log(e)
    });
}
