import { configureStore } from '@reduxjs/toolkit'
import { combineReducers } from 'redux'

import numerosReducer from './reducers/numeros'

const reducer = combineReducers({
    numeros: numerosReducer,
})

function configStore() {
    return configureStore({reducer})
}

export default configStore