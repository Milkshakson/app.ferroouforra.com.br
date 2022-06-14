import React from 'react'

import SideBar from '../common/template/sideBar'
import Footer from '../common/template/footer'
import Messages from '../common/msg/messages'
import Header from '../common/template/header'

export default props => (
    <>
        <Header isValidTokenAcesso={0} appName={'Meu nome'} usuarioTokenAcesso ={{
            sub:'ual',
            environment: 'production'
        }} />
        <SideBar />
        <main id="main" class="main">
            {props.children}
        </main>
        <Footer />
        {/* <Messages /> */}
        </>
)