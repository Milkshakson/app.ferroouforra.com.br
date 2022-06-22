function Visibility(props) {
    const { replacement, children } = props
    if (props.condition === true) {
        return children
    } else if (replacement) {
        return replacement
    } else {
        return false
    }
}
export default Visibility