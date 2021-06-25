import React, {useEffect, useState} from "react";
import {Button, TextField} from "@material-ui/core";
import {useDispatch} from "react-redux";
import {testAction} from "../actions/testActions";
import {deleteUser} from "../actions/userActions";

export default function HomeScreen() {
    const dispatch = useDispatch()
    const [deleteId, setDeleteId] = useState(0);
    const test = () => {
       // dispatch(testAction())
    }
    const handleSubmit = () => {
        dispatch(deleteUser(deleteId))
    }
    return (
        <>
            <form onSubmit={(e) => handleSubmit()}>
                <TextField
                    id="standard-number"
                    label="Number"
                    type="number"
                    InputLabelProps={{
                        shrink: true,
                    }}
                    onChange={(e) => setDeleteId(e.target.value)}
                />
                <Button
                    fullWidth
                    type={"submit"}
                    variant="contained"
                    color="primary"
                    onClick={(e) => test()}
                >
                    Supprimer
                </Button>
            </form>
        </>
    )
}
        