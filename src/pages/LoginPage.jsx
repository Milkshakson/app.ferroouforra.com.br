import React, { useEffect, useState } from 'react'
import api from '../main/api'
import { useStore } from 'react-redux'
import { Navigate, useNavigate } from 'react-router-dom'
import { toast } from 'react-toastify'
import { connect } from 'react-redux'
import { bindActionCreators } from 'redux'

import { login } from 'store/actions/appActions'
const LoginPage = function (props) {
    const store = useStore()
    const { appName } = store.getState().app
    const storedEmail = localStorage.getItem("emailLogin")
    const storedSenha = localStorage.getItem("senhalogin")
    const [email, setEmail] = useState(storedEmail || '')
    const [senha, setSenha] = useState(storedSenha || '')
    const [redirect, setRedirect] = useState(false)
    useEffect(() => {
        if (redirect) {
            return <Navigate to="/" replace />
        }
    }, [redirect])
    useEffect(() => {
        localStorage.setItem("emailLogin", email)
        localStorage.setItem("senhalogin", senha)
    }, [email, senha])

    function changeEmail(email) {
        setEmail(email)
    }
    function submitLogin(e) {
        e.preventDefault();

        const config = {
            headers: {
                'Content-Type': "application/json",
                'App-Version': "50"
            }
        }
        api
            .post("/login", {
                email, senha
            },
                config)
            .then((response) => {
                localStorage.setItem('tokenJwt', response.data.idToken)
                props.login(response.data.idToken)
                toast.success("Login efetuado com sucesso!",{autoClose:3500})
                setRedirect(true)
            })
            .catch((err) => {
                toast.error("ops! ocorreu um erro" + err)
            });
    }
    if (redirect) {
        return <Navigate to="/" replace />
    }
    return (
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
                                <div className="pt-4 pb-2">
                                    <h5 className="card-title text-center pb-0 fs-4">Login</h5>
                                    <p className="text-center small">Informe email e senha para acessar!</p>
                                </div>

                                <form onSubmit={e => submitLogin(e)} className="row g-3 needs-validation" method="post" noValidate>

                                    <div className="col-12">
                                        <label htmlFor="usuario" className="form-label">E-mail</label>
                                        <div className="input-group has-validation">
                                            <span className="input-group-text" id="inputGroupPrependEmail">@</span>
                                            <input type="text" onChange={e => changeEmail(e.target.value)} className="form-control" id="email"
                                                value={email} autoComplete="off" required />
                                            <div className="invalid-feedback">Por favor, informe seu email!</div>
                                        </div>
                                    </div>

                                    <div className="col-12">
                                        <label htmlFor="senha" className="form-label">Senha</label>
                                        <div className="input-group has-validation">
                                            <span className="input-group-text" id="inputGroupPrependsenha"><i className='bi bi-key'></i></span>
                                            <input type="password" onChange={e => setSenha(e.target.value)} className="form-control" id="senha"
                                                value={senha} autoComplete="off" required />
                                            <div className="invalid-feedback">Por favor, informe sua senha!</div>
                                        </div>
                                    </div>
                                    <div className="col-12">
                                        <button className="btn btn-primary w-100" type="submit">Login</button>
                                    </div> <div className="col-12">
                                        <p className="small mb-0">Esqueceu a senha? <a href="/registration/password-recovery">Recupere aqui</a></p>
                                    </div>
                                    <div className="col-12">
                                        <p className="small mb-0">Ainda não tem conta? <a href="/registration/new">Cadastre-se</a></p>
                                    </div>
                                    <div className="col-12">
                                        <p className="small mb-0">Precisa confirmar o email? <a href="/registration/email-confirmation-resend">Solicite o reenvio.</a></p>
                                    </div>
                                    <div className="col-12">
                                        <p className="small mb-0">Recebeu um token para confirmação? <a href="/registration/email-confirm">Use aqui.</a></p>
                                    </div>
                                </form>

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
const mapDispatchToProps = dispatch => bindActionCreators({ login }, dispatch)
export default connect(mapStateToProps, mapDispatchToProps)(LoginPage)