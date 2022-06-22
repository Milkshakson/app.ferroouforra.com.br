import { isExpired, decodeToken } from "react-jwt";
const storedToken = localStorage.getItem("tokenJwt") || ''
import {
    LOGOUT,
    LOGIN
} from 'store/actions/actionTypes'
const initialState = {
    appName: 'Ferro ou Forra',
    decodedToken: decodeToken(storedToken),
    isValidToken: !isExpired(storedToken),
}

const appReducer = function (state = initialState, action) {
    switch (action.type) {
        case LOGIN:
            return { ...state, ...action.payload }
        case LOGOUT:
            return { ...state, ...action.payload }
        default:
            return state
    }
}
export default appReducer