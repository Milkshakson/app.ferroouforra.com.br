import React from "react";
import { useStore } from "react-redux";
import { Navigate } from 'react-router-dom'
const ProtectedRoute = ({ user, children }) => {

    const { isValidToken} = useStore().getState().app
    if (!isValidToken) {
        return <Navigate to="/login" replace />;
    }

    return children;
};

export default ProtectedRoute