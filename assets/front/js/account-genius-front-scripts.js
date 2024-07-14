/**
 * Bootstrap collapse lite version
 * 
 * @since 1.0.0
 */
jQuery( function($) {
    const CLASS_NAME_SHOW = 'show'
    const CLASS_NAME_COLLAPSED = 'collapsed'
  
    $(document).on('click', '[data-bs-toggle="collapse"]', function(event) {
      event.preventDefault();
  
      const $target = $($(this).attr('data-bs-target') || $(this).attr('href'));
      const isShown = $target.hasClass(CLASS_NAME_SHOW);
  
      $target.toggleClass(CLASS_NAME_SHOW, !isShown);
      $target.toggleClass(CLASS_NAME_COLLAPSED, isShown);
      $target.css('height', isShown ? 0 : $target[0].scrollHeight);
    });
});

/**
 * Display loader and hide span on click
 */
jQuery( function($) {
  $('.button-loading').on('click', function() {
      let $btn = $(this);
      let originalText = $btn.text();
      let btnWidth = $btn.width();
      let btnHeight = $btn.height();

      // stay original width and height
      $btn.width(btnWidth);
      $btn.height(btnHeight);

      // Add spinner inside button
      $btn.html('<span class="spinner-border spinner-border-sm"></span>');
    
      setTimeout(function() {
        // Remove spinner
        $btn.html(originalText);
        
      }, 5000);
    });
});

/**
 * Activate tab on form login
 * 
 * @since 1.0.0
 */
jQuery( function($) {
  let switcher = $('[data-view]');
  
  if (switcher.length > 0) {
    switcher.each(function() {
      $(this).on('click', function(e) {
        let target = $(this).data('view');
        viewSwitch(target);
        if ($(this).attr('href') === '#') e.preventDefault();
      });
    });
  }
  
  let viewSwitch = function(target) {
    let targetView = $(target), targetParent = targetView.parent(), siblingViews = targetParent.find('.cs-view');
    siblingViews.removeClass('show');
    targetView.addClass('show');
  }
});


/**
 * Enable reset password button
 * 
 * @since 1.0.0
 * @package MeuMouse.com
 */
jQuery( function($) {
  $('#user_login').on('input', function() {
    var email = $(this).val();
    var btnResetPassword = $('#wc-account-genius-btn-reset-password');
    
    // Verificar se o campo de entrada não está vazio e é um email válido
    if (email.length > 0 && is_valid_email(email)) {
      btnResetPassword.prop('disabled', false); // Habilitar o botão
    } else {
      btnResetPassword.prop('disabled', true); // Desabilitar o botão
    }
  });
});

// Função auxiliar para verificar se um email é válido
function is_valid_email(email) {
  // Utilizando uma expressão regular simples para validar o formato do email
  var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  return emailRegex.test(email);
}


/**
 * Toggling password visibility in password input
 * 
 * @since 1.1.0
 * @package MeuMouse.com
 */
jQuery( function($) {
  $('.cs-password-toggle').each(function() {
    var $this = $(this);
    var passInput = $this.find('.form-control');
    var passToggle = $this.find('.cs-password-toggle-btn');

    passToggle.on('click', function(e) {
      if (e.target.type !== 'checkbox') return;
      if (e.target.checked) {
        passInput.attr('type', 'text');
      } else {
        passInput.attr('type', 'password');
      }
    });
  });
});