const ifConfirm = (msg, callback) => {
  modalConfirm(msg, function (abc) {
    callback(abc.target.getAttribute('data-return') == "true")
  })
}
var modalWrap = null;
const modalConfirm = (msg, conf) => {
  if (modalWrap !== null) {
    modalWrap.remove();
  }

  let title = 'Confirmação';
  let classTitle = 'text-light';
  let classBgTitle = 'bg-twitch';
  let classModalSize = '';
  let labelTrue = 'Confirmar';
  let labelFalse = 'Cancelar';
  let callback = () => { };
  if (typeof (conf) != 'undefined') {
    if (typeof (conf) == 'string') {
      title = conf;
    } else if (typeof (conf) == 'function') {
      callback = conf;
    } else {
      if (("title" in conf)) {
        title = conf.title;
      }
      if (("callback" in conf)) {
        callback = conf.callback;
      }
      if (("classTitle" in conf)) {
        classTitle = conf.classTitle;
      }
      if (("classBgTitle" in conf)) {
        classBgTitle = conf.classBgTitle;
      }

      if (("classModalSize" in conf)) {
        classModalSize = conf.classModalSize;
      }

      if (("labelTrue" in conf)) {
        labelTrue = conf.labelTrue;
      }
      if (("labelFalse" in conf)) {
        labelFalse = conf.labelFalse;
      }
    }
  }

  modalWrap = document.createElement('div');
  modalWrap.innerHTML = `
  <div class="modal fade" tabindex="-1" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered  ${classModalSize}">
      <div class="modal-content">
        <div class="modal-header ${classBgTitle}">
          <h5 class="modal-title ${classTitle}">${title}</h5>
        </div>
        <div class="modal-body">
          <p>${msg}</p>
        </div>
        <div class="modal-footer bg-light">
        <button type="button" data-return="false" class="btn btn-dark modal-danger-btn" data-bs-dismiss="modal">${labelFalse}</button>
        <button type="button" data-return="true" class="btn btn-dark modal-success-btn" data-bs-dismiss="modal">${labelTrue}</button>
        </div>
      </div>
    </div>
  </div>
`;

  modalWrap.querySelector('.modal-success-btn').onclick = callback
  modalWrap.querySelector('.modal-danger-btn').onclick = callback

  document.body.append(modalWrap);

  var modal = new bootstrap.Modal(modalWrap.querySelector('.modal'));
  modal.show();
}

/**
 * 
 * @param {string}
 *          title
 * @param {string}
 *          description content of modal body
 * @param {string}
 *          yesBtnLabel label of Yes button
 * @param {string}
 *          noBtnLabel label of No button
 * @param {function}
 *          callback callback function when click Yes button
 */
const errorAlert = (msg, callback) => {
  alert(msg, { 'callback': callback, 'classBgTitle': 'bg-danger', 'title': 'Erro' });
}

const successAlert = (msg, callback) => {
  alert(msg, { 'callback': callback, 'classBgTitle': 'bg-success', 'title': 'Sucesso' });
}

const warningAlert = (msg, callback) => {
  alert(msg, { 'callback': callback, 'classBgTitle': 'bg-warning', 'title': 'Aviso' });
}

const infoAlert = (msg, callback) => {
  alert(msg, { 'callback': callback, 'classBgTitle': 'bg-info', 'title': 'Info' });
}
const alert = (msg, conf) => {
  if (modalWrap !== null) {
    modalWrap.remove();
  }

  let title = 'Mensagem';
  let classTitle = 'text-light';
  let classBgTitle = 'bg-dark';
  let classModalSize = '';
  let labelClose = 'Ok';
  let callback = () => { };
  if (typeof (conf) != 'undefined') {
    if (typeof (conf) == 'string') {
      title = conf;
    } else if (typeof (conf) == 'function') {
      callback = conf;
    } else {
      if (("title" in conf)) {
        title = conf.title;
      }
      if (("callback" in conf)) {
        callback = conf.callback;
      }
      if (("classTitle" in conf)) {
        classTitle = conf.classTitle;
      }
      if (("classBgTitle" in conf)) {
        classBgTitle = conf.classBgTitle;
      }

      if (("classModalSize" in conf)) {
        classModalSize = conf.classModalSize;
      }

      if (("labelClose" in conf)) {
        labelClose = conf.labelClose;
      }
    }
  }

  modalWrap = document.createElement('div');
  modalWrap.innerHTML = `
    <div class="modal fade" tabindex="-1" data-bs-backdrop="false">
      <div class="modal-dialog modal-dialog-centered  ${classModalSize}">
        <div class="modal-content">
          <div class="modal-header ${classBgTitle}">
            <h5 class="modal-title ${classTitle}">${title}</h5>
            <button type="button" class="btn-close btn-light" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>${msg}</p>
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-dark modal-success-btn" data-bs-dismiss="modal">${labelClose}</button>
          </div>
        </div>
      </div>
    </div>
  `;

  modalWrap.querySelector('.modal-success-btn').onclick = callback;

  document.body.append(modalWrap);

  var modal = new bootstrap.Modal(modalWrap.querySelector('.modal'));
  modal.show();
}