function Visibility(props) {
    const replacementChild = Array.from(props.children).filter(child => {
        return child.type && child.type.name === 'Replacement'
    })[0]

    const ifChildren = Array.from(props.children).filter(child => {
        return child !== replacementChild
    })
    if (props.condition === true) {
        return ifChildren || ''
    } else if(replacementChild) {
        return replacementChild
    }else{
        return false
    }
}
export default Visibility

export const Replacement = props => props.children