import React from 'react'

function BxIcon(props){
    return <i className={`bx bx-${props.name||''}`}></i>
}
export default BxIcon