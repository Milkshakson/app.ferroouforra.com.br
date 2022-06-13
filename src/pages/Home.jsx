import React from 'react';
import BodyBootstrap from '../components/global/body-bootstrap.jsx';
import NavBarTop from '../components/global/navbar-top.jsx';
import Content from '../components/global/content.jsx';
import ListaModulos from '../components/moduloHome/listaModulos.jsx';

const modulos = [{ name: 'Módulo 1' }, { name: 'Módulo 2' }];
export default () => (
	<BodyBootstrap children={
		[<NavBarTop />,
		<Content children={[
			<div>Entendeu?</div>,
			<ListaModulos modulos={modulos}></ListaModulos>
		]} />]
	} />

)