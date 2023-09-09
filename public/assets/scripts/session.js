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
  $(document).on('click', '.btn-add-game', (e) => {
    // e.preventDefault()
    // lazyFormRegistration()
  })

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

function lazyFormRegistration() {
  $.ajax({
    url: '/currentSession/lazyFormRegistration',
    beforeSend: () => idForm = loadFormToAdd('...aguarde...', '...'),
    dataType: 'json',
    success: (data) => {
      $('#' + idForm).find('.modal-body').html(data.html)
      $('#' + idForm).find('.modal-title').html(data.title)
    }
  }).fail(() => {
    $('#' + idForm).find('.modal-title').html('Falha')
    $('#' + idForm).find('.modal-body').html('Não foi possível recuperar o formulário.')
  })
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
function loadFormToAdd(content, title) {
  let id = Date.now().toString().replace(/\D/g, "")
  let classModalSize = ''
  let classBgTitle = 'text-center bg-twitch text-light'
  let classTitle = 'text-center'
  let labelClose = 'Sair'
  modalForm = document.createElement('div')
  modalForm.classList.add('modal-container')
  modalForm.innerHTML = `
      <div class="modal  modal-form fade" id="${id}" tabindex="-1" data-bs-backdrop="false">
        <div class="modal-dialog modal-dialog-centered ${classModalSize}">
          <div class="modal-content">
            <div class="modal-header ${classBgTitle}">
              <h5 class="modal-title ${classTitle}">${title}</h5>
              <button type="button" class="btn-close btn-light" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              ${content}
            </div>
            <div class="modal-footer bg-light">
              <button type="button" class="btn btn-dark modal-success-btn" data-bs-dismiss="modal">${labelClose}</button>
            </div>
          </div>
        </div>
      </div>
    `

  modalForm.querySelector('.modal-success-btn').onclick = () => { modalForm.remove }

  document.body.append(modalForm);

  var modal = new bootstrap.Modal(modalForm.querySelector('.modal'));
  modal.show();
  return id;
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
