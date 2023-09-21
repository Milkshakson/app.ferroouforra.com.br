function openScheduleTournamentsModal(idGrade) {
    // Defina o ID da grade no modal, se necessário
    // Suponha que haja um campo no modal com o ID 'gradeId'
    const modal = $('#scheduleTournamentsModal');
    const target = modal.find('.modal-body');
    $.ajax({
        url: '/grade/loadTournaments/' + idGrade,
        beforeSend: function () {
            waitingDialog.show(spinnerWaiting);
        },
        dataType: 'json',
        success: (json) => {
            target.html(json.html);
            modal.modal('show');
        }
    })
        .fail(() => target.html('Bad beat, não foi possível concluir a transação...'))
        .always(() => waitingDialog.hide())
}

function removerGrade(idGrade) {
    ifConfirm('<div>Tem certeza que deseja remover esta grade e todos os seus torneios?</div>' +
        "<br/>" +
        "<div class='w-100 alert alert-danger gap-1'>" +
        "<i class='bi bi-exclamation-triangle'></i>" +
        "<strong >Esta ação não poderá ser desfeita.</strong>" +
        "</div>", (confirmed) => {
            if (confirmed) {
                $.ajax({
                    url: '/grade/remover/' + idGrade,
                    beforeSend: function () {
                        waitingDialog.show('Aguarde...')
                    },
                    dataType: 'json',
                    success: (response) => {
                        if (response.success) {
                            $(`#grade_${idGrade}`).remove();
                            successAlert('Grade removida com sucesso');
                        } else {
                            errorAlert(response.message);
                        }
                    }
                })
                    .always(() => waitingDialog.hide())
                    .fail(() => errorAlert('Bad beat, não foi possível concluir a transação...'))
            }
        })
}


function openModalAddTournamentSchedule(idGrade) {
    $.ajax({
        url: '/grade/addTournament/' + idGrade,
        dataType: 'json',
        beforeSend: () => waitingDialog.show(spinnerWaiting),
        success: (response) => {
            if (response.success) {
                const modal = $('#addTorneioGradeModal');
                modal.find('.modal-body').html(response.html);
                modal.modal('show');
            } else {
                errorAlert(response.message);
            }
        }
    })
        .fail(() => errorAlert('Falha ao carregar o formulário'))
        .always(() => waitingDialog.hide())
}

function openScheduleEditModal(idGrade) {
    // Defina o ID da grade no modal, se necessário
    // Suponha que haja um campo no modal com o ID 'gradeId'
    const modal = $('#scheduleCrudModal');
    const target = modal.find('.modal-body');
    $.ajax({
        url: '/grade/salvar/' + idGrade,
        beforeSend: function () {
            waitingDialog.show(spinnerWaiting);
        },
        dataType: 'json',
        success: (response) => {
            if (response.success) {
                target.html(response.html);
                $('textarea').each(function () {
                    $(this).val($(this).val().trim());
                }
                );
                modal.modal('show');
            } else
                errorAlert(response.message)
        }
    })
        .always(() => waitingDialog.hide())
        .fail(() => target.html('Bad beat, não foi possível concluir a transação...'))
}

function openScheduleCreateModal() {
    openScheduleEditModal(null)
}

function lazyLoadGrade(target) {
    $.ajax({
        url: '/grade/lazyLoad',
        beforeSend: function () {
            target.html(
                `<div class="d-flex justify-content-center align-items-center p-5 w-100 h-100">
                <div class="spinner-grow" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>`
            )
        },
        dataType: 'json',
        success: (json) => {
            target.html(json.html);
        }
    })
        .fail(() => target.html('Não foi possível recuperar a grade.'))
}

function removerDaGrade(id) {
    ifConfirm('Tem certeza que deseja remover o torneio da grade?', (confirmed) => {
        if (confirmed) {
            const formName = `form_${id}`;
            const formRegistro = $('[name=' + formName + ']');
            $.ajax({
                url: `/grade/removeTournament/${id}`,
                dataType: 'json',
                beforeSend: () => waitingDialog.show('Aguarde a remoção'),
                success: (response) => {
                    if (response.success) {
                        formRegistro.remove();
                        lazyLoadGrade($('.container-grade'));
                    } else {
                        errorAlert(response.message);
                    }
                }
            }).fail(() => errorAlert('Falha ao remover.')).always(() => waitingDialog.hide())
        }
    });
}
function registrar(id) {
    ifConfirm('Tem certeza que deseja registrar no torneio?', (confirmed) => {
        const formName = `form_${id}`;
        const formRegistro = $('[name=' + formName + ']');
        const data = formRegistro.serialize();
        if (confirmed) {
            $.ajax({
                data,
                method: 'post',
                url: `/grade/registrar`,
                dataType: 'json',
                beforeSend: () => waitingDialog.show(spinnerWaiting),
                success: (response) => {
                    if (response.success) {
                        reloadBuyInsOpen($('.container-buyins-opened'));
                        successAlert(response.message);
                    } else {
                        errorAlert(response.message);
                    }
                }
            }).fail(() => errorAlert('Falha ao registrar.')).always(() => waitingDialog.hide())
        }
    });
}


$(document).ready(() => {
    $(document).on('submit', '[name=form_add_tournament]', function (e) {
        e.preventDefault();
        const data = $(this).serialize();
        $.ajax({
            url: '/grade/addTournament',
            method: 'post',
            dataType: 'json',
            data,
            beforeSend: () => waitingDialog.show('Aguarde...'),
            success: (response) => {
                if (response.success) {
                    openScheduleTournamentsModal(response.idGrade);
                    successAlert(response.message, () => {
                        const modal = $('#addTorneioGradeModal');
                        modal.modal('hide');
                    });
                } else {
                    errorAlert(response.message);
                }
            }
        })
            .fail(() => errorAlert('Falha ao salvar o torneio'))
            .always(() => waitingDialog.hide())
    });

    $(document).on('submit', "[name=cadastroGrade]", function (e) {
        e.preventDefault();
        const data = $(this).serialize();
        $.ajax({
            data,
            method: 'post',
            url: `/grade/salvar`,
            dataType: 'json',
            beforeSend: () => waitingDialog.show('Aguarde, salvando a grade'),
            success: (response) => {
                if (response.success) {
                    successAlert('Grade salva com sucesso', () => {
                        lazyLoadGrade($('.container-grade'));
                        $('#scheduleCrudModal').modal('hide');
                    })
                } else {
                    errorAlert(response.message);
                }
            }
        }).fail(() => errorAlert('Falha ao salvar.')).always(() => waitingDialog.hide())
    });

    $(document).on("input", "[name=cadastroGrade] input,textarea", function () {
        var $input = $(this);
        var charCountElement = $input.next('.form-text').find(".char-count");
        var maxLength = $input.attr("maxlength");

        if (maxLength !== undefined) {
            var charCount = $input.val().length;
            var charsRemaining = maxLength - charCount;
            charCountElement.text(charsRemaining + " caracteres restantes");
        }
    });
});