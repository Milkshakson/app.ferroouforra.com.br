import React, { Component, useEffect, useRef, useState } from 'react';
import BarChart from '../../common/chart/BarChart';
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
    const [year, setYear] = useState(new Date().getFullYear())
    const [loadingSumary, setLoadingSumary] = useState(true)
    const isScreenMounted = useRef(true)
    
    useEffect(() => {
        return () =>  isScreenMounted.current = false
    },[])

    function getSumary() {
        if(!isScreenMounted.current) return;
        setLoadingSumary(true)
        const config = {
            headers: {
                'Content-Type': "application/json",
                'App-Version': "50",
                'Authorization': 'Bearer ' + encodedToken
            }
        }
        api
            .get(`/poker_session/resumo_anual_v2/?year=${year}`,
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
    }, [year])

    useEffect(() => {
        const reverseSumary = sumary.reverse()
        setUserData({
            labels: reverseSumary.map((data) => data.mesBuyin),
            datasets: [
                {
                    label: "Lucro",
                    data: reverseSumary.map((data) => data.profit),
                    backgroundColor: reverseSumary.map((data) => (parseFloat(data.profit) > 0) ? 'green' : 'red'),
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
                <div className='container-fluid d-flex justify-content-between' style={{ margin: 0, padding: '10px 0' }}>
                    <button style={{ cursor: 'pointer' }} className="btn btn-primary" onClick={() => setYear(year - 1)}>Anterior</button>
                    <button onClick={() => getSumary()} className="btn btn-primary">Ano: {year} Atualizar</button>
                    <button style={{ cursor: 'pointer' }} className="btn btn-primary" onClick={() => setYear(year + 1)}>Seguinte</button>
                </div>
                <div className='row d-flex align-items-center justify-content-center'>
                    <div style={{ width: '50%' }}><BarChart title='Movimentação anual' chartData={userData} /></div>
                    <ResumoAnual sumary={sumary} />
                </div>
            </> : <div style={{ display: 'flex', flexGrow: '1', flexDirection: 'column' }} className='d-flex flex-column align-items-center justify-content-center py-4'>
                <CircularProgress />
            </div>
    );
}

export default GraficoLucro;