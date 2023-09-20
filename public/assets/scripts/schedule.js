function openScheduleTournamentsModal(idGrade) {
    // Defina o ID da grade no modal, se necessário
    // Suponha que haja um campo no modal com o ID 'gradeId'
    const modal = $('#scheduleTournamentsModal');
    const target = modal.find('.modal-body');
    $.ajax({
        url: '/grade/loadTournaments/' + idGrade,
        beforeSend: function () {
            target.html('Aguarde...');
            modal.modal('show');
        },
        dataType: 'json',
        success: (json) => {
            target.html(json.html);
        }
    }).fail(() => target.html('Bad beat, não foi possível concluir a transação...'))
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

function openScheduleEditModal(idGrade) {
    // Defina o ID da grade no modal, se necessário
    // Suponha que haja um campo no modal com o ID 'gradeId'
    const modal = $('#scheduleCrudModal');
    const target = modal.find('.modal-body');
    $.ajax({
        url: '/grade/salvar/' + idGrade,
        beforeSend: function () {
            target.html('');
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
            target.html('Aguarde...')
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
                url: `/grade/removerTorneio/${id}`,
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
                beforeSend: () => waitingDialog.show('Aguarde a remoção'),
                success: (response) => {
                    if (response.success) {
                        formRegistro.remove();
                    } else {
                        errorAlert(response.message);
                    }
                }
            }).fail(() => errorAlert('Falha ao remover.')).always(() => waitingDialog.hide())
        }
    });
}


$(document).ready(() => {
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