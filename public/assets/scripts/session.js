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
  reloadBankroll($('.container-bankroll-opened'))
  $(document).on('click', '.btn-add-game', (e) => {
    // e.preventDefault()
    // lazyFormRegistration()
  })


  $(document).on('keydown', '.table-bankroll .money-dolar', function (e) {
    if (e.keyCode == 13) { // Verifica se a tecla pressionada é "Enter" (código 13)
      e.preventDefault();
      var $this = $(this);
      var $td = $this.closest('td');
      var $nextTd = $td.next('td');

      if ($nextTd.length > 0) {
        // Se existe um próximo campo na mesma linha, dê foco a ele
        $nextTd.find('.money-dolar').focus();
      } else {
        // Se for o último campo da linha, encontre a próxima linha e dê foco ao primeiro campo dela
        var $tr = $td.closest('tr');
        var $nextTr = $tr.next('tr');

        if ($nextTr.length > 0) {
          $nextTr.find('.money-dolar').first().focus();
        }
      }
    }
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

function reloadBankroll(target) {
  $.ajax({
    url: '/currentSession/lazyLoadBankroll',
    dataType: 'json',
    success: (json) => {
      target.html(json.html);

      // Calcular totais iniciais
      calcularTotais();
    }
  })
    .fail(() => target.html('Não foi possível recuperar o bankroll.'))
}
function calcularTotais() {
  var totalSaldoInicial = 0;
  var totalSaldoFinal = 0;

  $('.saldo-inicial').each(function () {
    var valor = parseFloat($(this).val().replace('$', '').replace(',', ''));
    if (!isNaN(valor)) {
      totalSaldoInicial += valor;
    }
  });

  $('.saldo-final').each(function () {
    var valor = parseFloat($(this).val().replace('$', '').replace(',', ''));
    if (!isNaN(valor)) {
      totalSaldoFinal += valor;
    }
  });

  $('#total-saldo-inicial').text('$' + totalSaldoInicial.toFixed(2));
  $('#total-saldo-final').text('$' + totalSaldoFinal.toFixed(2));

  // Verificar se há Lucro ou Prejuízo
  var lucroPrejuizo = totalSaldoFinal - totalSaldoInicial;
  var lucroPrejuizoElement = $('#lucro-prejuizo');
  var valorLucroPrejuizoElement = $('#valor-lucro-prejuizo');

  if (lucroPrejuizo > 0) {
    lucroPrejuizoElement.text('Lucro').removeClass('text-danger').addClass('text-success');
    valorLucroPrejuizoElement.text('$' + lucroPrejuizo.toFixed(2)).removeClass('text-danger').addClass('text-success');
  } else if (lucroPrejuizo < 0) {
    lucroPrejuizoElement.text('Prejuízo').removeClass('text-success').addClass('text-danger');
    valorLucroPrejuizoElement.text('$' + Math.abs(lucroPrejuizo).toFixed(2)).removeClass('text-success').addClass('text-danger');
  } else {
    lucroPrejuizoElement.text('Nenhum').removeClass('text-success text-danger');
    valorLucroPrejuizoElement.text('$0.00').removeClass('text-success text-danger');
  }
}
function salvaBankroll() {
  var dadosBankroll = [];
  $('.saldo-inicial').each(function (e) {
    var index = $('.saldo-inicial').index(this);
    // Verifique se os valores são válidos antes de salvar
    var siteId = $(this).data('site');
    var saldoInicial = $(this).val();
    var saldoFinal = $('.saldo-final').eq(index).val();
    dadosBankroll.push({
      poker_site_id: siteId,
      saldo_abertura: saldoInicial,
      saldo_encerramento: saldoFinal
    });
  });
  $.ajax({
    url: '/currentSession/updateBankrollSession',
    dataType: 'json',
    method: 'post',
    data: JSON.stringify(dadosBankroll),
    success: (response) => {
      if (response.success) {
        $('#table-icon .bi-exclamation-circle').hide();
        $('#table-icon .bi-check-circle').show();
      } else {
        $('#table-icon .bi-check-circle').hide();
        $('#table-icon .bi-exclamation-circle').show();
      }
    }
  })
}
// Chame a função de cálculo quando um campo de entrada for alterado
$(document).on('input', '.money-dolar', function () {
  $('#table-icon .bi-check-circle').hide();
  $('#table-icon .bi-exclamation-circle').show();
  calcularTotais();
});

$(document).on('click', '#salvar-btn', function () {
  salvaBankroll();
});
$(document).on('blur change', '.saldo-inicial', function () {
  var index = $(this).closest('tr').index(); // Obtém o índice da linha atual
  var saldoFinalInput = $('.saldo-final').eq(index); // Obtém o campo de saldo_encerramento correspondente

  // Verifica se o valor do saldo_encerramento está vazio
  if (saldoFinalInput.val() === '') {
    saldoFinalInput.val($(this).val()); // Define o valor do saldo_encerramento como o valor do saldo_abertura
  }

  calcularTotais(); // Recalcula os totais após a modificação
});

function reloadBuyInsOpen(target) {
  $.ajax({
    url: '/currentSession/lazyLoadBuyInList',
    dataType: 'json',
    success: (json) => {
      target.html(json.html);
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