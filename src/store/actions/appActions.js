import {
    LOGOUT,
    LOGIN,
    TOGGLE_SIDEBAR
} from 'store/actions/actionTypes'
import { isExpired, decodeToken } from "react-jwt";
// Action Creator
export function login(token) {
    return {
        type: LOGIN,
        payload: {
            decodedToken: decodeToken(token),
            encodedToken: token,
            isValidToken: !isExpired(token)
        }
    }
}

export function toggleSidebar(showSidebar) {
    localStorage.setItem('showSidebar', showSidebar)
    return {
        type: TOGGLE_SIDEBAR,
        payload: showSidebar
    }
}
// Action Creator
export function logout() {
    localStorage.removeItem('tokenJwt')
    return {
        type: LOGOUT,
        payload: {
            decodedToken: {},
            isValidToken: false,
            encodedToken: ''
        }
    }
}