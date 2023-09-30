var timeToReload = timeToReload = 60 * 1000
const intervalreload = setInterval(() => {
  reloadBuyInsOpen($('.container-buyins-opened'))
  reloadSummaryOpen($('.container-summary-opened'))
}, timeToReload)

$(document).ready(() => {
  reloadSummaryOpen($('.container-summary-opened'))
  reloadBuyInsOpen($('.container-buyins-opened'))
  lazyLoadGrade($('.container-grade'));
  lazyLoadColaboracao($('.container-colaboracao'));
  $(document).on('click', '.btn-add-game', (e) => {
    e.preventDefault()
    lazyFormRegistration()
  })
  $(document).on('click', '.btn-edit-buy-in', function (e) {
    e.preventDefault()
    const idBuyIn = $(this).data('codigo');
    lazyFormRegistration(idBuyIn);
  })
  $(document).on('click', '.btn-edit-staking', function (e) {
    e.preventDefault()
    const idBuyIn = $(this).data('codigo');
    lazyFormStaking(idBuyIn);
  })

  $(document).on('submit', '[name=form-end-buy-in]', function (e) {
    const url = `/session/endBuyIn`;
    e.preventDefault();
    const modal = $("#buyInModal");
    $.ajax({
      url,
      beforeSend: () => waitingDialog.show('Aguarde..'),
      dataType: 'json',
      method: 'post',
      data: $(this).serialize(),
      success: response => {
        if (response.success) {
          reloadBuyInsOpen($('.container-buyins-opened'));
          modal.modal('hide');
          successAlert(response.message);
        } else {
          errorAlert(response.message);
        }
      }
    })
      .fail(() => errorAlert('Falha na requisição'))
      .always(waitingDialog.hide())
  })

  $(document).on('click', '.btn-end-buyin', function (e) {
    const idBuyIn = $(this).data('codigo');
    const url = `/session/endBuyIn/${idBuyIn}`;
    e.preventDefault();
    const modal = $("#buyInModal");
    $.ajax({
      url,
      beforeSend: () => waitingDialog.show('Aguarde..'),
      dataType: 'json',
      success: response => {
        if (response.success) {
          modal.find('.modal-body').html(response.html);
          modal.modal('show');
        } else {
          errorAlert(response.message);
        }
      }
    })
      .fail(() => errorAlert('Falha na requisição'))
      .always(waitingDialog.hide())
  })

  $(document).on('click', '.btn-remove-buyin', function (e) {
    var sender = $(this)
    e.preventDefault()
    ifConfirm('Tem certeza que deseja remover este registro?', (confirmed) => {
      if (confirmed) {
        $.ajax({
          url: sender.attr('href'),
          dataType: 'json',
          beforeSend: () => waitingDialog.show('Aguarde...'),
          success: (response) => {
            if (response.success) {
              setTimeout(
                reloadBuyInsOpen($('.container-buyins-opened')), 200);
              successAlert(response.message);
            } else {
              errorAlert(response.message)
            }
          }
        })
          .fail(() => errorAlert('Falha na requisição'))
          .always(() => waitingDialog.hide())
      }
    })
  });

  $(document).on('submit', '[name=formSalvaBuyIn]', function (e) {
    e.preventDefault();
    const sender = $(this);
    $.ajax({
      method: 'post',
      data: sender.serialize(),
      beforeSend: () => waitingDialog.show('Aguarde enquanto o buy-in é salvo'),
      dataType: 'json',
      url: '/session/saveBuyIn',
      success: (response) => {
        if (response.success) {
          $("#buyInModal").modal('hide');
          reloadBuyInsOpen($('.container-buyins-opened'));
          successAlert('Buy-in salvo com sucesso');
        } else {
          errorAlert(response.message)
        }
      }
    }).fail(() => errorAlert('Falha na requisição')).always(() => waitingDialog.hide());
  });

  $(document).on('submit', '[name=form-save-staking]', function (e) {
    e.preventDefault();
    $.ajax({
      url: '/session/stakingBuyIn',
      data: $(this).serialize(),
      dataType: 'json',
      method: 'post',
      beforeSend: () => waitingDialog.show('Aguarde...'),
      success: (response) => {
        if (response.success) {
          const modal = $('#buyInModal');
          modal.modal('hide');
          reloadBuyInsOpen($('.container-buyins-opened'));
          successAlert(response.message);
        } else {
          errorAlert(response.message);
        }
      }
    }).fail(() => errorAlert('Falha ao salvar.'))
      .always(() => waitingDialog.hide())
  })


  $(document).on('change', '[name=stakingSelling]', function (e) {
    const stakingSoldElement = $('[name=stakingSold]');
    if (stakingSoldElement.val() == '') {
      stakingSoldElement.val($(this).val());
    }
  });

  $(document).on('submit', '#form-encerramento-sessao', function (e) {
    e.preventDefault();
    const sender = $(this);
    ifConfirm('Tem certeza que deseja encerrar a sessão? <br/>Você já confirmou os saldos do bankroll?', (confirmed) => {
      if (confirmed) {
        $.ajax({
          method: 'post',
          data: sender.serialize(),
          beforeSend: () => waitingDialog.show('Aguarde enquanto a sessão é encerrada'),
          dataType: 'json',
          url: sender.attr('action'),
          success: (response) => {
            if (response.success) {
              successAlert('Sessão encerrada com sucesso.', () => {
                waitingDialog.show('Aguarde enquanto recarregamos');
                location.href = response.redirectTo;
              });
            } else {
              errorAlert(response.message);
            }
          }
        }).fail(() => errorAlert('Falha na requisição')).always(() => waitingDialog.hide());
      }
    })

  });
  $(document).on('submit', '[name=form-donation]', function (e) {
    e.preventDefault();
    const data = $(this).serialize();
    const mensagemSelecionada = selecionarMensagemAleatoria();
    $.ajax({
      url: '/donation/create',
      method: 'post',
      data,
      dataType: 'json',
      beforeSend: () => waitingDialog.show(mensagemSelecionada),
      success: (response) => {
        if (response.success) {
          lazyLoadColaboracao($('.container-colaboracao'), response.txId);
        } else {
          errorAlert(response.message);
        }
      }
    })
      .always(() => waitingDialog.hide())
      .fail(() => errorAlert('Falha na requisição'));
  })
})

const mensagensEngracadas = [
  "Aguardando com tanta ansiedade que o relógio está com ciúmes.",
  "Nosso sistema está trabalhando duro para te atender, mas não está suando, prometemos!",
  "Aqui estamos, aguardando como se fosse a hora do almoço em um dia de trabalho.",
  "Estamos contando os segundos, mas não vamos contar para ninguém!",
  "Enquanto isso, nossos servidores estão fazendo uma pausa para o café.",
  "Aguardando... e esperando... e aguardando... até que você volte!",
  "Nosso programa está pensando em piadas para te entreter enquanto aguarda.",
  "Se fosse possível, nosso sistema estaria roendo as unhas agora.",
  "Aguardando pacientemente como um flamingo em uma perna só.",
  "Aguardando com a mesma ansiedade de uma criança na véspera de Natal.",
  "Nosso sistema está mais ansioso para te atender do que um cachorro esperando por um passeio.",
  "Aguardando com a graça de um pinguim deslizando pelo gelo.",
  "Enquanto você espera, nossos servidores estão fazendo uma dança da chuva virtual.",
  "Aguardando com a tranquilidade de uma tartaruga em um dia ensolarado.",
  "Nosso sistema está pensando em charadas para te divertir enquanto espera.",
  "Aguardando... e esperando... e aguardando... até que você retorne!",
  "Se nosso sistema pudesse fazer um chá, ele já teria preparado uma xícara.",
  "Aguardando com a mesma empolgação de um fã em um show de rock.",
];

// Função para selecionar uma mensagem aleatória do array
function selecionarMensagemAleatoria() {
  const indiceAleatorio = Math.floor(Math.random() * mensagensEngracadas.length);
  return mensagensEngracadas[indiceAleatorio];
}

function reloadBuyInsOpen(target) {
  $.ajax({
    url: '/currentSession/lazyLoadBuyInList',
    dataType: 'json',
    success: (json) => {
      target.html(json.html);
      setTimeout(reloadSummaryOpen($('.container-summary-opened')), 200)
      updateAlertaMaxLateIcons();
    }
  })
    .fail(() => target.html('Não foi possível recuperar a lista de buy-ins.'))
}
function clonaResumoItens(selector = ".grid-summary-current-session > div:lt(4)") {
  var $divsToClone = $(selector);

  // Clone os divs selecionados
  var $clonedDivs = $divsToClone.clone();

  // Adicione os divs clonados ao elemento com a classe 'other-element'
  $(".clone-resumo").html($clonedDivs);
}
function reloadSummaryOpen(target) {
  $.ajax({
    url: '/currentSession/lazyLoadSummary',
    dataType: 'json',
    success: (json) => {
      target.html(json.html);
      setTimeout(clonaResumoItens(), 200);
    }
  }).fail(() => target.html('Não foi possível recuperar o resumo da sessão.'))
}

function lazyFormRegistration(idBuyIN) {
  const modal = $('#buyInModal');
  let url = '/currentSession/lazyFormRegistration/'
  if (idBuyIN != undefined) {
    url += idBuyIN
  }
  $.ajax({
    url,
    beforeSend: () => waitingDialog.show('Aguarde...'),
    dataType: 'json',
    success: (response) => {
      if (response.success) {
        modal.find('.modal-body').html(response.html)
        modal.modal('show');
      } else {
        errorAlert(response.message)
      }
    }
  })
    .fail(() => {
      errorAlert('Houve uma falha ao abrir o formulário.')
    })
    .always(() => waitingDialog.hide())
}

function confirmarPagamento(txId) {
  var url = `/donation/confirmar/${txId}`;
  $.ajax({
    url,
    dataType: 'json',
    beforeSend: () => waitingDialog.show('Aguarde...'),
    success: response => {
      if (response.success) {
        lazyLoadColaboracao($('.container-colaboracao'), txId);
      } else {
        errorAlert(response.message);
      }
    }
  })
    .always(() => waitingDialog.hide())
    .fail(() => errorAlert('Falha na requisição'));
}

function lazyLoadColaboracao(target, txId) {
  var url = '/donation/';
  if (txId != undefined)
    url += txId;
  $.ajax({
    url,
    dataType: 'json',
    beforeSend: () => target.html(spinnerWaiting),
    success: response => {
      if (response.success) {
        target.html(response.html);
        $('textarea').each(function () {
          $(this).val($(this).val().trim());
        }
        );
      } else {
        errorAlert(response.message);
      }
    }
  }).fail(() => target.html('Falha na requisição'));
}
function lazyFormStaking(idBuyIN) {
  const modal = $('#buyInModal');
  let url = `/session/stakingBuyIn/${idBuyIN}`
  $.ajax({
    url,
    beforeSend: () => waitingDialog.show('Aguarde...'),
    dataType: 'json',
    success: (response) => {
      if (response.success) {
        modal.find('.modal-body').html(response.html)
        $('.percent-dot').mask('##0.00', {
          reverse: true
        });
        $('.markup').mask('?.??', {
          reverse: true,
          translation: {
            '?': {
              pattern: /[0-9]+([\.][0-9]{0,2})?/,
              optional: true
            }
          }
        });

        modal.modal('show');
      } else {
        errorAlert(response.message)
      }
    }
  })
    .fail(() => {
      errorAlert('Houve uma falha ao abrir o formulário.')
    })
    .always(() => waitingDialog.hide())
}

function loadMyBuyIns() {
  const formFilter = $("form[name='formFilterMyBuyIns']")
  $.ajax({
    method: 'post',
    beforeSend: () => $('.content-my-buyins').html('...aguarde...'),
    data: formFilter.serialize(),
    url: '/session/lazyLoadMyBuyIns',
    success: (data) => $('.content-my-buyins').html(data)
  })
}
// Função para verificar e vocalizar os elementos que começarão em 1 minuto ou menos
function vocalizeUpcomingElements() {
  // Selecione todos os elementos com a classe 'start-vocalize'
  $('.start-vocalize').each(function () {
    var codigoJogo = $(this).data('codigo-jogo');
    var alertaMaxLateAtivo = localStorage.getItem('alerta-max-late-' + codigoJogo) === 'true';

    if (alertaMaxLateAtivo) {
      var $element = $(this);
      var startTime = $element.data('start');
      var currentTime = moment(); // Obtenha o momento atual
      // Calcule a diferença de tempo em minutos
      var minutesDiff = moment(startTime).diff(currentTime, 'minutes');

      // Verifique se a diferença de tempo é menor ou igual a 1 minuto
      if (minutesDiff > 4 && minutesDiff <= 5) {
        vocalizeElement($element);
      } else
        if (minutesDiff > 0 && minutesDiff <= 1) {
          // Vocalize o elemento
          vocalizeElement($element);
        }
    }
  });

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
// Função para vocalizar um elemento
function vocalizeElement($element) {
  // Aqui você pode implementar a lógica para vocalizar o elemento
  // Por exemplo, você pode usar Text-to-Speech (TTS) API ou reproduzir um som de alerta

  // Exemplo simples: Mostrar um alerta
  vocalizarTexto('Alerta para registro tardio: ' + $element.text());
}

// Chame a função para verificar e vocalizar elementos a cada minuto
setInterval(vocalizeUpcomingElements, 30000); // A cada minuto (60.000 milissegundos)


function vocalizarTexto(texto) {
  if ('speechSynthesis' in window) {
    const utterance = new SpeechSynthesisUtterance(texto);
    speechSynthesis.speak(utterance);
  }
}

// Quando você clica no botão de liga/desliga
$(document).on('click', '.btn-toggle-alerta-max-late', function () {
  const $this = $(this);
  const codigoJogo = $this.closest('.start-vocalize').data('codigo-jogo');

  // Verifique se o botão está pressionado (ativo) ou não
  const alertaMaxLateAtivo = $this.attr('aria-pressed') === 'false';

  // Alterne o estado do botão
  $this.attr('aria-pressed', alertaMaxLateAtivo ? 'true' : 'false');

  // Armazene o estado no localStorage (opcional)
  localStorage.setItem('alerta-max-late-' + codigoJogo, alertaMaxLateAtivo ? 'true' : 'false');

  // Adicione ou remova a classe text-success com base no estado
  $this.toggleClass('text-success', alertaMaxLateAtivo);
});

function updateAlertaMaxLateIcons() {
  $('.start-vocalize').each(function () {
    var codigoJogo = $(this).data('codigo-jogo');
    var alertaMaxLateAtivo = localStorage.getItem('alerta-max-late-' + codigoJogo) === 'true';
    // Adicione a classe de acordo com o estado do alerta
    var $button = $(this).find('.btn-toggle-alerta-max-late');
    $button.attr('aria-pressed', alertaMaxLateAtivo);
    $button.toggleClass('text-success', alertaMaxLateAtivo);
  });
}

