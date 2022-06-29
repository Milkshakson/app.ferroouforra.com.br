import React, { Component, useEffect, useState } from 'react';
import BarChart from '../../common/chart/BarChart';
import PieChart from '../../common/chart/PieChart';
import LineChart from '../../common/chart/LineChart';
import { toast } from 'react-toastify'
import { useStore } from 'react-redux';
import api from '../../main/api';
import ResumoAnual from '../ResumoAnual';
import CircularProgress from 'components/global/CircularProgress';
import 'react-circular-progressbar/dist/styles.css'

function GraficoLucro() {
    const store = useStore()
    const { encodedToken } = store.getState().app
    const [sumary, setSumary] = useState([])
    const [loadingSumary, setLoadingSumary] = useState(true)

    function getSumary() {
        setLoadingSumary(true)
        const config = {
            headers: {
                'Content-Type': "application/json",
                'App-Version': "50",
                'Authorization': 'Bearer ' + encodedToken
            }
        }
        api
            .get("/poker_session/resumo_anual_v2/?year=2022",
                config)
            .then((response) => {
                setLoadingSumary(false)
                setSumary(response.data)
            })
            .catch((err) => {
                setLoadingSumary(false)
                toast.error("ops! ocorreu um erro!")
            });
    }
    useEffect(() => {
        getSumary()
    }, [])

    useEffect(() => {

        setUserData({
            labels: sumary.reverse().map((data) => data.mesBuyin),
            datasets: [
                {
                    label: "Lucro",
                    data: sumary.map((data) => data.profit),
                    backgroundColor: sumary.reverse().map((data) => (data.profit > 0) ? 'green' : 'red'),
                    borderColor: "black",
                    borderWidth: 2,
                },
            ],
        })
    }, [sumary])
    const [userData, setUserData] = useState();
    return (
        typeof (userData) !== 'undefined' && !loadingSumary ?
            <>
                <div style={{ marginTop: 0, marginLeft: 0, position: 'relative', width: '120px' }} className='row d-flex aalign-items-start justify-content-start'>
                    <button onClick={() => getSumary()} className="btn btn-primary">Atualizar</button>
                </div>
                <div className='row d-flex align-items-center justify-content-center'>
                    <div style={{ width: '33%' }}>
                        <BarChart chartData={userData} /></div>
                    <div style={{ width: '33%' }}><LineChart chartData={userData} /></div>
                    <ResumoAnual sumary={sumary} />
                </div>
            </> : <div style={{display:'flex',flexGrow:'1',flexDirection:'column'}} className='d-flex flex-column align-items-center justify-content-center py-4'>
                <CircularProgress />
            </div>
    );
}

export default GraficoLucro;