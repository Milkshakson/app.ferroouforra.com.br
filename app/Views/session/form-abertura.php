<?php if(isset($variaveis))extract($variaveis);?>
<div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Dados da abertura</h5>

                        <!-- Multi Columns Form -->
                        <form method="post" class="row g-3">
                            <div class="col-md-12">
                                <label for="descricao" class="form-label">Descrição da sessão</label>
                                <input type="text" name="descricao" class="form-control" id="descricao" value="<?= set_value('descricao') ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="data" class="form-label">Data de abertura</label>
                                <input type="date" name="data" class="form-control" id="data" value="<?= set_value('data') ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="hora" class="form-label">Hora</label>
                                <input type="time" name="hora" class="form-control" id="time" value="<?= set_value('hora') ?>">
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="estudo" id="estudo" <?= set_value('estudo') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="gridCheck">
                                        Estudei antes da sessão
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="preparacaofisica" id="preparacaofisica" <?= set_value('preparacaofisica') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="preparacaofisica">
                                        Fiz preparação física antes da sessão
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="preparacaomental" id="preparacaomental" <?= set_value('preparacaomental') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="preparacaomental">
                                        fiz preparação mental antes da sessão
                                    </label>
                                </div>
                                <hr class='divider' />
                                <fieldset class="row mb-3">
                                    <legend class="col-form-label col-sm-2 pt-0">Resultados</legend>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="runando" id="gridRadios1" value="downswing" <?= (set_value('runando') == 'downswing') ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="gridRadios1">
                                                Estou em downswing
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="runando" id="gridRadios2" value="upswing" <?= (set_value('runando') == 'upswing') ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="gridRadios2">
                                                Estou em upswing
                                            </label>
                                        </div>
                                        <div class="form-check disabled">
                                            <input class="form-check-input" type="radio" name="runando" id="gridRadios3" value="breakeven" <?= (set_value('runando') == 'breakeven') ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="gridRadios3">
                                                Não estou downswing nem em upswing
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Salvar</button>
                                <button type="reset" class="btn btn-secondary">Limpar</button>
                            </div>
                        </form><!-- End Multi Columns Form -->

                    </div>
                </div>