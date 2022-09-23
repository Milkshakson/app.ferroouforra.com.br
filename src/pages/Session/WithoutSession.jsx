import React from 'react'
import { useForm } from "react-hook-form";
import { yupResolver } from '@hookform/resolvers/yup';
import * as yup from "yup";
import moment from 'moment';

const schema = yup
    .object({
        descricao: yup.string().required("A descrição é obrigatória"),
        time: yup.string().required("A hora é obrigatória"),
        runando: yup.string().nullable().required("A flag para resultados é obrigatória"),
        data: yup.date()
            .nullable()
            .transform((curr, orig) => orig === '' ? null : curr)
            .required('A data de abertura é obrigatória')
    })
    .required();

const WithoutSession = () => {
    const { register, handleSubmit, formState: { errors } } = useForm({
        resolver: yupResolver(schema)
        , defaultValues: {
            descricao: 'Sessão de '+moment().locale('pt-br').format('dddd')+', '+moment().format('DD/MM/YYYY')
            , data: moment().format('YYYY-MM-DD')
            , time: moment().format('HH:mm')
        }

    });
    const onSubmit = data => console.log(data);

    return (
        <div>
            <div className="card">
            <div className="card-header">
            <div>Não há uma sessão aberta. Preencha os dados para iniciar uma nova.</div>
            </div>
                <div className="card-body">
                    <h5 className="card-title">Dados da abertura</h5>

                    <form onSubmit={handleSubmit(onSubmit)} className="row g-3">
                        <div className="col-md-12">
                            <label htmlFor="descricao" className="form-label">Descrição da sessão</label>
                            <input type="text" autoComplete='off' className="form-control" id="descricao" {...register("descricao", { required: true })} />
                            <p className='text-danger'>{errors.descricao?.message}</p>
                        </div>
                        <div className="col-md-6">
                            <label htmlFor="data" className="form-label">Data de abertura</label>
                            <input type="date" className="form-control" id="data"  {...register("data", { required: true })} />
                            <p className='text-danger'>{errors.data?.message}</p>
                        </div>
                        <div className="col-md-6">
                            <label htmlFor="hora" className="form-label">Hora</label>
                            <input type="time" className="form-control" id="time"   {...register("time", { required: true })} />
                            <p className='text-danger'>{errors.time?.message}</p>
                        </div>
                        <div className="col-12">
                            <div className="form-check">
                                <input className="form-check-input" type="checkbox" name="estudo" id="estudo"   {...register("estudo")} />
                                <label className="form-check-label" htmlFor="estudo">
                                    Estudei antes da sessão
                                </label>
                            </div>
                            <div className="form-check">
                                <input className="form-check-input" type="checkbox" id="preparacaofisica"
                                    {...register("preparacaofisica")} />
                                <label className="form-check-label" htmlFor="preparacaofisica">
                                    Fiz preparação física antes da sessão
                                </label>
                            </div>
                            <div className="form-check">
                                <input className="form-check-input" type="checkbox" id="preparacaomental"
                                    {...register("preparacaomental")} />
                                <label className="form-check-label" htmlFor="preparacaomental">
                                    Fiz preparação mental antes da sessão
                                </label>
                            </div>
                            <hr className='divider' />
                            <fieldset className="row mb-3">
                                <legend className="col-form-label col-sm-2 pt-0">Resultados</legend>
                                <div className="col-sm-10">
                                    <div className="form-check">
                                        <input className="form-check-input" type="radio" name="runando" id="gridRadios1"
                                            value='downswing'
                                            {...register("runando")} />
                                        <label className="form-check-label" htmlFor="gridRadios1">
                                            Estou em downswing
                                        </label>
                                    </div>
                                    <div className="form-check">
                                        <input className="form-check-input" type="radio" name="runando" id="gridRadios2"
                                           value='upswing'
                                           {...register("runando")} />
                                        <label className="form-check-label" htmlFor="gridRadios2">
                                            Estou em upswing
                                        </label>
                                    </div>
                                    <div className="form-check disabled">
                                        <input className="form-check-input" type="radio" name="runando" id="gridRadios3"
                                            value='breakeven'
                                            {...register("runando")} />
                                        <label className="form-check-label" htmlFor="gridRadios3">
                                            Não estou downswing nem em upswing
                                        </label>
                                    </div>
                                    <p className='text-danger'>{errors.runando?.message}</p>
                                </div>
                            </fieldset>
                        </div>
                        <div className="d-flex d-flex justify-content-center gap-2">
                            <button type="submit" className="btn btn-primary">Salvar</button>
                            <button type="reset" className="btn btn-secondary">Limpar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    )
}

export default WithoutSession