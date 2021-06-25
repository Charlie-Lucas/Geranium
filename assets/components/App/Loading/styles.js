import { makeStyles } from '@material-ui/core/styles';

export default makeStyles((theme) => ({
    backdrop: {
        zIndex: theme.zIndex.modal + 1,
        color: '#fff',
    },
}));
