import {
    LOGOUT,
    LOGIN
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