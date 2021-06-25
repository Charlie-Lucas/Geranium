import React, {useEffect, useState} from "react";
import {
    Avatar, Button,
    Checkbox,
    Container,
    CssBaseline,
    FormControlLabel,
    Grid, Link, Paper,
    TextField,
    Typography
} from "@material-ui/core";
import useStyles from "./styles";
import {validateEmail} from "../../utils/form";
import {useDispatch} from "react-redux";
import {register} from "../../actions/userActions";

function LockOutlinedIcon() {
    return null;
}

export default function RegisterScreen({openLogin, setOpenLogin}) {
    const classes = useStyles();
    const [login, setLogin] = useState(false);
    const [firstname, setFirstname] = useState('');
    const [firstnameError, setFirstnameError] = useState('');
    const [lastname, setLastname] = useState('');
    const [lastnameError, setLastnameError] = useState('');
    const [email, setEmail] = useState('');
    const [emailError, setEmailError] = useState(false);
    const [password, setPassword] = useState('');
    const [passwordError, setPasswordError] = useState(false);
    const [allowExtraEmails, setAllowExtraEmails] = useState(false);
    const dispatch = useDispatch();


    useEffect(() => {
        if(login) setOpenLogin(true)
    }, [setOpenLogin, login])

    const handleSubmit = (e) => {
        e.preventDefault();
        dispatch(register(firstname, lastname, email, password, allowExtraEmails))
    }

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
    function onFirstnameChange(firstname) {
        setFirstname(firstname);
        if(firstname.length === 0) {
            setFirstnameError("Veuillez saisir un prénom.");
        } else {
            setFirstnameError(false);
        }
    }
    function onLastnameChange(lastname) {
        setLastname(lastname);
        if(lastname.length === 0) {
            setLastnameError("Veuillez saisir un nom.");
        } else {
            setLastnameError(false);
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
            <Paper className={classes.paper}>
                <Container>
                <Avatar className={classes.avatar}>
                    <LockOutlinedIcon />
                </Avatar>
                <Typography component="h1" variant="h5">
                    Rejoindre le marché
                </Typography>
                <form className={classes.form} noValidate onSubmit={handleSubmit}>
                    <Grid container spacing={2}>
                        <Grid item xs={12} sm={6}>
                            <TextField
                                name="firstName"
                                variant="outlined"
                                required
                                fullWidth
                                id="firstName"
                                label="Prénom"
                                onClick={ (e) => onFirstnameChange(e.target.value)} onChange={ (e) => onFirstnameChange(e.target.value)}
                                error={firstnameError !== false} helperText={firstnameError}
                                autoFocus
                            />
                        </Grid>
                        <Grid item xs={12} sm={6}>
                            <TextField
                                variant="outlined"
                                required
                                fullWidth
                                id="lastName"
                                label="Nom"
                                name="lastName"
                                onClick={ (e) => onLastnameChange(e.target.value)} onChange={ (e) => onLastnameChange(e.target.value)}
                                error={lastnameError !== false} helperText={lastnameError}
                            />
                        </Grid>
                        <Grid item xs={12}>
                            <TextField
                                variant="outlined"
                                required
                                fullWidth
                                id="email"
                                label="Adresse email"
                                name="email"
                                autoComplete="email"
                                onClick={ (e) => onEmailChange(e.target.value)} onChange={ (e) => onEmailChange(e.target.value)}
                                error={emailError !== false} helperText={emailError}
                            />
                        </Grid>
                        <Grid item xs={12}>
                            <TextField
                                variant="outlined"
                                required
                                fullWidth
                                name="password"
                                label="Mot de passe"
                                type="password"
                                id="password"
                                onChange={(e) =>setPassword(e.target.value)}
                                error={passwordError !== false} helperText={passwordError}
                            />
                        </Grid>
                        <Grid item xs={12}>
                            <FormControlLabel
                                control={<Checkbox value="allowExtraEmails" color="primary"
                                   onChange={(e) =>setAllowExtraEmails(e.target.value)}
                                />}
                                label="M'inscrire aux nouvelles du marché"
                            />
                        </Grid>
                    </Grid>
                    <Button
                        type="submit"
                        fullWidth
                        variant="contained"
                        color="primary"
                        className={classes.submit}
                    >
                        M'enregistrer
                    </Button>
                    <Grid container justify="flex-end">
                        <Grid item>
                            Déjà un compte?
                            <Link href="#" variant="body2" onClick={(e) => {setLogin(true)}}>
                                Me connecter
                            </Link>
                        </Grid>
                    </Grid>
                </form>
                </Container>
            </Paper>
    )
}
