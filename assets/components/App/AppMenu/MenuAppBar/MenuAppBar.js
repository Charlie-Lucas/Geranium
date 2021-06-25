import React, {useState} from "react";
import {
    AppBar, Dialog, DialogContent, DialogTitle,
    IconButton,
    Typography
} from "@material-ui/core";
import PlantIcon from "../../../Icons/PlantIcon";
import Toolbar from "@material-ui/core/Toolbar/Toolbar";
import useStyles from "./styles";
import {AccountCircle} from "@material-ui/icons";
import LoginForm from "../../Form/LoginForm";
import ProfileMenu from "../ProfileMenu/ProfileMenu";
import CartMenu from "../CartMenu/CartMenu";

export default function MenuAppBar({currentUser, isLoggedIn, openLogin}) {

    const classes = useStyles();
    const [open, setOpen] = useState(openLogin);

    return (
        <div className={classes.menuAppBar}>
        <AppBar position="relative">
            <Toolbar>
                <IconButton edge="start" className={classes.siteIcon} color="inherit">
                    <PlantIcon/>
                </IconButton>
                <Typography variant="h6" className={classes.title} color="inherit" noWrap >
                    Géranium
                </Typography>
                { isLoggedIn ? (
                    <>
                        <CartMenu/>
                        <ProfileMenu/>
                    </>
                ):
                    <>
                    <IconButton
                        aria-label="Log in user"
                        aria-controls="menu-appbar"
                        aria-haspopup="true"
                        onClick={ (e) => setOpen(true)}
                        color="inherit"
                    >
                        <AccountCircle />
                    </IconButton>
                    <Dialog
                        open={open}
                        onClose={ (e) => setOpen(false)}
                        aria-labelledby="open login dialog"
                        fullWidth
                        maxWidth="sm"
                    >
                        <DialogTitle className={classes.loginFormTittle}>
                           Se connecter
                        </DialogTitle>
                        <DialogContent>
                            <LoginForm currentUser={currentUser}/>
                        </DialogContent>

                    </Dialog >
                    </>
                }
            </Toolbar>
        </AppBar>
        </div>
    );
}