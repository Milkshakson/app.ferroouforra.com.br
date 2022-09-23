import React from 'react'
import BiIcon from 'components/global/BiIcon'
import BxIcon from 'components/global/BxIcon'
import MenuItem from 'components/global/MenuItem'
import NavHeading from 'components/global/NavHeading'
import Visibility from 'components/global/Visibility'
import { useStore } from 'react-redux'

export default props => {
const store = useStore()
const {app} = store.getState()
    return (
        <ul className="sidebar-nav" id="sidebar-nav">
            <NavHeading>Geral</NavHeading>
            <MenuItem path='/' ><BiIcon name="home" />Home</MenuItem>
            <Visibility condition={app.isValidToken} replacement={
                <>
                    <MenuItem path='/login/index'><BiIcon name="key" />Login</MenuItem>
                    <MenuItem path='/registration/new'><BxIcon name="registered" />Cadastre-se</MenuItem>
                </>
            }>
                <MenuItem path='/session/current' ><BiIcon name="currency-dollar" />Minha Sessão</MenuItem>
                <MenuItem path='/dashboard/yearly'><BiIcon name="calendar" />Resumo anual</MenuItem>
                <MenuItem path='/dashboard/monthly'><BiIcon name="calendar" />Resumo mensal</MenuItem>
                <MenuItem path='/login/logout'><BxIcon name="exit" />Sair</MenuItem>
            </Visibility>
            <NavHeading>Externos</NavHeading>
            <MenuItem path='https://twitch.tv/milkshakson' type="external" target="_blank" label='Twitch do Milk' icon={<BiIcon name="twitch" />} />
        </ul>
    )
}