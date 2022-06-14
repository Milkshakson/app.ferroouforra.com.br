import React from 'react'
import { Link } from 'react-router-dom'


function MenuItem(props) {
    if (props.type == 'external') {
        return (<li className="nav-item">
            <a className="nav-link" target={props.target} href={props.path}>
                {props.icon}<span>{props.label}</span>
            </a>
        </li>)
    } else {
        return (<li className="nav-item">
            <Link className="nav-link" to={props.path}>
                {props.icon}<span>{props.label}</span>
            </Link>
        </li>)

    }
}
export default MenuItem