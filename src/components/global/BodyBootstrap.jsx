import React, { Component } from "react"
class BodyBootstrap extends Component {
    render() {
        const { children } = this.props;
        return (
            <div style={{
                display: 'flex',
                flexDirection: 'column',
                height: '100vh'
            }}>
                {children}
            </div>
        )
    }
}
//Decorator
export default BodyBootstrap