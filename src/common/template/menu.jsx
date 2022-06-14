import React from 'react'
import BiIcon from './bi-icon'
import BxIcon from './bx-icon'
import MenuItem from './menuItem'
import NavHeading from './nav-heading'
import Visibility from './visibility'

export default props => (
    <ul className="sidebar-nav" id="sidebar-nav">
        <NavHeading>Geral</NavHeading>
        <MenuItem path='/' label='Home' icon={<BiIcon name="home" />} />
        <Visibility condition={true}>
            <MenuItem path='/session/current' label='Minha Sessão' icon={<BiIcon name="currency-dollar" />} />
            <MenuItem path='/dashboard/yearly' label='Resumo anual' icon={<BiIcon name="calendar" />} />
            <MenuItem path='/dashboard/monthly' label='Resumo mensal' icon={<BiIcon name="calendar" />} />
            <MenuItem path='/login/logout' label='Sair' icon={<BxIcon name="exit" />} />
        </Visibility>
        <Visibility condition={false}>
            <MenuItem path='/login/index' label='Login' icon={<BiIcon name="key" />} />
            <MenuItem path='/registration/new' label='Cadastre-se' icon={<BxIcon name="registered" />} />
        </Visibility>
        <NavHeading>Externos</NavHeading>
        <MenuItem path='https://twitch.tv/milkshakson' type="external" target="_blank" label='Twitch do Milk' icon={<BiIcon name="twitch" />} />
    </ul>
)