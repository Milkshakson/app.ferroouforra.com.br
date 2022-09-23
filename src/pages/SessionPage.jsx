import React, { useEffect, useState } from 'react'
import SideBarLeft from 'components/global/SidebarLeft';
import BodyBootstrap from 'components/global/BodyBootstrap';
import Footer from 'components/global/Footer';
import Header from 'components/global/header';
import api from 'main/api';
import { useStore } from 'react-redux';
import ContentMain from 'components/global/ContentMain';
import Visibility from 'components/global/Visibility';
import CircularProgress from '../components/global/CircularProgress';
import WithSession from './Session/withSession';
import WithoutSession from './Session/withoutSession';
const SessionPage = () => {
  const { app } = useStore().getState()
  const [session, setSession] = useState({})
  const [loadingSession, setLoadingSession] = useState(true)
  function getSession() {
    setLoadingSession(true)
    const config = {
      headers: {
        'Content-Type': "application/json",
        'App-Version': app.version,
        'Authorization': 'Bearer ' + app.encodedToken
      }
    }
    api
      .get('/poker_session/current_open',
        config)
      .then((response) => {
        setLoadingSession(false)
        setSession(response.data)
      })
      .catch((err) => {
        setLoadingSession(false)
      });
  }
  useEffect(() => {
    getSession()
  }, [])



  return (
    <BodyBootstrap>
      <Header showLeftMenu />
      <SideBarLeft />
      <ContentMain >

        <div className="pagetitle">
          <h1>Sessão atual</h1>
          <nav>
            <ol className="breadcrumb">
              <li className="breadcrumb-item">Minha Sessão</li>
            </ol>
          </nav>
        </div>


        <section className="section dashboard">
          <div className="d-flex flex-column justify-content-center align-items-center" style={{ height: '100%' }}>
            <Visibility condition={!loadingSession} replacement={<CircularProgress />}>
              <Visibility condition={session !== {} && session.id > 0} replacement={<WithoutSession />}>
                <WithSession session={session} />
              </Visibility>
            </Visibility>
          </div>
        </section>
      </ContentMain>
      <Footer />
    </BodyBootstrap>
  )
}
export default SessionPage