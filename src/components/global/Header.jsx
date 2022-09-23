import React from 'react'
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux'
import Visibility from './Visibility'
import { toggleSidebar} from 'store/actions/appActions'
import { useEffect } from "react"
import { useState } from "react"
import Logo from '/public/assets/img/Logo.png'
import MenuItem from './MenuItem';
import ClockSession from './ClockSession';

function Header(props) {
	const { showLeftMenu, app } = props
	const { appName } = app
	const { isValidToken, decodedToken } = app ;
	const [isVisibleSidebar, setIsVisibleSidebar] = useState(app.showSidebar)
	useEffect(() => {
		setIsVisibleSidebar(app.showSidebar)
	}, [app])

	return (
		<header id="header" className="header fixed-top d-flex align-items-center">
			<div className="d-flex align-items-baseline justify-content-start">
				<a href="/" className="logo d-flex align-items-center">
				<img src={Logo}/>  
				</a>

				<Visibility condition={showLeftMenu}>
					<i className="bi bi-list toggle-sidebar-btn" onClick={() => {
						props.toggleSidebar(!isVisibleSidebar)
					}}></i>
				</Visibility>
			</div>
			<h5 className="card-title">
				<span>{appName}</span>
			</h5>
			<nav className="header-nav ms-auto">
				<ul className="d-flex align-items-center">
					<Visibility condition={isValidToken}>
						<ClockSession />

						<li className="nav-item dropdown pe-3">
							<a className="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
								<i className="bi bi-person"></i>
								<span className="d-none d-md-block dropdown-toggle ps-2">
									{decodedToken.sub}</span>
							</a>
							<ul className="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
								<li className="dropdown-header">
									<h6>
										{decodedToken.nomeUsuario}
									</h6>
								</li>
								<MenuItem className="dropdown-item d-flex align-items-center" path='/login/logout'>
									<i className="bi bi-box-arrow-right"></i>
									<span> Sair</span>
								</MenuItem>
							</ul>
						</li>
					</Visibility>
				</ul>
			</nav>
		</header>
	)
}
const mapStateToProps = state => ({ app: state.app})
const mapDispatchToProps = dispatch => bindActionCreators({ toggleSidebar }, dispatch)
export default connect(mapStateToProps, mapDispatchToProps)(Header)