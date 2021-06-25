import React, {useState} from "react";
import {IconButton, Link, Menu, MenuItem, Typography} from "@material-ui/core";
import {AccountCircle, ShoppingBasket} from "@material-ui/icons";
import {useDispatch} from "react-redux";

export default function CartMenu() {

    const [anchorEl, setAnchorEl] = useState(null);
    const dispatch = useDispatch();
    return (
        <div>
            <IconButton
                aria-label="account of current user"
                aria-controls="cart-appbar"
                aria-haspopup="true"
                onClick={ (e) => setAnchorEl(e.currentTarget)}
                color="inherit"
            >
                <ShoppingBasket />
            </IconButton>
            <Menu
                id="cart-appbar"
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
                <MenuItem onClick={ (e) => setAnchorEl(null)}>Créer ma liste de courses</MenuItem>
                <MenuItem onClick={ (e) => setAnchorEl(null)}>Visiter la carte des producteurs</MenuItem>
                <MenuItem onClick={ (e) => setAnchorEl(null)}>Mon panier</MenuItem>
                <MenuItem onClick={ (e) => setAnchorEl(null)}>Mes commandes</MenuItem>
            </Menu>
        </div>
    );
}