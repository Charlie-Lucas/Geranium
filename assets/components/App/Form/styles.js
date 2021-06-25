import { makeStyles } from '@material-ui/core/styles';

export default makeStyles((theme) => ({
    input : {
        textAlign: "center",
        margin: theme.spacing(1),
    },
    submit : {
        margin: theme.spacing(2),
    },
    submitContainer : {
        textAlign: "center"
    },
    loginForm : {
        margin: theme.spacing(2),
    }
}));