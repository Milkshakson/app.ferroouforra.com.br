import React from 'react'

import SideBar from 'common/template/sideBar'
import Footer from 'common/template/footer'
import Header from 'common/template/header'
import GraficoLucro from '../components/grafico/GraficoLucro'
export default props => (
    <>
        <Header />
        <SideBar />
        <main id="main" className="main" style={{minHeight:'100vh'}}>
          <GraficoLucro />
        </main>
        <Footer />
        {/* <Messages /> */}
        </>
)