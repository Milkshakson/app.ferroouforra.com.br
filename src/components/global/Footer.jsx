import React from "react"
import Visibility from "./Visibility";
import { connect, useStore } from "react-redux";
const Footer = (props) => {
    const {noMargin } = props;
    const store = useStore();
    const {secretariaNome,secretariaSigla} =store.getState().app
    let style = {marginTop: 120, backgroundColor:'#fff'}
    if (noMargin || !props.app.showSidebar) style = {...style,marginLeft:0}
    return (
        <>
            <footer id="footer" style={style} className="footer fixed-bottom">
                <div className="copyright">
                    <Visibility condition={(secretariaNome && secretariaSigla)}>
                        <div>
                        {secretariaNome}
                        -
                        {secretariaSigla}
                        </div>
                    </Visibility>
                </div>
                <div className="copyright text-light">
                    &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
                </div>
                <div className="credits text-light"></div>
            </footer>
            <a href="#" className="back-to-top d-flex align-items-center justify-content-center"><i className="bi bi-arrow-up-short"></i></a>
        </>
    );
}
const mapStateToProps = state => ({ app: state.app })
export default connect(mapStateToProps)(Footer)