import React, { Component } from "react"
class BodyLogin extends Component {
    render() {
        const { children,className,id,withNavbar,withoutSidebar } = this.props;
        let style = {}
        if (withNavbar) style = {...style,marginTop:60}
        if (withoutSidebar) style = {...style,marginLeft:0}
        return (
                <main className={className} id={id} style={style}>
                  <div className="container">
                      <section className="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                        <div className="container">
                            {children}
                        </div>
                      </section>
                  </div>
                </main>
        )
    }
}
// Decorator
export default BodyLogin