import React from 'react'

import SideBar from 'common/template/sideBar'
import Footer from 'common/template/footer'
import Header from 'common/template/header'
export default props => (
    <>
        <Header />
        <SideBar />
        <main id="main" className="main">
            {props.children}
        </main>
        <Footer />
        {/* <Messages /> */}
        </>
)