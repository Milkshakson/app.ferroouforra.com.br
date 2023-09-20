var timeToReload = timeToReload = 60 * 1000
const intervalreload = setInterval(() => {
  reloadBuyInsOpen($('.container-buyins-opened'))
  reloadSummaryOpen($('.container-summary-opened'))
}, timeToReload)



$(window).on('load', function () {
  // loadMyBuyIns()
});


$(document).ready(() => {
  reloadSummaryOpen($('.container-summary-opened'))
  reloadBuyInsOpen($('.container-buyins-opened'))
  lazyLoadGrade($('.container-grade'))
  $(document).on('click', '.btn-add-game', (e) => {
    e.preventDefault()
    lazyFormRegistration()
  })
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

  $(document).on('click', '.btn-remove-buyin', function (e) {
    var sender = $(this)
    if (!sender.hasClass('confirmed')) {
      e.preventDefault()
      ifConfirm('Tem certeza que deseja remover este registro?', (confirmed) => {
        if (confirmed) {
          sender.addClass('confirmed')
          location.href = sender.attr('href')
        }
      })
    }
  });

})

function reloadBuyInsOpen(target) {
  $.ajax({
    url: '/currentSession/lazyLoadBuyInList',
    dataType: 'json',
    success: (json) => {
      target.html(json.html);
      updateAlertaMaxLateIcons();
    }
  })
    .fail(() => target.html('Não foi possível recuperar a lista de buy-ins.'))
}

function reloadSummaryOpen(target) {
  $.ajax({
    url: '/currentSession/lazyLoadSummary',
    dataType: 'json',
    success: (json) => {
      target.html(json.html);
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

// Chame a função para carregar o estado do alerta ao carregar a página


window.addEventListener('beforeunload', function (e) {
  // Personalize a mensagem de confirmação
  var confirmationMessage = 'Tem certeza que deseja sair desta página? Se voc~e tiver algum timer nesta página ele não exibirá o alerta.';

  // Define a mensagem de confirmação na janela do navegador
  e.returnValue = confirmationMessage;

  // Retorne a mensagem de confirmação (opcional, pois a maioria dos navegadores ignora isso)
  return confirmationMessage;
});
