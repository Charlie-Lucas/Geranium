import React, {useEffect, useState} from 'react';
import {Backdrop, CircularProgress, Container, CssBaseline, Typography} from "@material-ui/core";
import useStyles from './styles';
import Copyright from './components/App/Copyright/Copyright';
import {
    ConnectedRouter,
} from 'connected-react-router';
import {Route, Switch} from "react-router-dom";
import HomeScreen from "./screens/HomeScreen";
import MenuAppBar from "./components/App/AppMenu/MenuAppBar/MenuAppBar";
import {useSelector} from "react-redux";
import RegisterScreen from "./screens/Register/RegisterScreen";


const App = ({ history }) => {
    const classes = useStyles();
    const currentUser = useSelector((state) => state.currentUser);
    const [openLogin, setOpenLogin] = useState(false);
    const [isLoggedIn, setIsLoggedIn] = useState(false);
    useEffect(() => {
       // console.log('out')
        //console.log(currentUser)
        if(currentUser.id) {
            setIsLoggedIn(true)
          //  console.log('in')
        }
    }, [setIsLoggedIn, currentUser]);


    return (
        <ConnectedRouter history={history}>
            <div className={classes.root}>
            <CssBaseline />
            <MenuAppBar currentUser={currentUser} isLoggedIn={isLoggedIn} openLogin={openLogin}/>
            <Container component="main" className={classes.main} maxWidth="sm">
                <Switch>
                    <Route path="/" component={HomeScreen} strict={true} exact={true}/>
                    {isLoggedIn ? (
                        <></>
                    ) : (
                        <Route path="/register" strict={true} exact={true} render={props => (
                            <RegisterScreen {...props} openLogin={openLogin} setOpenLogin={setOpenLogin} />
                        )} />
                    )}
                    {/* Add your routes here */}
                    <Route render={() => <h1>Not Found</h1>} />
                </Switch>
            </Container>
            <footer className={classes.footer}>
                <Container maxWidth="sm">
                    <Typography vaiant="h6" align="center" gutterBottom>
                    Footer
                    </Typography>
                    <Typography variant="subtitle1" align="center" color="textSecondary" component="p">
                        Something here to give the footer a purpose!
                    </Typography>
                    <Copyright/>
                </Container>
            </footer>
            </div>
        </ConnectedRouter>

    );
}
export default App;
