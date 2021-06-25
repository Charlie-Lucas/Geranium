import React from "react";
import {Backdrop, CircularProgress} from "@material-ui/core";
import useStyles from "./styles";

export default function Loading() {
    const classes = useStyles();
    return (
        <Backdrop className={classes.backdrop} open={true}>
            <CircularProgress color="inherit" />
        </Backdrop>
    );
}