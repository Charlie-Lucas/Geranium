import React, {useCallback, useEffect, useState} from "react";
import {
    Backdrop,
    Box,
    Button, CircularProgress,
    Divider,
    FormControl,
    FormHelperText,
    Grid,
    Input,
    InputLabel,
    Link, TextField,
    Typography
} from "@material-ui/core";
import useStyles from "./styles";
import {useDispatch} from "react-redux";
import {logIn} from "../../../actions/userActions";
import {validateEmail} from "../../../utils/form";
import Loading from "../Loading/Loading";

export default function LoginForm({currentUser}) {
    const classes = useStyles();
    const preventDefault = (event) => event.preventDefault();
    const[email, setEmail] = useState('');
    const[emailError, setEmailError] = useState(false);
    const [password, setPassword] = useState('');
    const [passwordError, setPasswordError] = useState(false);
    const dispatch = useDispatch();
    const {loading}= currentUser;
    const errorLogin = currentUser && currentUser.error != null;

    const submitHandler = (e) => {
        e.preventDefault();
        console.log('here')
        dispatch(logIn(email, password));
    };
    useEffect(() => {
        if (errorLogin) {
            setPassword('');
            setPasswordError("Votre identifiant ou mot de passe est incorrect.")
        }
    }, [setPassword, errorLogin]);

    function onEmailChange(mail) {
        setEmail(mail);
        if(mail !== undefined && mail.length === 0) {
            setEmailError("Veuillez saisir une adresse email.");
        }else if(!validateEmail(mail)) {
            setEmailError("Vérifiez l'adresse email, son format n'est pas valide.");
        } else {
            setEmailError(false);
        }
    }
    function onPasswordChange(password) {
        setPassword(password);
        if(password.length === 0) {
            setPasswordError("Veuillez saisir un mot de passe.");
        } else {
            setPasswordError(false);
        }
    }


    return (
        <>
            {loading && (
                <Loading/>
            )}
        <form onSubmit={submitHandler} className={classes.loginForm}>
            <Grid container direction="row"
                  justify={"center"}>
                <Grid item xs={12} xs={8}>
                    <TextField label="Adresse Email" className={classes.input} id="email" aria-describedby="Champ email"  required fullWidth
                               onClick={ (e) => onEmailChange(e.target.value)} onChange={ (e) => onEmailChange(e.target.value)}
                               error={emailError !== false} helperText={emailError}
                    />
                </Grid>
                <Grid item xs={12} sm={8}>
                    <TextField label="Mot de passe" value={password} className={classes.input} type="password" id="password" aria-describedby="Champ mot de passe" required fullWidth
                               onClick={ (e) => onPasswordChange(e.target.value)} onChange={ (e) => onPasswordChange(e.target.value)}
                               error={passwordError !== false} helperText={passwordError}
                    />
                </Grid>
                <Grid item xs={12} sm={8}>
                    <Link href="#" onClick={preventDefault}>
                        Mot de passe oublié
                    </Link>
                </Grid>
                <Grid item xs={12} sm={8} className={classes.submitContainer}>
                    <Button variant="contained" color="primary" type="submit" className={classes.submit}>
                        Se connecter
                    </Button>
                </Grid>
                <Grid item xs={12} xs={8}>
                    <Divider variant="middle" />
                </Grid>
                <Grid item xs={12} xs={8}>
                    New customer ? <Link href="#">Create yor account</Link>
                </Grid>
            </Grid>
        </form>
        </>
    );
}