import React from 'react';
import { connect } from 'react-redux';
function Content(props) {
    const { children, withoutSidebar,app } = props;
    let style = {marginLeft:'300px'}
    if (withoutSidebar || !props.app.showSidebar) style = { ...style, marginLeft: 0 }
    return (
        <main style={style}>
            <div className="container">
                <section className="section dashboard min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                    <div className="container" style={{ marginTop: 60, marginBottom: 60 }}>
                        <div className="row justify-content-center">
                            {children}
                        </div>
                    </div>
                </section>
            </div>
        </main>
    )
}
const mapStateToProps = state => ({ app: state.app })
export default connect(mapStateToProps)(Content)