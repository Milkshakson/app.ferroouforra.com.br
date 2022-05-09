// Learn Template literals: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Template_literals
// Learn about Modal: https://getbootstrap.com/docs/5.0/components/modal/

var modalWrap = null;
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
const errorAlert = (msg,callback)=>{
    alert(msg,{'callback':callback,'classBgTitle':'bg-danger','title':'Erro'});
}

const successAlert = (msg,callback)=>{
    alert(msg,{'callback':callback,'classBgTitle':'bg-success','title':'Sucesso'});
}

const warningAlert = (msg,callback)=>{
    alert(msg,{'callback':callback,'classBgTitle':'bg-warning','title':'Aviso'});
}

const infoAlert = (msg,callback)=>{
    alert(msg,{'callback':callback,'classBgTitle':'bg-info','title':'Info'});
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
  let callback = ()=>{};
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
          
          if(("classModalSize" in conf)){
              classModalSize = conf.classModalSize;
          }
          
          if(("labelClose" in conf)){
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

/**
 * Module for displaying "Waiting for..." dialog using Bootstrap
 * 
 * @author Eugene Maslovich <ehpc@em42.ru>
 */

var waitingDialog = (function ($) {
    // Creating modal dialog's DOM
  var $dialog = $(
    '<div class="modal fade modal-waiting-dialog" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
    '<div class="modal-dialog modal-m">' +
    '<div class="modal-content">' +
      '<div class="modal-header text-center bg-secondary"><h3 style="margin:0;font-size:12px;font-style:italic;"></h3></div>' +
      '<div class="modal-body bg-light" style="padding:0">' +
      '<button class="btn btn-light" style="width:100%;margin:0;" type="button" disabled>'+
      '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'+
      '<span class="ps-1 loading text-justify"></span>'+
   '</button>'+
      '</div>' +
    '</div></div></div>');

  return {
    /**
     * Opens our dialog
     * 
     * @param message
     *          Custom message
     * @param options
     *          Custom options: options.dialogSize - bootstrap postfix for
     *          dialog size, e.g. "sm", "m"; options.progressType - bootstrap
     *          postfix for progress bar type, e.g. "success", "warning".
     */
    show: function (message, options) {
      // Assigning defaults
      var settings = $.extend({
        dialogSize: 'm',
        progressType: ''
      }, options);
      if (typeof message === 'undefined') {
        message = 'Aguarde';
      }
      if (typeof options === 'undefined') {
        options = {};
      }
      // Configuring dialog
      $dialog.find('.modal-dialog').attr('class', 'modal-dialog').addClass('modal-' + settings.dialogSize);
      $dialog.find('.progress-bar').attr('class', 'progress-bar');
      if (settings.progressType) {
        $dialog.find('.progress-bar').addClass('progress-bar-' + settings.progressType);
      }
      $dialog.find('button .loading').text(message);
      // Opening dialog
     // $dialog.modal();
      var modalDialog = new bootstrap.Modal($dialog);
      modalDialog.show();
    },
    /**
     * Closes dialog
     */
    hide: function () {
      $dialog.modal('hide').remove();
    }
  }

})(jQuery);