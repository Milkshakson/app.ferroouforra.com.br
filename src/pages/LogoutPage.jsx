import React, { useEffect, useState } from 'react';

import { toast } from 'react-toastify';
import { useStore } from 'react-redux';
import { Navigate } from 'react-router-dom';
import Visibility from '../common/template/visibility';
import { connect } from 'react-redux'
import { bindActionCreators } from 'redux'

import { logout } from 'store/actions/appActions'
const LogoutPage = function (props) {
    const store = useStore()
    const { appName } = store.getState().app
    const [redirectLogin, setRedirectLogin] = useState(false)
    const [redirectHome, setRedirectHome] = useState(false)
  
    function toHome() {
        toast.sucess("Você optou por continuar logado!")
        setRedirectHome(true)
    }
    function logout() {
        props.logout()
        setRedirectLogin(true)
        toast.warning("Você optou por NÃO continuar logado!")
    }
    return (
        redirectHome?<Navigate to="/" replace />:
        redirectLogin?<Navigate to="/login" replace />:
        <section className="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
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
                                <Visibility condition={!redirectHome && !redirectLogin}>
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

const mapStateToProps = state => ({ app: state.app })
const mapDispatchToProps = dispatch => bindActionCreators({ logout }, dispatch)
export default connect(mapStateToProps, mapDispatchToProps)(LogoutPage)