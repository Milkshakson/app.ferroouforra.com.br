import React from 'react'
import GraficoLucro from '../components/grafico/GraficoLucro'
import SideBarLeft from 'components/global/SidebarLeft';
import BodyBootstrap from 'components/global/BodyBootstrap';
import Content from 'components/global/Content';
import Footer from 'components/global/Footer';
import Header from 'components/global/header';
import CountdownTimer from 'components/global/CountdownTimer';
import { useStore } from 'react-redux';
import moment from 'moment'
import ContentMain from '../components/global/ContentMain';
const IndexPage = () => {
    const { app } = useStore().getState()
    const { exp } = app.decodedToken
    return (
        <BodyBootstrap>
            <Header showLeftMenu />
            <SideBarLeft />
            <ContentMain >
              <div className="d-flex flex-column align-items-center justify-content-center p-6"  style={{height:'100%'}}>
                <GraficoLucro />
              </div>
            </ContentMain>
            <Footer />
        </BodyBootstrap>
    )
}
export default IndexPage