var timeToReload = timeToReload = 60 * 1000
const intervalreload = setInterval(() => {
    reloadBuyInsOpen($('.container-buyins-opened'))
    reloadSummaryOpen($('.container-summary-opened'))
}, timeToReload)

$(document).ready(() => {
    reloadSummaryOpen($('.container-summary-opened'))
    reloadBuyInsOpen($('.container-buyins-opened'))


    $(document).on('click', '.btn-add-game', (e) => {
        e.preventDefault()
        lazyFormRegistration()
    })

})

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
    var idForm
    $.ajax({
        url: '/currentSession/lazyFormRegistration',
        beforeSend: () => idForm = loadFormToAdd('...aguarde...', '...'),
        dataType: 'json',
        success: (data) => {
            $('#' + idForm).find('.modal-body').html(data.html)
            $('#' + idForm).find('.modal-title').html(data.title)
        }
    }).fail(() => target.html('Não foi possível recuperar o formulário.'))
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