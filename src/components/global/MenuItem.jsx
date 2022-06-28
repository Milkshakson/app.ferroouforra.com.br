import React from 'react'
import { Link } from 'react-router-dom'


const MenuItem = function (props) {
    const {selected} = props
    if (props.type == 'external') {
        return (<li className="nav-item">
            <Link className={"nav-link "+ (selected?'':'collapsed')} to={{ pathname: props.path, target: props.target }}>
                {props.children}
            </Link>
        </li>)
    } else {
        return (<li className="nav-item">
            <Link className={"nav-link "+(selected?'':'collapsed')} to={props.path}>
                {props.children}
            </Link>
        </li>)

    }
}
export default MenuItem