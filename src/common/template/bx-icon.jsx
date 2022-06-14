import React from 'react'

function BxIcon(props){
    return <i class={`bx bx-${props.name||''}`}></i>
}
export default BxIcon