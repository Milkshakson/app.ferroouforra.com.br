import React, { Component } from "react"
class ContentLogin extends Component {
    render() {
        const { children } = this.props;
        return (
            <main>
                <div className="container">
                    <section className="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                        <div className="container">
                            <div className="row justify-content-center">
                                <div className="col-lg-6 col-md-6 d-flex flex-column align-items-center justify-content-center">
                                    {children}
                                    <div className="credits text-light">
                                        Designed by <a className="text-light" href="https://bootstrapmade.com/">BootstrapMade</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
        )
    }
}
// Decorator
export default ContentLogin