(function ($) {
    "use strict";

	/**
	 * Activate tabs
	 * 
	 * @since 1.6.0
	 */
	jQuery( function($) {
		// Reads the index stored in localStorage, if it exists
		let activeTabIndex = localStorage.getItem('active_tab_index_account_genius');

		if (activeTabIndex === null) {
			// If it is null, activate the general tab
			$('.wc-account-genius-wrapper a.nav-tab[href="#options-settings"]').click();
		} else {
			$('.wc-account-genius-wrapper a.nav-tab').eq(activeTabIndex).click();
		}
	});
	  
	$(document).on('click', '.wc-account-genius-wrapper a.nav-tab', function() {
		// Stores the index of the active tab in localStorage
		let tabIndex = $(this).index();
		localStorage.setItem('active_tab_index_account_genius', tabIndex);
		
		let attrHref = $(this).attr('href');
		
		$('.wc-account-genius-wrapper a.nav-tab').removeClass('nav-tab-active');
		$('.wc-account-genius-form .nav-content').removeClass('active');
		$(this).addClass('nav-tab-active');
		$('.wc-account-genius-form').find(attrHref).addClass('active');
		
		return false;
	});

	/**
	 * Hide toast on click button or after 5 seconds
	 * 
	 * @since 1.0.0
	 */
	jQuery( function($) {
		$('.hide-toast').click( function() {
			$('.toast, .update-notice-ag-wc').fadeOut('fast');
		});

		setTimeout( function() {
			$('.toast, .update-notice-ag-wc').fadeOut('fast');
		}, 3000);
	});


	/**
	 * Display loader and hide span on click
	 * 
	 * @since 1.6.0
	 */
	jQuery( function($) {
		$('.button-loading').on('click', function() {
			let $btn = $(this);
			let expireDate = $btn.text();
			let btnWidth = $btn.width();
			let btnHeight = $btn.height();

			// keep original width and height
			$btn.width(btnWidth);
			$btn.height(btnHeight);

			// Add spinner inside button
			$btn.html('<span class="spinner-border spinner-border-sm"></span>');
		});

		// Prevent keypress enter
		$('.form-control').keypress( function(event) {
			if (event.keyCode === 13) {
				event.preventDefault();
			}
		});
	});


	/**
	 * Save options in AJAX
	 * 
	 * @since 1.5.0
	 * @version 2.1.0
	 */
	jQuery( function($) {
		let settings_form = $('form[name="wc-account-genius"]');
		let original_values = settings_form.serialize();
		var notification_delay;
		var debounce_timeout;

		settings_form.on('change', function() {
			$('.update-notice-ag-wc').fadeOut('fast', function() {
				$(this).removeClass('active').css('display', '');
			});

			if (settings_form.serialize() !== original_values) {
				if (debounce_timeout) {
					clearTimeout(debounce_timeout);
				}
				
				debounce_timeout = setTimeout(ajax_save_options, 500); // debounce delay of 500ms
			}
		});

		function ajax_save_options() {
			$.ajax({
				url: account_genius_admin_params.ajax_url,
				type: 'POST',
				data: {
					action: 'ajax_save_options_action',
					form_data: settings_form.serialize(),
				},
				success: function(response) {
					try {
						if (response.status === 'success') {
							original_values = settings_form.serialize();
							
							$('.update-notice-ag-wc').addClass('active');
	
							if (notification_delay) {
								clearTimeout(notification_delay);
							}
					
							notification_delay = setTimeout( function() {
								$('.update-notice-ag-wc').fadeOut('fast', function() {
									$(this).removeClass('active').css('display', '');
								});
							}, 3000);
						}
					} catch (error) {
						console.log(error);
					}
				}
			});
		}
	});


	/**
	 * Sortable and create new tabs
	 * 
	 * @since 1.8.0
	 * @version 2.1.0
	 */
	jQuery(document).ready( function($) {
		$('#account-tabs .sortable').sortable({
			update: function(event, ui) {
				update_tabs_priority(event, ui, '2');
			},
		});

		/**
		 * Update tabs priority on change position
		 * 
		 * @since 1.8.0
		 * @version 2.1.0
		 * @param {*} event | 
		 * @param {*} ui |
		 * @param {*} tab |
		 */
		function update_tabs_priority(event, ui, tab) {
			var container = ui.item.closest('#account-tabs .sortable');
	
			// Updates field priority
			$(container).find('.tab-item').each( function(index) {
				$(this).find('.change-priority').val(index + 1).change();
			});
		}

		sort_tabs_by_priority( $('#account-tabs .sortable') );

		/**
		 * Sort tabs by priority on load page
		 * 
		 * @since 1.8.0
		 * @version 2.1.0
		 * @param {string} container | Container to sort children elements
		 */
		function sort_tabs_by_priority(container) {
			var tab_items = container.find('.tab-item');
	
			tab_items.sort( function(a, b) {
				var priority_a = $(a).data('priority');
				var priority_b = $(b).data('priority');

				return priority_a - priority_b;
			});
	
			// Remove the ordered elements and reattach them to the container
			tab_items.detach().appendTo(container);
		}

		// open popup
		$(document).on('click', '.account-genius-tabs-trigger', function(e) {
			e.preventDefault();
			
			let get_tab = $(e.target).closest('li.tab-item').addClass('blocked');

			get_tab.children('.account-genius-tabs-container').addClass('show');
			$('#account-tabs .sortable').sortable('disable');
		});

		// close popup on click close button
		$(document).on('click', '.account-genius-tabs-close-popup', function(e) {
			e.preventDefault();

			$(this).closest('.account-genius-tabs-container').removeClass('show');
			$('.account-genius-tabs-trigger').closest('li.tab-item').removeClass('blocked');
			$('#account-tabs .sortable').sortable('enable');
		});
	
		// close popup if click outside the container
		$(document).on('click', '.account-genius-tabs-container', function(e) {
			if (e.target === this) {
				$(this).removeClass('show');
				$('.account-genius-tabs-trigger').closest('li.tab-item').removeClass('blocked');
				$('#account-tabs .sortable').sortable('enable');
			}
		});

		// change tab name on keyup
		$(document).on('keyup', '.get-name-tab', function(e) {
			let this_val = $(e.target).val();

			$('.tab-item.blocked').find('.account-genius-tabs-header').find('.tab-name').text(this_val);
			$('.tab-item.blocked').children('.tab-name').text(this_val);
		});

		// deactive tab on click toggle switch
		$(document).on('click', '.toggle-active-tab', function(e) {
			let checked = $(e.target).prop('checked');
			let target = $('.tab-item.blocked');

			$(target).toggleClass('active', checked).removeClass('inactive');
			$(target).toggleClass('inactive', !checked).removeClass('active');
		});

		// format input text for slug
		$(document).on('blur input change', '.set-endpoint-tab', function() {
			var endpoint_value = $(this).val().trim();
			
			// If input .set-endpoint-tab is empty
			if (endpoint_value === '') {
				// Get the text from the nearest input .get-name-tab
				var name_value = $(this).closest('.tab-item').find('.get-name-tab').val().trim();
				
				// Format for URL replacing spaces with dashes
				endpoint_value = name_value.replace(/\s+/g, '-').toLowerCase();
			} else {
				// If not empty, format for URL replacing spaces with dashes
				endpoint_value = endpoint_value.replace(/\s+/g, '-').toLowerCase();
			}
		
			// Remove accents, special characters, and anything not in the URL-safe character set
			endpoint_value = endpoint_value.normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[^a-z0-9-]/g, '');
		
			// Update the value of input .set-endpoint-tab with the formatted version
			$(this).val(endpoint_value);
		});		

		// show/hide redirect link input
		$(document).on('click', '.enable-redirect-link', function() {
			if ( $(this).prop('checked') ) {
				$('.tab-item.blocked').find('tr.redirect-link').removeClass('d-none');
				$('.tab-item.blocked').find('tr.redirect-link-content').addClass('d-none');
			} else {
				$('.tab-item.blocked').find('tr.redirect-link').addClass('d-none');
				$('.tab-item.blocked').find('tr.redirect-link-content').removeClass('d-none');
			}
		});

		// display popup for add new tab
		display_popup( $('#add_new_tab_item'), $('#add_new_tab_container'), $('#add_new_tab_close') );

		// display popup for set icon tab
		display_popup( $('.display-icon-tab'), $('#boxicons_library_container'), $('#boxicons_library_close') );

		// exclude tab action
		$(document).on('click', '.exclude-tab', function(e) {
			e.preventDefault();

			let tab_item = $(this).closest('li.tab-item');
			let index = tab_item.find('.set-array-key-tab').val();
			var notification_delay;

			// Fade out the tab with animation
			tab_item.fadeOut(400, function() {
				tab_item.remove();
			});

			$.ajax({
				url: account_genius_admin_params.ajax_url,
				type: 'POST',
				data: {
					action: 'remove_tab_from_options',
					tab_to_remove: index,
				},
				success: function(response) {
					try {
						var response_data = JSON.parse(response);
	
						if (response_data && response_data.status === 'success') {
							$('.update-notice-ag-wc').addClass('active');
	
							if (notification_delay) {
								clearTimeout(notification_delay);
							}
					
							notification_delay = setTimeout( function() {
								$('.update-notice-ag-wc').fadeOut('fast', function() {
									$(this).removeClass('active').css('display', '');
								});
							}, 3000);
						} else {
							console.error('Invalid JSON response or missing "status" field:', response);
						}
					} catch (error) {
						console.error('Error parsing JSON:', error);
					}
				},
				error: function(xhr, textStatus, errorThrown) {
					console.error('AJAX request failed:', textStatus, errorThrown);
				}
			});
		});

		// set icon item to tab
		$(document).on('click', '.icon-item', function(e) {
			e.preventDefault();

			var get_icon = $(this).data('icon');
			var final_icon_class = 'bx ' + get_icon;

			$('.tab-item.blocked').find('.set-icon-tab').val(final_icon_class).change();
			$('.tab-item.blocked').find('.display-icon-tab > i').removeClass().addClass(final_icon_class);
			$('.tab-item.blocked').children('i.bx').removeClass().addClass(final_icon_class);

			// popup for add new tab is active
			if ( $('#add_new_tab_container').hasClass('show') ) {
				var new_tab_container = $('#add_new_tab_container').children('.popup-content');

				$(new_tab_container).find('#add_new_tab_icon').val(final_icon_class).change();
				$(new_tab_container).find('#add_new_tab_icon').siblings('.display-icon-tab').children('i').removeClass().addClass(final_icon_class);
			}

			$('#boxicons_library_container').removeClass('show');
		});

		// add icon on <i> element on paste event
		$(document).on('paste', '.set-icon-tab', function(e) {
			setTimeout( function() {
				let pasted_value = $(e.target).val();
				let matches = pasted_value.match(/class=['"]([^'"]+)['"]/);

				if (matches && matches.length > 1) {
					$(e.target).val(matches[1]);
					$(e.target).siblings('.display-icon-tab').children('i').removeClass().addClass(matches[1]);
					$('.tab-item.blocked').children('i').removeClass().addClass(matches[1]);
				}
			}, 10);
		});

		// add icon on <i> element on keyup event
		$(document).on('keyup', '.set-icon-tab', function(e) {
			let this_val = $(e.target).val();

			$(e.target).siblings('.display-icon-tab').children('i').removeClass().addClass(this_val);
			$('.tab-item.blocked').children('i').removeClass().addClass(this_val);
		});
	});


	/**
	 * Process upload alternative license
	 * 
	 * @since 2.0.0
	 */
	$(document).ready( function() {
		// Add event handlers for dragover and dragleave
		$('#license_key_zone').on('dragover dragleave', function(e) {
			e.preventDefault();
			$(this).toggleClass('drag-over', e.type === 'dragover');
		});
	
		// Add event handlers for drop
		$('#license_key_zone').on('drop', function(e) {
			e.preventDefault();
	
			var file = e.originalEvent.dataTransfer.files[0];

			if ( ! $(this).hasClass('file-uploaded') ) {
				handle_file(file, $(this));
			}
		});
	
		// Adds a change event handler to the input file
		$('#upload_license_key').on('change', function(e) {
			e.preventDefault();
	
			var file = e.target.files[0];

			handle_file(file, $(this).parents('.dropzone'));
		});
	
		/**
		 * Handle sent file
		 * 
		 * @since 2.0.0
		 * @param {string} file | File
		 * @param {string} dropzone | Dropzone div
		 * @returns void
		 */
		function handle_file(file, dropzone) {
			if (file) {
				var filename = file.name;

				dropzone.children('.file-list').removeClass('d-none').text(filename);
				dropzone.addClass('file-processing');
				dropzone.append('<div class="spinner-border"></div>');
				dropzone.children('.drag-text').addClass('d-none');
				dropzone.children('.drag-and-drop-file').addClass('d-none');
				dropzone.children('.form-inter-bank-files').addClass('d-none');
	
				// Create a FormData object to send the file via AJAX
				var form_data = new FormData();
				form_data.append('action', 'account_genius_alternative_activation');
				form_data.append('file', file);
	
				$.ajax({
					url: account_genius_admin_params.ajax_url,
					type: 'POST',
					data: form_data,
					processData: false,
					contentType: false,
					success: function(response) {
						try {
							if (response.status === 'success') {
								dropzone.addClass('file-uploaded').removeClass('file-processing');
								dropzone.children('.spinner-border').remove();
								dropzone.append('<div class="upload-notice d-flex flex-collumn align-items-center"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><path fill="#22c55e" d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"></path><path fill="#22c55e" d="M9.999 13.587 7.7 11.292l-1.412 1.416 3.713 3.705 6.706-6.706-1.414-1.414z"></path></svg><span>'+ account_genius_admin_params.upload_success +'</span></div>');
								dropzone.children('.file-list').addClass('d-none');

								setTimeout( function() {
									location.reload();
								}, 1000);
							} else if (response.status === 'invalid_file') {
								$('.drop-file-license-key').after('<div class="text-danger mt-2"><p>'+ account_genius_admin_params.invalid_file +'</p></div>');
								dropzone.addClass('invalid-file').removeClass('file-processing');
								dropzone.children('.spinner-border').remove();
								dropzone.children('.drag-text').removeClass('d-none');
								dropzone.children('.drag-and-drop-file').removeClass('d-none');
								dropzone.children('.form-inter-bank-files').removeClass('d-none');
								dropzone.children('.file-list').addClass('d-none');
							}
						} catch (error) {
							console.log(error);
						}
					},
					error: function(xhr, status, error) {
						dropzone.addClass('fail-upload').removeClass('file-processing');
						console.log('Erro ao enviar o arquivo');
						console.log(xhr.responseText);
					}
				});
			}
		}
	});


	/**
	 * Helper color selector
	 * 
	 * @since 2.0.0
	 */
	jQuery(document).ready( function($) {
		$('.get-color-selected').on('input', function() {
			var color_value = $(this).val();
	
			$(this).closest('.color-container').find('.form-control-color').val(color_value);
		});
	
		$('.form-control-color').on('input', function() {
			var color_value = $(this).val();
	
			$(this).closest('.color-container').find('.get-color-selected').val(color_value);
		});

		$('.reset-color').on('click', function(e) {
			e.preventDefault();
			var color_value = $(this).data('color');

			$(this).closest('.color-container').find('.form-control-color').val(color_value);
			$(this).closest('.color-container').find('.get-color-selected').val(color_value).change();
		});
	});


	/**
	 * Add new tab action
	 * 
	 * @since 2.1.0
	 */
	jQuery(document).ready( function($) {
		var form_data = new FormData();
		form_data.append('action', 'add_new_tab_action');
		form_data.append('tab_redirect', 'no');

		var get_tab_priority = $('#account-tabs ul.sortable > li').length + 1;
	
		// Function to update form_data with current input values
		function update_form_data() {
			form_data.set('tab_name', $('#add_new_tab_name').val());
			form_data.set('tab_endpoint', $('#add_new_tab_endpoint').val());
			form_data.set('tab_icon', $('#add_new_tab_icon').val());
			form_data.set('tab_class', $('#add_new_tab_class').val());
			form_data.set('tab_content', $('#add_new_tab_content').val());
			form_data.set('tab_redirect_link', $('#add_new_tab_redirect_link').val());
			form_data.set('tab_priority', get_tab_priority);
		}
	
		// Update form_data when redirect checkbox is clicked
		$('#add_new_tab_redirect').on('click', function() {
			if ($(this).prop('checked')) {
				$('.redirect-link.new-tab').removeClass('d-none');
				$('.redirect-link-content.new-tab').addClass('d-none');
				form_data.set('tab_redirect', 'yes');
			} else {
				$('.redirect-link-content.new-tab').removeClass('d-none');
				$('.redirect-link.new-tab').addClass('d-none');
				form_data.set('tab_redirect', 'no');
			}
		});
	
		$('#add_new_tab_name').on('change', function() {
			let get_value = $(this).val();
			if ($('#add_new_tab_endpoint').val() === '') {
				$('#add_new_tab_endpoint').val(get_value).change();
			}
		});
	
		function check_tab_name_and_endpoint() {
			var name = $('#add_new_tab_name').val().trim();
			var endpoint = $('#add_new_tab_endpoint').val().trim();
	
			if (name !== '' && endpoint !== '') {
				$('#add_new_tab_submit').prop('disabled', false);
			} else {
				$('#add_new_tab_submit').prop('disabled', true);
			}
		}
	
		// Check if tab name and endpoint is empty
		$('#add_new_tab_name, #add_new_tab_endpoint').on('input change', function() {
			setTimeout(function() {
				check_tab_name_and_endpoint();
			}, 100);
		});

		$(document).on('click', '#add_new_tab_submit', function(e) {
			e.preventDefault();
	
			// Update form_data with current input values
			update_form_data();

			var get_tab_name = $('#add_new_tab_name').val();
			var get_tab_id = $('#add_new_tab_endpoint').val();
			var get_tab_icon = $('#add_new_tab_icon').val();
			var get_tab_class = $('#add_new_tab_class').val();
			var get_tab_content = $('#add_new_tab_content').val();
			var get_tab_link = $('#add_new_tab_redirect_link').val();

			var new_tab_element = `<li id="${get_tab_id}" class="tab-item ui-sortable-handle" data-native="no" data-priority="${get_tab_priority}">
					<input type="hidden" class="change-priority" name="account_tabs[${get_tab_id}][priority]" value="${get_tab_priority}">
					<input type="hidden" class="get-native-tab" name="account_tabs[${get_tab_id}][native]" value="no">
					<input type="hidden" class="get-endpoint-tab" name="account_tabs[${get_tab_id}][endpoint]" value="${get_tab_id}">
					<input type="hidden" class="set-array-key-tab" name="account_tabs[${get_tab_id}][array_key]" value="${get_tab_id}">
					<input type="hidden" class="get-content-tab" name="account_tabs[${get_tab_id}][content]" value="${get_tab_content}">
					
					<i class="${get_tab_icon}"></i>
					<span class="tab-name">${get_tab_name}</span>
					<button class="account-genius-tabs-trigger btn btn-sm btn-outline-primary ms-auto rounded-3" data-trigger="${get_tab_id}">${account_genius_admin_params.edit_tab}</button>
					
					<div class="account-genius-tabs-container">
						<div class="account-genius-tabs-content">
							<div class="account-genius-tabs-header">
								<h5 class="account-genius-tabs-popup-title">${account_genius_admin_params.tab_title} <strong class="tab-name">${get_tab_name}</strong></h5>
								<button class="account-genius-tabs-close-popup btn-close fs-lg" aria-label="Fechar"></button>
							</div>
							<div class="tab-conditions">
								<table class="form-table">
									<tbody>
										<tr>
											<th class="w-50">
												${account_genius_admin_params.tab_name}
												<span class="wc-account-genius-description">${account_genius_admin_params.tab_name_description}</span>
											</th>
											<td class="w-50">
												<input type="text" class="get-name-tab form-control" name="account_tabs[${get_tab_id}][label]" value="${get_tab_name}">
											</td>
										</tr>
										<tr>
											<th class="w-50">
												${account_genius_admin_params.endpoint_tab}
												<span class="wc-account-genius-description">${account_genius_admin_params.endpoint_tab_description}</span>
											</th>
											<td class="w-50">
												<input type="text" class="set-endpoint-tab form-control" name="account_tabs[${get_tab_id}][endpoint]" value="${get_tab_id}">
											</td>
										</tr>
										<tr>
											<th class="w-50">
												${account_genius_admin_params.icon_tab}
												<span class="wc-account-genius-description">${account_genius_admin_params.icon_tab_description}</span>
											</th>
											<td class="w-50">
												<div class="input-group">
													<button class="display-icon-tab fs-xg btn btn-outline-secondary d-flex align-items-center">
														<i class="${get_tab_icon}"></i>
													</button>
													<input type="text" class="set-icon-tab form-control" name="account_tabs[${get_tab_id}][icon]" value="${get_tab_icon}" placeholder="${account_genius_admin_params.icon_placeholder}">
												</div>
											</td>
										</tr>
										<tr>
											<th class="w-50">
												${account_genius_admin_params.class_css_title}
												<span class="wc-account-genius-description">${account_genius_admin_params.class_css_description}</span>
											</th>
											<td class="w-50">
												<input type="text" class="form-control" name="account_tabs[${get_tab_id}][class]" value="${get_tab_class}" placeholder="${account_genius_admin_params.class_css_placeholder}">
											</td>
										</tr>
										<tr class="redirect-link-content">
											<th class="w-50">
												${account_genius_admin_params.tab_content_title}
												<span class="wc-account-genius-description">${account_genius_admin_params.tab_content_description}</span>
											</th>
											<td class="w-50">
												<div class="form-check form-switch">
													<textarea class="form-control" name="account_tabs[${get_tab_id}][content]">${get_tab_content}</textarea>
												</div>
											</td>
										</tr>
										<tr>
											<th class="w-50">
												${account_genius_admin_params.active_redirect_tab_title}
												<span class="wc-account-genius-description">${account_genius_admin_params.active_redirect_tab_description}</span>
											</th>
											<td class="w-50">
												<div class="form-check form-switch">
													<input type="checkbox" class="toggle-switch enable-redirect-link" name="account_tabs[${get_tab_id}][redirect]" value="yes">
												</div>
											</td>
										</tr>
										<tr class="redirect-link d-none">
											<th class="w-50">
												${account_genius_admin_params.redirect_tab_link_title}
												<span class="wc-account-genius-description">${account_genius_admin_params.redirect_tab_link_description}</span>
											</th>
											<td class="w-50">
												<div class="form-check form-switch">
													<input type="text" class="form-control" name="account_tabs[${get_tab_id}][link]" value="${get_tab_link}">
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<button class="btn btn-outline-danger btn-icon ms-3 rounded-3 exclude-tab" data-exclude="${get_tab_id}">
						<i class="bx bx-trash-alt fs-lg"></i>
					</button>
				</li>`;
	
			let btn = $(this);
			let btn_html = btn.html();
			let btn_width = btn.width();
			let btn_height = btn.height();
	
			// Keep original width and height
			btn.width(btn_width);
			btn.height(btn_height);
			btn.html('<span class="spinner-border spinner-border-sm"></span>');
			btn.prop('disabled', true);
	
			$.ajax({
				url: account_genius_admin_params.ajax_url,
				type: 'POST',
				data: form_data,
				processData: false,
				contentType: false,
				success: function(response) {
					try {
						if (response.status === 'success') {
							btn.removeClass('btn-primary').addClass('btn-success').html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: #ffffff"><path d="m10 15.586-3.293-3.293-1.414 1.414L10 18.414l9.707-9.707-1.414-1.414z"></path></svg>');
							btn.prop('disabled', false);

							$('#account-tabs > ul.sortable').append(new_tab_element);

							$('.wc-account-genius-wrapper').before(`<div class="toast tab-notice toast-success show">
								<div class="toast-header bg-success text-white">
									<svg class="account-genius-toast-check-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ffffff"><g stroke-width="0"/><g stroke-linecap="round" stroke-linejoin="round"/><g><path d="M10.5 15.25C10.307 15.2353 10.1276 15.1455 9.99998 15L6.99998 12C6.93314 11.8601 6.91133 11.7029 6.93756 11.55C6.96379 11.3971 7.03676 11.2562 7.14643 11.1465C7.2561 11.0368 7.39707 10.9638 7.54993 10.9376C7.70279 10.9114 7.86003 10.9332 7.99998 11L10.47 13.47L19 5.00004C19.1399 4.9332 19.2972 4.91139 19.45 4.93762C19.6029 4.96385 19.7439 5.03682 19.8535 5.14649C19.9632 5.25616 20.0362 5.39713 20.0624 5.54999C20.0886 5.70286 20.0668 5.86009 20 6.00004L11 15C10.8724 15.1455 10.6929 15.2353 10.5 15.25Z" fill="#ffffff"/> <path d="M12 21C10.3915 20.9974 8.813 20.5638 7.42891 19.7443C6.04481 18.9247 4.90566 17.7492 4.12999 16.34C3.54037 15.29 3.17596 14.1287 3.05999 12.93C2.87697 11.1721 3.2156 9.39921 4.03363 7.83249C4.85167 6.26578 6.1129 4.9746 7.65999 4.12003C8.71001 3.53041 9.87134 3.166 11.07 3.05003C12.2641 2.92157 13.4719 3.03725 14.62 3.39003C14.7224 3.4105 14.8195 3.45215 14.9049 3.51232C14.9903 3.57248 15.0622 3.64983 15.116 3.73941C15.1698 3.82898 15.2043 3.92881 15.2173 4.03249C15.2302 4.13616 15.2214 4.2414 15.1913 4.34146C15.1612 4.44152 15.1105 4.53419 15.0425 4.61352C14.9745 4.69286 14.8907 4.75712 14.7965 4.80217C14.7022 4.84723 14.5995 4.87209 14.4951 4.87516C14.3907 4.87824 14.2867 4.85946 14.19 4.82003C13.2186 4.52795 12.1987 4.43275 11.19 4.54003C10.193 4.64212 9.22694 4.94485 8.34999 5.43003C7.50512 5.89613 6.75813 6.52088 6.14999 7.27003C5.52385 8.03319 5.05628 8.91361 4.77467 9.85974C4.49307 10.8059 4.40308 11.7987 4.50999 12.78C4.61208 13.777 4.91482 14.7431 5.39999 15.62C5.86609 16.4649 6.49084 17.2119 7.23999 17.82C8.00315 18.4462 8.88357 18.9137 9.8297 19.1953C10.7758 19.4769 11.7686 19.5669 12.75 19.46C13.747 19.3579 14.713 19.0552 15.59 18.57C16.4349 18.1039 17.1818 17.4792 17.79 16.73C18.4161 15.9669 18.8837 15.0864 19.1653 14.1403C19.4469 13.1942 19.5369 12.2014 19.43 11.22C19.4201 11.1169 19.4307 11.0129 19.461 10.9139C19.4914 10.8149 19.5409 10.7228 19.6069 10.643C19.6728 10.5631 19.7538 10.497 19.8453 10.4485C19.9368 10.3999 20.0369 10.3699 20.14 10.36C20.2431 10.3502 20.3471 10.3607 20.4461 10.3911C20.5451 10.4214 20.6372 10.471 20.717 10.5369C20.7969 10.6028 20.863 10.6839 20.9115 10.7753C20.9601 10.8668 20.9901 10.9669 21 11.07C21.1821 12.829 20.842 14.6026 20.0221 16.1695C19.2022 17.7363 17.9389 19.0269 16.39 19.88C15.3288 20.4938 14.1495 20.8755 12.93 21C12.62 21 12.3 21 12 21Z" fill="#ffffff"/></g></svg>
									<span class="me-auto">${response.success_message_header}</span>
									<button class="btn-close btn-close-white ms-2 hide-toast" type="button" aria-label="Fechar"></button>
								</div>
								<div class="toast-body">${response.success_message_body}</div>
							</div>`);

							// hide notice with fadeout
							setTimeout( function() {
								$('.tab-notice').fadeOut('fast');
							}, 3000);

							// remove notice from HTML after 3.5 seconds
							setTimeout( function() {
								$('.tab-notice').remove();
							}, 3500);
	
							$('#add_new_tab_container').removeClass('show');
							$('#add_new_tab_name').val('');
							$('#add_new_tab_endpoint').val('');
							$('#add_new_tab_icon').val('bx bx-info-circle');
							$('#add_new_tab_icon').siblings('.display-icon-tab').children('i').removeClass().addClass('bx bx-info-circle');
							$('#add_new_tab_class').val('');
							$('#add_new_tab_content').val('');
							$('#add_new_tab_redirect_link').val('');
							$('#add_new_tab_redirect').prop('checked', false);
	
							setTimeout( function() {
								btn.removeClass('btn-success').addClass('btn-primary').html(btn_html);
							}, 500);
						} else {
							btn.html(btn_html);

							$('.wc-account-genius-wrapper').before(`<div class="toast tab-notice toast-danger show">
								<div class="toast-header bg-danger text-white">
									<svg class="account-genius-toast-check-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ffffff"><g stroke-width="0"/><g stroke-linecap="round" stroke-linejoin="round"/><g><path d="M10.5 15.25C10.307 15.2353 10.1276 15.1455 9.99998 15L6.99998 12C6.93314 11.8601 6.91133 11.7029 6.93756 11.55C6.96379 11.3971 7.03676 11.2562 7.14643 11.1465C7.2561 11.0368 7.39707 10.9638 7.54993 10.9376C7.70279 10.9114 7.86003 10.9332 7.99998 11L10.47 13.47L19 5.00004C19.1399 4.9332 19.2972 4.91139 19.45 4.93762C19.6029 4.96385 19.7439 5.03682 19.8535 5.14649C19.9632 5.25616 20.0362 5.39713 20.0624 5.54999C20.0886 5.70286 20.0668 5.86009 20 6.00004L11 15C10.8724 15.1455 10.6929 15.2353 10.5 15.25Z" fill="#ffffff"/> <path d="M12 21C10.3915 20.9974 8.813 20.5638 7.42891 19.7443C6.04481 18.9247 4.90566 17.7492 4.12999 16.34C3.54037 15.29 3.17596 14.1287 3.05999 12.93C2.87697 11.1721 3.2156 9.39921 4.03363 7.83249C4.85167 6.26578 6.1129 4.9746 7.65999 4.12003C8.71001 3.53041 9.87134 3.166 11.07 3.05003C12.2641 2.92157 13.4719 3.03725 14.62 3.39003C14.7224 3.4105 14.8195 3.45215 14.9049 3.51232C14.9903 3.57248 15.0622 3.64983 15.116 3.73941C15.1698 3.82898 15.2043 3.92881 15.2173 4.03249C15.2302 4.13616 15.2214 4.2414 15.1913 4.34146C15.1612 4.44152 15.1105 4.53419 15.0425 4.61352C14.9745 4.69286 14.8907 4.75712 14.7965 4.80217C14.7022 4.84723 14.5995 4.87209 14.4951 4.87516C14.3907 4.87824 14.2867 4.85946 14.19 4.82003C13.2186 4.52795 12.1987 4.43275 11.19 4.54003C10.193 4.64212 9.22694 4.94485 8.34999 5.43003C7.50512 5.89613 6.75813 6.52088 6.14999 7.27003C5.52385 8.03319 5.05628 8.91361 4.77467 9.85974C4.49307 10.8059 4.40308 11.7987 4.50999 12.78C4.61208 13.777 4.91482 14.7431 5.39999 15.62C5.86609 16.4649 6.49084 17.2119 7.23999 17.82C8.00315 18.4462 8.88357 18.9137 9.8297 19.1953C10.7758 19.4769 11.7686 19.5669 12.75 19.46C13.747 19.3579 14.713 19.0552 15.59 18.57C16.4349 18.1039 17.1818 17.4792 17.79 16.73C18.4161 15.9669 18.8837 15.0864 19.1653 14.1403C19.4469 13.1942 19.5369 12.2014 19.43 11.22C19.4201 11.1169 19.4307 11.0129 19.461 10.9139C19.4914 10.8149 19.5409 10.7228 19.6069 10.643C19.6728 10.5631 19.7538 10.497 19.8453 10.4485C19.9368 10.3999 20.0369 10.3699 20.14 10.36C20.2431 10.3502 20.3471 10.3607 20.4461 10.3911C20.5451 10.4214 20.6372 10.471 20.717 10.5369C20.7969 10.6028 20.863 10.6839 20.9115 10.7753C20.9601 10.8668 20.9901 10.9669 21 11.07C21.1821 12.829 20.842 14.6026 20.0221 16.1695C19.2022 17.7363 17.9389 19.0269 16.39 19.88C15.3288 20.4938 14.1495 20.8755 12.93 21C12.62 21 12.3 21 12 21Z" fill="#ffffff"/></g></svg>
									<span class="me-auto">${response.error_message_header}</span>
									<button class="btn-close btn-close-white ms-2 hide-toast" type="button" aria-label="Fechar"></button>
								</div>
								<div class="toast-body">${response.error_message_body}</div>
							</div>`);

							// hide notice with fadeout
							setTimeout( function() {
								$('.tab-notice').fadeOut('fast');
							}, 3000);

							// remove notice from HTML after 3.5 seconds
							setTimeout( function() {
								$('.tab-notice').remove();
							}, 3500);
						}
					} catch (error) {
						console.log(error);
					}
				},
				error: function(xhr, status, error) {
					console.log(xhr.responseText);
				}
			});
		});
	});	
	
})(jQuery);