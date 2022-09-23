import React from 'react';
import { dolarFormat } from '../utils/moneyUtils';

function ResumoAnual(props) {
    const { sumary } = props
    const linhas = sumary.map(mes => {
        let classTextProfit = ''
        if (mes['profit'] > 0)
            classTextProfit = 'text-success'
        else if (mes['profit'] < 0)
            classTextProfit = 'text-danger'
        else
            classTextProfit = 'text-light'

        return (<tr className={classTextProfit} key={mes.mesBuyin}>
            <td>{mes.mesBuyin}</td>
            <td>{mes['countBuyIns']}</td>
            <td>{dolarFormat(mes['totalBuyIn'])}</td>
            <td>{dolarFormat(mes['stakingReturn'])}</td>
            <td>{dolarFormat(mes['totalPrize'])}</td>
            <td>{dolarFormat(mes['profit'])}</td>
        </tr>
        )
    })
    return (
        <table className='table'>
            <thead>
                <tr>
                    <th colSpan={6}>
                        Lucro do ano: {dolarFormat(sumary.reduce((total, mes) => {
                            return total + parseFloat(mes['profit'])
                        }, 0))}
                    </th>
                </tr>
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