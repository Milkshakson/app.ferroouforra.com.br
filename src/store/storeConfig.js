import { configureStore } from '@reduxjs/toolkit'
import { combineReducers } from 'redux'

import appReducer from './reducers/appReducer'

const reducer = combineReducers({
    app: appReducer,
})

function configStore() {
    return configureStore({reducer})
}

export default configStore