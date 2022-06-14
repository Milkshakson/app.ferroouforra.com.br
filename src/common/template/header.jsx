import React from 'react'
import Visibility from './visibility'

function Header(props) {
    const { isValidTokenAcesso, appName,usuarioTokenAcesso } = props;
    return (
    <header id="header" className="header fixed-top d-flex align-items-center">
	<div className="d-flex align-items-center justify-content-between">
		<a href="/" className="logo d-flex align-items-center">
			<img src="/assets/img/Logo.png"/>  
		</a>
		<i className="bi bi-list toggle-sidebar-btn"></i>
	</div>
		<h5 className="card-title">
				<span>{appName}</span>
		</h5>
		<nav className="header-nav ms-auto">
			<ul className="d-flex align-items-center">
                <Visibility condition={isValidTokenAcesso}>
					<li className="nav-item dropdown">
						<a className="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
							<i className="bi bi-time-left-token bi-clock"></i>
							<span className="badge bg-light badge-number badge-time-left-token"></span>
						</a>
						<ul className="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
							<li className="dropdown-header">
								<span className="badge-time-left-token-expanded"></span>
							</li>
						</ul>
					</li>
				
					<li className="nav-item dropdown pe-3">
						<a className="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
							<i className="bi bi-person"></i>
							<span className="d-none d-md-block dropdown-toggle ps-2">
								{usuarioTokenAcesso.sub }</span>
						</a>
						<ul className="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
							<li className="dropdown-header">
								<h6>
                                {usuarioTokenAcesso.sub }
								</h6>
								<span>{usuarioTokenAcesso.environment }</span>
							</li>
							<li>
								<hr className="dropdown-divider" />
							</li>
							<li>
								<a className="dropdown-item d-flex align-items-center" href="login/logout">
									<i className="bi bi-box-arrow-right"></i>
									<span>Sair</span>
								</a>
							</li>
						</ul>
					</li>
                    </Visibility>
			</ul>
		</nav>
</header>
)
}
export default Header;