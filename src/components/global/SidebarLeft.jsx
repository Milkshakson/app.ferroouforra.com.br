import React from 'react'
import Visibility from './Visibility'
import { connect } from "react-redux";
import LeftMenu from './LeftMenu';

const SideBarLeft = (props) => {
    const { app } = props
    const { showSidebar } = app
    return (
        <Visibility condition={showSidebar===true}>
            <aside id="sidebar" className="sidebar">
                <LeftMenu />
            </aside>
        </Visibility>
    )
}
const mapStateToProps = state => ({ app: state.app })
export default connect(mapStateToProps)(SideBarLeft)