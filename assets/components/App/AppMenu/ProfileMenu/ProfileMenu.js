import React, {useState} from "react";
import {IconButton, Link, Menu, MenuItem, Typography} from "@material-ui/core";
import {AccountCircle} from "@material-ui/icons";
import {useDispatch} from "react-redux";
import {logout} from "../../../../actions/userActions";

export default function ProfileMenu() {

    const [anchorEl, setAnchorEl] = useState(null);
    const dispatch = useDispatch();
    const handleLogout = () => {
        dispatch(logout());
    }
    return (
        <div>
            <IconButton
                aria-label="account of current user"
                aria-controls="profile-appbar"
                aria-haspopup="true"
                onClick={ (e) => setAnchorEl(e.currentTarget)}
                color="inherit"
            >
                <AccountCircle />
            </IconButton>
            <Menu
                id="profile-appbar"
                anchorEl={anchorEl}
                getContentAnchorEl={null}
                anchorOrigin={{
                    vertical: 'bottom',
                    horizontal: 'right',
                }}
                keepMounted
                transformOrigin={{
                    vertical: 'top',
                    horizontal: 'right',
                }}
                open={Boolean(anchorEl)}
                onClose={ (e) => setAnchorEl(null)}
            >
                <MenuItem onClick={ (e) => setAnchorEl(null)}>Profile</MenuItem>
                <MenuItem onClick={ (e) => handleLogout()}>Se déconnecter</MenuItem>
            </Menu>
        </div>
    );
}