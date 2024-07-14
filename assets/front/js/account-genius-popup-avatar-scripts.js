/**
 * Popup upload avatar
 * 
 * @since 1.0.0
 * @package MeuMouse.com
 */
jQuery(function($) {
  const openUploadAvatar = $('#upload-avatar');
  const uploadContainer = $('#upload-avatar-container');
  const closePopup = $('#close-popup-avatar');

  openUploadAvatar.on('click', function() {
    uploadContainer.addClass('show');
  });

  uploadContainer.on('click', function(event) {
    if (event.target === this) {
      $(this).removeClass('show');
    }
  });

  closePopup.on('click', function() {
    uploadContainer.removeClass('show');
  });
});


/**
 * Drag and drop avatar
 * 
 * @since 1.0.0
 * @package MeuMouse.com
 */
jQuery( function($) {
  const $dropArea = $('#drop-area');
  const $fileInput = $('#upload-file-avatar');
  const $fileList = $('.file-list');
  const $sendButton = $('#wc-account-genius-send-avatar');

  // Manipuladores de eventos
  $dropArea.on('dragover', handleDragOver);
  $dropArea.on('dragleave', handleDragLeave);
  $dropArea.on('drop', handleDrop);
  $dropArea.on('click', handleDropAreaClick);
  $fileInput.on('change', handleFileInputChange);

  // Processa os arquivos selecionados
  function handleFiles(files) {
    $fileList.empty(); // Limpa a lista de arquivos
    let hasInvalidFileType = false;

    for (let i = 0; i < files.length; i++) {
      const file = files[i];
      const listItem = $('<div class="file-item"></div>').text(file.name);
      $fileList.append(listItem);

      const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp', 'image/gif'];
      
      if (!allowedTypes.includes(file.type)) {
        hasInvalidFileType = true;
        break;
      }
    }

    // Atualiza o valor do campo de entrada oculto com os arquivos selecionados
    $fileInput.prop('files', files);

    // Ativa ou desativa o botão "Enviar" dependendo se existem arquivos selecionados e se há um tipo de arquivo inválido
    $sendButton.prop('disabled', files.length === 0 || hasInvalidFileType);
  }

  function handleDragOver(e) {
    e.preventDefault();
    $dropArea.addClass('drag-over');
  }

  function handleDragLeave(e) {
    e.preventDefault();
    $dropArea.removeClass('drag-over');
  }

  function handleDrop(e) {
    e.preventDefault();
    $dropArea.removeClass('drag-over');

    const files = e.originalEvent.dataTransfer.files;
    handleFiles(files);
  }

  function handleDropAreaClick(e) {
    $fileInput.click();
  }

  function handleFileInputChange(e) {
    const files = e.target.files;
    handleFiles(files);
  }
});