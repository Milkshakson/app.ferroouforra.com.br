import React from 'react';
import { connect } from 'react-redux';
function ContentMain(props) {
    const { children, withoutSidebar, app } = props;
    let style = { marginLeft: '300px', height: '99%', marginTop: 60, marginBottom: 65 ,boxSizing: 'border-box'}
    if (withoutSidebar || !props.app.showSidebar) style = { ...style, marginLeft: 0 }
    style = { ...style, ...props.style }
    return (
        <main style={style} >
            <div className='container' style={{height:'99%',boxSizing: 'border-box'}}>
                {children}
            </div>
        </main>
    )
}
const mapStateToProps = state => ({ app: state.app })
export default connect(mapStateToProps)(ContentMain)

