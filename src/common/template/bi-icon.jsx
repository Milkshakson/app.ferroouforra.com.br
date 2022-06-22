import React from 'react'

function BiIcon(props){
    return <i className={`bi bi-${props.name||''}`}></i>
}
export default BiIcon