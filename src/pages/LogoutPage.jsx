import React, { useEffect, useState } from 'react';
import api from '../main/api'
import { useStore } from 'react-redux';
import { Navigate } from 'react-router-dom';
import Visibility, { Replacement } from '../common/template/visibility';
import { ToastContainer, toast } from 'react-toastify';

import 'react-toastify/dist/ReactToastify.css';
import alert from '../components/Messages';
const LogoutPage = function (props) {
    const store = useStore()
    const { appName } = store.getState().app
    const [redirect, setRedirect] = useState(false)
    const [showMessage, setShowMessage] = useState(false)
    const [message, setMMessage] = useState('')
    const [messageTitle, setMessageTitle] = useState(false)
    if (redirect) {
        return <Navigate to="/" replace />
    }
    function toHome() {
        setRedirect(true)
    }
    function logout() {
        console.log('toast')
        //localStorage.removeItem('tokenJwt')
        toast("Default Notification???? !");
    }
    return (
        <section className="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">

            <ToastContainer
                position="top-right"
                autoClose={50000}
                hideProgressBar={false}
                newestOnTop={false}
                closeOnClick
                rtl={false}
                pauseOnFocusLoss
                draggable
                pauseOnHover
            />
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                        <div className="d-flex justify-content-center py-4">
                            <a href="/" className="logo d-flex align-items-center w-auto">
                                <img src="/assets/img/Logo.png" alt="Logo" />
                                <span className="d-none d-lg-block">APP: {appName}</span>
                            </a>
                        </div>
                        <div className="card mb-3">

                            <div className="card-body">
                                <Visibility condition={!redirect}>
                                    <div className="pt-4 pb-2">
                                        <h5 className="card-title text-center pb-0 fs-4">Logout</h5>
                                        <p className="text-center small">Confirma a saída do sistema?</p>
                                        <div className='row gap-3 p-5'>
                                            <button className="btn btn-danger" onClick={() => logout()}>Sim, quero sair</button>
                                            <button className="btn btn-success" onClick={() => toHome()}>Não, quero continuar</button>
                                        </div>
                                    </div>
                                </Visibility>

                            </div>
                        </div>

                        <div className="credits text-light">
                            Designed by <a className=' text-light' href="https://bootstrapmade.com/">BootstrapMade</a>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    )
}
export default LogoutPage