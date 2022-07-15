export function dolarFormat(valor){
return parseFloat(valor).toLocaleString('en-us',{style: 'currency', currency: 'USD'})
}