import React from 'react'
import { useState } from 'react'

const CircularProgress = (props) => {
    const [count, setCount] = useState(0)
    return (
        <div className="d-flex justify-content-center align-items-center p-3" style={{ height: "100%" }}>
            <div className="spinner-border text-success" onClick={() => {
                setCount(count + 1)
            }} style={count >= 10 ? { zIndex: 9999, fontSize: '50px' } : {}} role="status">
                <span className={count < 10 ? "visually-hidden" : ''}>Aguarde...</span>
            </div>
        </div>
    )
}
export default CircularProgress