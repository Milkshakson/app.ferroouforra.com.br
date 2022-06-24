import React from 'react';

function ResumoAnual(props) {
    const { sumary } = props
    const classTextProfit = ''
    const linhas = sumary.map(mes => {
        /*
        if (mes['profit'] > 0)
            let classTextProfit = 'text-success'
        else if (mes['profit'] < 0)
        let classTextProfit = 'text-danger'
        else
        let classTextProfit = 'text-light'
*/
        return (<tr className={classTextProfit} key={mes.mesBuyin}>
            <td>{mes.mesBuyin}</td>
            <td>{mes['countBuyIns']}</td>
            <td>{mes['totalBuyIn']}</td>
            <td>{mes['stakingReturn']}</td>
            <td>{mes['totalPrize']}</td>
            <td>{mes['profit']}</td>
        </tr>
        )
    })
    return (
        <table className='table'>
            <thead>
                <tr>
                    <th>Mês</th>
                    <th>Contagem de buy ins</th>
                    <th>Total de buy ins</th>
                    <th>Retorno de cotas</th>
                    <th>Total de premiação</th>
                    <th>Total de Lucro</th>
                </tr>
            </thead>
            <tbody>
                {linhas}
            </tbody>
        </table>
    );
}

export default ResumoAnual;