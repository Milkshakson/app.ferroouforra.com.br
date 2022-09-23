import { isExpired, decodeToken } from "react-jwt";
const storedToken = localStorage.getItem("tokenJwt") || ''
import {
    LOGOUT,
    LOGIN,
    TOGGLE_SIDEBAR
} from 'store/actions/actionTypes'
const initialState = {
    appName: 'Ferro ou Forra',
    encodedToken: storedToken,
    decodedToken: decodeToken(storedToken),
    isValidToken: !isExpired(storedToken),
    showSidebar: localStorage.getItem('showSidebar') === 'true',
    version:50
}

const appReducer = function (state = initialState, action) {
    switch (action.type) {
        case LOGIN:
            return { ...state, ...action.payload }
        case TOGGLE_SIDEBAR:
            return { ...state, showSidebar: action.payload }
        case LOGOUT:
            return { ...state, ...action.payload }
        default:
            return state
    }
}
export default appReducer