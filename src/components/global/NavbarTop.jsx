import React from "react"
import Visibility from "components/global/Visibility"
import SistagLogo from 'views/images/sistag.png'
import LinkBase from "./LinkBase";
import { connect } from "react-redux";
import { bindActionCreators } from 'redux'
import toggleSidebar from "store/actions/appActions";
import { useEffect } from "react";
import { useState } from "react";
const NavBarTop = (props) => {
    const { sistemaSigla, showLeftMenu, app } = props
    const [isVisibleSidebar, setIsVisibleSidebar] = useState(app.showSidebar)
    useEffect(() => {
        setIsVisibleSidebar(app.showSidebar)
    }, [app])
    return (
        <header id="header" className="header fixed-top d-flex align-items-center">
            <div className="d-flex align-items-center justify-content-between">
                <LinkBase href="/" className="logo d-flex align-items-center">
                    <img src={SistagLogo} alt="Logo Sistag" />
                    <span className="d-none d-lg-block">{sistemaSigla}</span>
                </LinkBase>
                <Visibility condition={showLeftMenu}>
                    <i className="bi bi-list toggle-sidebar-btn" onClick={() => {
                        props.toggleSidebar(!isVisibleSidebar)
                    }}></i>
                </Visibility>
            </div>
        </header>
    )
}
const mapStateToProps = state => ({ app:state.app, app:state.app })
const mapDispatchToProps = dispatch => bindActionCreators({ toggleSidebar }, dispatch)
export default connect(mapStateToProps, mapDispatchToProps)(NavBarTop)