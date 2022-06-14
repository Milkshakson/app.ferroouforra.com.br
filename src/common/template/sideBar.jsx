import React from 'react'
import Menu from './menu'
import Visibility from './visibility'

export default props => (
    <aside id="sidebar" className="sidebar">
            <Menu />
        <Visibility condition={props.mostrarPropaganda}></Visibility>
    </aside>
)