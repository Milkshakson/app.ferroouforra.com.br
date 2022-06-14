import React from 'react'

function BiIcon(props){
    return <i class={`bi bi-${props.name||''}`}></i>
}
export default BiIcon