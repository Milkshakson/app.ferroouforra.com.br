import React from 'react'
import style from './css/campoSenha.module.css';
const CampoSenha = (props) => {
    const { name, className, id, required, value, onChange } = props
    const classSenha = style.inputSenha
    const url = window.location.href
    if (url.startsWith('https://'))
        return <input type="password" value={value} onChange={(e)=>{onChange(e.target.value)}} className={className} id={id} required={required ? true : ''} />
    else
        return <input type="text" value={value} onChange={(e)=>{onChange(e.target.value)}}  autoComplete="off" className={className+' '+classSenha} id={id} required={required ? true : ''} />
}

export default CampoSenha