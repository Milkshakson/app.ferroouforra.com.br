import React from 'react';
import ReactDOM from 'react-dom';

const funciona = 'Sim, funciona. Isso é react. Compilando real.Será?';

ReactDOM.render(
  <h2>{funciona}</h2>,
  document.querySelector('#app')
)

console.log('Funcionou tudo junto, viva!')