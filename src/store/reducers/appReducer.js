import { isExpired, decodeToken } from "react-jwt";
const storedToken = localStorage.getItem("tokenJwt") || ''
const initialState = {
    appName: 'Ferro ou Forra',
    decodedToken: decodeToken(storedToken),
    isValidToken: !isExpired(storedToken),
}

const appReducer = function(state = initialState, action) {
    switch(action.type) {   
        default:
            return state
    }
}
export default appReducer