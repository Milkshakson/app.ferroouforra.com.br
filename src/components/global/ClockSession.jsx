// @flow
import * as React from 'react';
import { useStore } from 'react-redux';
import moment from 'moment'
import { useEffect, useState } from 'react';
import { getRemainingTimeUntilMsTimestamp } from 'utils/dateUtil';


const defaultRemainingTime = {
    seconds: '00',
    minutes: '00',
    hours: '00',
    days: '00'
}
const ClockSession = (props) => {
    const [remainingTime, setRemainingTime] = useState(defaultRemainingTime);
    const { app } = useStore().getState()
    const { exp, iat } = app.decodedToken
    const [outputCountDown, setOutputCountDown] = useState('')
    // const expira = moment.unix(exp, 'DD/MM/YYYY HH:mm:ss');
    const expira = moment.unix(exp);
    const inicio = moment.unix(iat, 'DD/MM/YYYY HH:mm:ss');
    const [classExpira, setClassExpira] = useState('text-default')
    const [textExpira, setTextExpira] = useState('')
    useEffect(() => {
        const intervalId = setInterval(() => {
            updateRemainingTime()
        }, 1000);
        return () => clearInterval(intervalId)

    }, []);

    function updateRemainingTime() {
        if (app.isValidToken) {
            setRemainingTime(getRemainingTimeUntilMsTimestamp(expira));
        } else {
            setRemainingTime(defaultRemainingTime);
        }
    }
    useEffect(() => {
        const { days, minutes, hours, seconds } = remainingTime
        if (days == 0 && hours == 0 && minutes < 0) {
            setClassExpira('text-danger')
            setTextExpira(`Sua sessão expirou em ${expira.format('DD/MM/YYYY HH:mm:ss')}`)
        } else {
            if (days == 0 && hours == 0 && minutes <= 0)
                setClassExpira('text-danger')
            else if (days == 0 && hours == 0 && minutes < 5)
                setClassExpira('text-warning')
            else if (days == 0 && hours == 0 && minutes < 30)
                setClassExpira('text-info')
            else
                setClassExpira('text-success')
            /**/

            var output = hours.toString().padStart(2, '0') + 'h' + ':' + minutes.toString().padStart(2, '0') + 'm' + (days == 0 && hours == 0 && minutes <= 10 ? ':' + seconds.toString().padStart(2, '0') + 's' : '');
            setOutputCountDown(output)
            setTextExpira(`Sua sessão expira em ${output}`)
        }

    }, [remainingTime])
    return (
        <li className="nav-item dropdown">
            <a className="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                <i className="bi bi-time-left-token bi-clock"></i>
                <span className={"badge bg-light badge-number " + classExpira}>
                    {outputCountDown}
                </span>
            </a>
            <ul className="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">

                {(remainingTime.minutes <= 0) ? <li className="dropdown-header text-start">
                    <span className={classExpira}>Esta sessão iniciou em {inicio.format('DD/MM/YYYY HH:mm')} e encerrou em {expira.format('DD/MM/YYYY HH:mm')}</span>
                </li>
                    : <>
                        <li className="dropdown-header text-start">
                            <span className={classExpira}>{textExpira}</span>
                        </li>
                        <li className="dropdown-header text-start">
                            <span className={classExpira}>Esta sessão iniciou em {inicio.format('DD/MM/YYYY HH:mm')} e encerrará em {expira.format('DD/MM/YYYY HH:mm')}</span>
                        </li></>
                }
            </ul>
        </li>
    );
}

export default ClockSession