import React from 'react'
function Visibility(props){
    if(props.condition) {
        return props.children
    }else{
        return false;
    }
}
export default Visibility