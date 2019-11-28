/*
* 2007-2017 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2017 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

var cleanInt = function (x) {
	x = Number(x);
	return x >= 0 ? Math.floor(x) : Math.ceil(x);
};

var p = function () {
	if (debug_mode) {
		var i = 0,
		arg_lenght = arguments.length;
		if (arg_lenght > 0) {
			for (i; i<arg_lenght; i++) {
				if (arguments[i] instanceof Array) {
					console.log(arguments[i]);
				} else if (typeof(arguments[i]) === 'object') {
					if (typeof console.table == 'function') {
						console.table(arguments[i]);
					} else {
						console.log(arguments[i]);
				}
				} else {
					// console.log(arguments.callee.caller.toString());
					console.log(arguments[i]);
				}
			}
		}
	}
};

jQuery.fn.listAttributes = function(prefix) {
	var list = [],
		key;
	$(this).each(function() {
		console.info(this);
		var attributes = [];
		for (key in this.attributes) {
			if(!isNaN(key)) {
				if(!prefix || this.attributes[key].name.substr(0,prefix.length) === prefix) {
					attributes.push(this.attributes[key].name);
				}
			}
		}
		list.push(attributes);
	});
	return (list.length > 1 ? list : list[0]);
};


var tmp = $;
$ = $j210;

// Main Function
var Main = function () {
	/**
	** Check type of category
	*/
	var checkRadio = function(obj1, obj2, btn) {
		obj1.click(function() {
			btn.enable();
		});

		obj2.click(function() {
			btn.disable();
			checkInputsExtra($('#category_id'), btn);
		});

		checked1 = obj1.find('input').attr('checked');
		if (typeof(checked1) !== 'undefined') {
			btn.enable();
		}

		checked2 = obj2.find('input').attr('checked');
		if (typeof(checked2) !== 'undefined') {
			checkInputsExtra($('#category_id'), btn);
		}
	};

	/**
	** Check if inputs are not empty (category tree)
	*/
	var checkInputsExtra = function(obj, btn) {
		checked2 = $(document).find("#radios-1").find('input').attr('checked');
		if ($(obj).val().length > 0) {
			if (typeof(checked2) !== 'undefined') {
				if ($(obj).val() !== '0') {
					btn.enable();
				} else {
					btn.disable();
				}
			} else {
				btn.disable();
			}
		} else {
			btn.disable();
		}
	};

	/**
	** Check if inputs are not empty
	*/
	var checkInputs = function(obj, btn) {
		obj.data('val', obj.val());
		obj.change(function() {
			if (obj.val().length > 0) {
				btn.enable();
			} else {
				btn.disable();
			}
		});

		obj.keyup(function() {
			if(obj.val() !== obj.data('val')) {
				obj.data('val', obj.val());
				$(this).change();
			}
		});

		if (obj.val().length > 0) {
			btn.enable();
		} else {
			btn.disable();
		}
	};

	/**
	** Disable some buttons
	*/
	var disableButtons = function (header, button) {
		// Disable & hide save button
		button.disable().hide();
		// Allow to close from header
		header.find('.bootstrap-dialog-close-button').attr('style', '');
	};

	/**
	** Display category tree
	*/
	var addTree = function (tree, body, btn, categ_tree) {
		tree.on('changed.jstree', function (e, data) {
			e.preventDefault();
			var i, z, r = [];
			for (i = 0, z = data.selected.length; i < z; i++) {
				id = data.instance.get_node(data.selected[i]).id;
				parent = "#"+data.instance.get_parent(data.selected[i]);
				cat_id = id.replace('category_','');
				r.push(cat_id);
			}
			categ_tree = r.join(',');
			body.find('#category_id').val(categ_tree);

			checkInputsExtra(body.find('#category_id'), btn);

		}).live("ready.jstree", function (event, data) {
			tree.jstree("open_node", parent);
		}).jstree({
			"checkbox" : {
				"three_state" : false,
				"keep_selected_style" : false
			},
			"plugins" : ["checkbox"]
		});

		// Show hide tree
		if (typeof(categ_tree) !== 'undefined') {
			body.find('#catree').removeClass('hide');
			body.find("#radios-1").find('input').attr('checked', true);
			checkInputsExtra(body.find('#category_id'), btn);
		} else {
			categ_tree = 0;
		}

		body.find(".radio").on('click', function (e) {
			$(this).find('input').attr('checked', true);
		});

		body.find("#radios-0").on('click', function (e) {
			body.find('#catree').addClass('hide');
			tree.find('.jstree-clicked').removeClass('jstree-clicked');
			body.find('#category_id').val(0);
		});

		body.find("#radios-1").on('click', function (e) {
			body.find('#catree').removeClass('hide');
		});

		// Extended Tree select
		body.find("#checkall").on('click', function (e) {
			tree.jstree("select_all");
			checkInputsExtra(body.find('#category_id'), btn);
		});

		body.find("#uncheckall").on('click', function (e) {
			tree.jstree("deselect_all");
			checkInputsExtra(body.find('#category_id'), btn);
		});

		body.find("#expandall").on('click', function (e) {
			tree.jstree("open_all");
		});

		body.find("#collapseall").on('click', function (e) {
			tree.jstree("close_all");
		});

		// Force update category selection
		body.find('#category_id').val(categ_tree);
	};

	/**
	** Load Modal for Add or Edit SEO Rule
	*/
	var loadFormModal = function (id, role, type, table) {
		id_rule = (typeof(id) !== 'undefined') ? id : '';
		role = (typeof(role) !== 'undefined') ? role : 'meta';
		type = (typeof(type) !== 'undefined') ? type : 'product';
		$.ajax({
			type: 'POST',
			url: admin_module_ajax_url,
			dataType: 'html',
			data: {
				controller : admin_module_controller,
				action : 'LoadForm',
				ajax : true,
				id_tab : current_id_tab,
				type: type,
				role: role,
				id_rule : id_rule
			},
			success: function(jsonData) {
				// Filled the fields with pattern tags
				var selected_input;
				var response_form = $('<div id="response_form"></div>');
				response_form.html(jsonData);
				// Show Modal
				BootstrapDialog.show({
					//title: '',
					sizeModal: 'SIZE_XLARGE',
					onshow: function(dialogRef) {
						dialogRef.setClosable(false);

						var $header = dialogRef.getModalHeader();
						var $body = dialogRef.getModalBody();
						var $footer = dialogRef.getModalFooter();
						var $button = dialogRef.getButton('btn-save');
						var $next_button = dialogRef.getButton('next-step');
						var $back_button = dialogRef.getButton('back-step');
						var $twitter_card = $body.find('#tw_card_type');
						var $tree = $body.find('#jstree');
						var categ_tree;

						// Disable & hide save button
						disableButtons($header, $button);

						// Category Tree
						// See forms_meta.tpl & category-tree-branch.tpl
						// A with classe "jstree-clicked"
						addTree($tree, $body, $next_button, categ_tree);

						// Check if this rule is a default one
						isDefaultRule = function (id_lang, id_rule) {
							$.post(admin_module_ajax_url, {
								controller : admin_module_controller,
								action : 'DefaultRule',
								ajax : true,
								id_tab : current_id_tab,
								type: type,
								role: role,
								id_lang: id_lang,
								id_rule : id_rule
							}).done(function(data) {
								data = cleanInt(data);
								if (typeof(id_rule) === 'object') {
									if (data === 1) {
										$body.find("#radios-0").hide();
										$body.find("#radios-1").show();
										$body.find('#catree').removeClass('hide');
										$body.find("#radios-1").find('input').attr('checked', true);
									} else {
										$body.find("#radios-0").show();
										$body.find("#radios-1").show();
										$body.find('#catree').addClass('hide');
										$body.find("#radios-1").find('input').attr('checked', false);
									}
								} else {
									if (data === 1) {
										$body.find("#radios-1").hide();
										$body.find("#radios-0").find('input').attr('checked', true);
									} else {
										$body.find("#radios-0").hide();
										$body.find("#radios-1").find('input').attr('checked', true);
									}
								}
							});
						};
						isDefaultRule(cleanInt($body.find("#select_lang").val()), id_rule);

						// Verification between steps
						var checkStep = function (obj, context) {
							var $wizardContent = $body.find('#wizard');
							var $lang = $wizardContent.find('#select_lang');
							current_step = cleanInt(context.fromStep);
							next_step = cleanInt(context.toStep);

							$next_button.disable();

							if(next_step > 1) {
								$footer.find("#back-step").removeClass('hide');
							} else if (next_step === 1) {
								$footer.find("#back-step").addClass('hide');
								checkInputs($body.find('#rule_name'), $next_button);
								$body.find('#step-1 input').first().focus();
							}

							if(next_step < 5) {
								$footer.find("#next-step").removeClass('hide');
							} else {
								$footer.find("#next-step").addClass('hide');
							}

							if ((current_step === 2 && next_step === 3) || typeof(id_rule) === 'string') {
								$button.enable().show();
							}

							if (((current_step === 2 && next_step === 3) || (next_step >= 3 || current_step > 3)) && role === 'meta') {
								$next_button.enable();
								if (next_step === 3) {
									checkInputs($body.find('#meta_title'), $next_button);
								}
							}

							if ((current_step === 1 && next_step === 2) || (next_step === 2 && current_step > 2)) {
								checkRadio($body.find('#radios-0'), $body.find('#radios-1'), $next_button);
							}

							if (current_step === 2 && next_step === 3 && role === 'url') {
								$footer.find("#next-step").addClass('hide');
							}

							// Force first input focus
							if ($body.find('#link_rewrite').length) {
								$body.find('#link_rewrite').focus();
							}
							if ($body.find('#meta_title').length) {
								$body.find('#meta_title').focus();
							}
							if ($body.find('#fb_title').length) {
								$body.find('#fb_title').focus();
							}

							$step3_visible = $($body.find('#step-3')).is(":visible");
							if ($step3_visible === true) {
								selected_input = "#"+$body.find('#step-3 input').first().attr('id');
								$body.find('#step-3 input').focus(function() {
									selected_input = "#"+$(this).attr('id');
								});
							}

							$step4_visible = $($body.find('#step-4')).is(":visible");
							if ($step4_visible === true) {
								selected_input = "#fb_title";
								$body.find('#step-4 input').focus(function() {
									selected_input = "#"+$(this).attr('id');
								});
							}

							$step5_visible = $($body.find('#step-5')).is(":visible");
							if ($step5_visible === true) {
								selected_input = "#"+$body.find('#step-5 input').first().attr('id');
								$body.find('#step-5 input').focus(function() {
									selected_input = "#"+$(this).attr('id');
								});
							}

							// Navigation buttons (Back/Next/Save)
							$body.find("#next-step").unbind("click").on("click", function (e) {
								e.preventDefault();
								$wizardContent.smartWizard("goForward");
							});
							$body.find("#back-step").unbind("click").on("click", function(e) {
								e.preventDefault();
								$wizardContent.smartWizard("goBackward");
							});
							$body.find(".finish-step").unbind("click").on("click", function(e) {
								e.preventDefault();
								onFinishForm();
							});
						};

						// Show tooltip for helping merchant
						$body.find('.tooltips').tooltip({
							animation: false
						});

						$body.find('.tags_select a').click(function(e) {
							e.preventDefault();
							var $input_focus = $(selected_input);
							if (typeof(selected_input) === "undefined") {
								$input_focus = $body.find('#3-step input').first();
								selected_input = "#"+$body.find('#step-3 input').first().attr('id');
							}
							var input = $input_focus.focus();
							var value = $.trim($(this).attr('data-ref'));
							input.val(input.val() + value + ' ');
							checkInputs($body.find(selected_input), $next_button);
						});

						$body.find('.tags_select').hide();

						$body.find('.showlist').focusin(function() {
							if (typeof(timeout) !== 'undefined') {
								clearTimeout(timeout);
							}
							$body.find('.tags_select').show();
						}).focusout(function() {
							timeout = setTimeout(function() {
								$body.find('.tags_select').hide();
							}, 200);
						});


						// Start the wizard
						$body.find('#wizard').smartWizard({
							selected: 0,
							keyNavigation: false,
							enableAllSteps: (typeof(id_rule) === 'string'),
							onShowStep: checkStep
						});

						// Select picker
						$body.find('select.selectpicker').selectpicker();
						$body.find('button.selectpicker').each(function() {
							var select = $(this);
							select.on('click', function() {
								select.find('.bootstrap-select').addClass('open');
							});
							});

						// Find if is default rule
						$body.find('#select_lang').on('change', function() {
							isDefaultRule(cleanInt($(this).val()), id_rule);
						});

						// TWitter card
						$twitter_card.on('change', function() {
							if ($(this).val() !== '') {
								$('#tw_global').removeClass('hide');
								if ($(this).val() === 'product') {
									$('#tw_product').removeClass('hide');
								} else {
									$('#tw_product').addClass('hide');
								}
							} else {
								$('#tw_global').addClass('hide');
								$('#tw_product').addClass('hide');
								$('input[name*="tw_"]').val('');
							}
						});
						if ($twitter_card.val() !== '') {
							$body.find('#tw_global').removeClass('hide');
							if ($twitter_card.val() === 'product') {
								$body.find('#tw_product').removeClass('hide');
							}
						}
					},
					message: response_form,
					buttons: [
						// Next
						{
							id: 'next-step',
							label: next_message,
							cssClass: 'btn-default pull-right',
							action: function(dialogRef){
								var $body = dialogRef.getModalBody();
								var $wizardContent = $body.find('#wizard');
								$wizardContent.smartWizard("goForward");
							}
						},
						// Prev
						{
							id: 'back-step',
							label: prev_message,
							cssClass: 'btn-default pull-right',
							action: function(dialogRef){
								var $body = dialogRef.getModalBody();
								var $wizardContent = $body.find('#wizard');
								$wizardContent.smartWizard("goBackward");
							}
						},
						// Close
						{
							label: close_message,
							cssClass: 'btn-default pull-left',
							action: function(dialogRef){
								dialogRef.close();
							}
						},
						// Save
						{
							id: 'btn-save',
							label: save_message,
							cssClass: 'btn-primary pull-left',
							autospin: true,
							action: function(dialogRef) {
								dialogRef.enableButtons(false);
								dialogRef.setClosable(false);
								form_value = $("#form_add").serializeArray();
								dialogRef.getModalBody().hide();
								dialogRef.getModalFooter().hide();
								$.ajax({
									type: 'POST',
									url: admin_module_ajax_url,
									dataType: 'html',
									data: {
										controller : admin_module_controller,
										action : 'SaveRules',
										ajax : true,
										id_tab : current_id_tab,
										type: type,
										role: role,
										id_rule: id_rule,
										apply: 0,
										params: form_value
									},
									success: function(jsonData) {
										var $body = dialogRef.getModalBody();
										ps_version = cleanInt(ps_version);
										if (ps_version === 1) {
											error_exist = $(jsonData).find('.module_error').length;
											test_error = (error_exist === 0);
										} else {
											error_exist = $(jsonData).attr('class');
											test_error = (error_exist !== 'module_error alert error');
										}

										$body.show();
										if (test_error) {
											dialogRef.setClosable(true);
											$body.html(jsonData);
											reloadTable(table);
										} else {
											dialogRef.enableButtons(true);
											dialogRef.getModalFooter().show();
											error_already_exist = $body.find('.module_error').length;
											error_already_exist = cleanInt(error_already_exist);
											if (error_already_exist === 0) {
												$(jsonData).insertBefore($body.find('#response_form'));
											}
										}

										if(debug_mode === 0) {
											setTimeout(function(){
												dialogRef.close();
											}, 1000);
										}
									}
								});
							}
						}
					]
				});
			}
		});
	};

	/**
	** Load Modal for delete SEO Rule
	*/
	var loadDeleteModal = function (id, table) {
		var loader = '<div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></div>';
		var reload = '<div class="bootstrap-dialog-message"><span>Thank you for your patience during the removal process rules</span>'+loader+'</div>';
		BootstrapDialog.show({
			sizeModal: 'SIZE_XLARGE',
			message: '<span>'+delete_rule_message+'</span>',
			buttons: [{
				icon: 'icon-trash',
				label: delete_message,
				cssClass: 'btn-primary',
				autospin: true,
				action: function(dialogRef) {
					dialogRef.enableButtons(false);
					dialogRef.setClosable(false);
					dialogRef.getModalFooter().hide();
					dialogRef.getModalBody().html(reload);
					$.ajax({
						type: 'POST',
						url: admin_module_ajax_url,
						dataType: 'html',
						data: {
							controller : admin_module_controller,
							action : 'DeleteRules',
							ajax : true,
							id_tab : current_id_tab,
							id : id
						},
						success: function(jsonData) {
							dialogRef.setClosable(true);
							dialogRef.getModalBody().children().children().next().children().css('width', '100%');
							dialogRef.getModalBody().children().children().next().children().attr('aria-valuenow', '100');
							reloadTable(table);
							if (debug_mode === 0) {
								setTimeout(function(){
									dialogRef.close();
								}, 2000);
							}
						},
						error: function (jqXHR, textStatus, errorThrown) {
							dialogRef.setClosable(true);
							dialogRef.getModalBody().children().children().next().children().addClass('progress-bar-danger');
							dialogRef.getModalBody().children().children().next().children().css('width', '100%');
							dialogRef.getModalBody().children().children().next().children().attr('aria-valuenow', '100');
							if(debug_mode === 0) {
								setTimeout(function(){
									dialogRef.close();
								}, 2000);
							}
						}
					});
				}
			}, {
				label: close_message,
				action: function(dialogRef){
					dialogRef.close();
				}
			}]
		});
	};

	/**
	** Load Modal for delete SEO Rule
	*/
	var loadGenerateModal = function (id_rule, id_table) {
		var loader = '<div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></div>';
		var reload = '<div class="bootstrap-dialog-message"><span id="msg_progress">Thank you for your patience during the apply process rules</span>'+loader+'<div class="render_seo"></div></div>';

		BootstrapDialog.show({
			sizeModal: 'SIZE_XLARGE',
			onshow: function(dialogRef) {
				var $body = dialogRef.getModalBody();
				$body.html(reload);
			},
			onshown: function(dialogRef) {
				var $body = dialogRef.getModalBody();
				if (typeof(id_rule) === 'string') {
					generateRule(id_rule, 0, $body, dialogRef, id_table, 1);
				} else {
					$(id_rule).each(function(key, id) {
						generateRule(id, key, $body, dialogRef, id_table, 1);
					});
				}
			},
			buttons: [{
				label: close_message,
				action: function(dialogRef){
					dialogRef.close();
				}
			}]
		});
	};

	/**
	**	Generater rule for a given category
	**	One rule for rule them all !
	*/
	var generateRule = function (id_rule, index, zone, dialogRef, id_table, page, batch) {
		// resetProgress(id_rule, zone);
		page = page || 1;
		batch = batch || 0;
		$.ajax({
			type: 'POST',
			url: admin_module_ajax_url,
			dataType: 'json',
			async: true,
			cache: false,
			data: {
				action : 'GenerateRule',
				ajax : true,
				id_tab : current_id_tab,
				id_rule : id_rule,
				page : page,
				batch : batch,
			},
			success : function(data) {
				// Update progress bar
				getProgress(data, zone);

				if (page < data.max_pages) {
					page = page + 1;
					generateRule(id_rule, index, zone, dialogRef, id_table, page, data.batch);
				}
				else {
					var row = zone.find('.render_seo').html(data.message);
				reloadTable(id_table);
				if(debug_mode === 0) {
					setTimeout(function(){
						dialogRef.close();
					}, 2000);
				}
			}
			}
		});
	};

	/**
	** Display progress of the generation of rules
	*/
	var getProgress = function(data, zone) {
		calc = cleanInt(data.pourcent);

				if (calc > 0) {
					zone.find('.progress-bar').html(calc+'%').css('width', calc+'%').attr('aria-valuenow', calc);
				}

				if (calc === 100) {
					zone.find('#msg_progress').fadeOut("slow");
					zone.find('#msg_progress').next().fadeOut("slow");
			}
	};


	/**
	** Delete all rules
	*/
	var cleanUp = function () {
		BootstrapDialog.show({
			sizeModal: 'SIZE_NORMAL',
			message: '<span>'+delete_rule_message+'</span>',
			buttons: [{
				icon: 'icon-trash',
				label: delete_message,
				cssClass: 'btn-primary',
				autospin: true,
				action: function(dialogRef) {
					dialogRef.enableButtons(false);
					dialogRef.setClosable(false);
					dialogRef.getModalBody().html('Ok');
					dialogRef.getModalFooter().hide();
					$.ajax({
						type: 'POST',
						url: admin_module_ajax_url,
						dataType: 'html',
						data: {
							controller : admin_module_controller,
							action : 'cleanUp',
							ajax : true
						},
						success : function(data) {
							location.reload();
						}
					});
				}
			}, {
				label: close_message,
				action: function(dialogRef){
					dialogRef.close();
				}
			}]
		});
	};

	/**
	** Click Event
	*/
	var runEvent = function () {
		// Click on Edit button
		$('.edit').live('click', function (e) {
			e.preventDefault();
			var id = $(this).attr('role-id');
			var role = $(this).attr('data-role');
			var type = $(this).attr('data-type');
			var table = $(this).closest("table").attr('id');
			loadFormModal(id, role, type, table);
		});

		// Click on Delete button
		$('.delete').live('click', function (e) {
			e.preventDefault();
			var id = $(this).attr('role-id');
			var table = $(this).closest("table").attr('id');
			loadDeleteModal(id, table);
		});

		// Click on Generate button
		$('.generate').live('click', function (e) {
			e.preventDefault();
			var id = $(this).attr('role-id');
			var table_id = $(this).parents("table").attr('id');
			loadGenerateModal(id, table_id);
		});

		// Click on State button
		$('.action-enabled, .action-disabled').live('click', function (e) {
			e.preventDefault();
			var table_id = $(this).parents("table").attr('id');
			var cat_id = $(this).parents("tr").attr('id');
			id = cat_id.replace('cat_', '');
			$.ajax({
				type: 'POST',
				url: admin_module_ajax_url,
				dataType: 'html',
				data: {
					controller : admin_module_controller,
					action : 'SwitchAction',
					ajax : true,
					id_tab : current_id_tab,
					id_rule : id
				},
				success : function() {
					reloadTable(table_id);
				}
			});
		});

		// Click on ToolsBar button
		$('.list-toolbar-btn').live('click', function (e) {
			e.preventDefault();
			var table = '';
			var role = $(this).attr('data-role');
			var type = $(this).attr('data-type');
			if (type !== 'generate') {
				table = $(this).attr('id');
				table = table.replace('configuration-'+role+'s-', 'table-'+role+'s-');
				loadFormModal(null, role, type, table);
			} else if (type === 'generate') {
				table = $.trim($(this).attr('id'));
				mytabe = table.replace('generate', 'table');
				$tr = $('#'+mytabe+' tbody tr');
				tab = new Array();
				$tr.each(function(index, value) {
					cat = value.id;
					cat = cat.replace('cat_', '');
					active = $.trim($(this).attr('data-active'));
					if (cleanInt(active) === 1) {
						tab.push(cat);
					}
				});
				if (tab.length > 0) {
					loadGenerateModal(tab, mytabe);
				}
			}
		});

		// Click on Add or Apply button
		$('.panel-footer a').live('click', function (e) {
			e.preventDefault();
			var role = $(this).attr('data-role');
			var type = $(this).attr('data-type');
			var table = $(this).closest( "div" ).attr('id');
			if (type !== 'generate') {
				loadFormModal(null, role, type, table);
			} else if (type === 'generate') {
				$tr = $('#'+table+' tbody tr');
				tab = new Array();
				$tr.each(function(index, value) {
					cat = value.id;
					cat = cat.replace('cat_', '');
					active = $.trim($(this).attr('data-active'));
					if (cleanInt(active) === 1) {
						tab.push(cat);
					}
				});
				if (tab.length > 0) {
					loadGenerateModal(tab, table);
				}
			}
		});

		// Click on Panel
		$('#modulecontent .tab-content h3 a').live('click', function (e) {
			e.preventDefault();
			var collapse = $(this).attr('data-toggle');
			if (typeof(collapse) !== "undefined" && collapse === 'collapse') {
				var id = $(this).attr('href');
				id = id.replace('#', '');
				id = id.replace('metas', 'meta');
				var is_collapse = false;
				var table_id = '#table-'+id;

				$(this.attributes).each(function() {
					if (this.nodeName === 'class') {
						if(this.nodeValue === '') {
							is_collapse = true;
						}
					}
				});

				if ($(this).attr('class') === undefined) {
					is_collapse = true;
				}

				if(is_collapse) {
					reloadTable(table_id);
				}
			}
		});


		$(".contactus").on('click', function() {
			$href = $.trim($(this).attr('href'));
			$(".list-group a.active").each(function() {
				$(this).removeClass("active");
			});

			$(".list-group a.contacts").addClass("active");
		});

		// Tab panel active
		$(".list-group-item").on('click', function() {
			var $el = $(this).parent().closest(".list-group").children(".active");
			var tab_id = $(this).attr('id');
			if (tab_id === 'drop') {
				$(this).removeClass("active");
				cleanUp();
			} else {
				if ($el.hasClass("active")) {
					target = $(this).find('i').attr('data-target');
					if (target !== undefined) {
						loadTable('#'+target);
					}
					$el.removeClass("active");
					$(this).addClass("active");
				}
			}
		});
	};

	/**
	** Custom Elements
	*/
	var runCustomElement = function () {
		// Hide ugly toolbar
		$('table[class="table"]').each(function() {
			$(this).hide();
			$(this).next('div.clear').hide();
		});

		// Hide ugly multishop select
		if (typeof(_PS_VERSION_) !== 'undefined') {
			var version = _PS_VERSION_.substr(0,3);
			if(version === '1.5') {
				$('.multishop_toolbar').addClass("panel panel-default");
				$('.shopList').removeClass("chzn-done").removeAttr("id").css("display", "block").next().remove();
				cloneMulti = $(".multishop_toolbar").clone(true, true);
				$(".multishop_toolbar").first().remove();
				cloneMulti.find('.shopList').addClass('selectpicker show-menu-arrow').attr('data-live-search', 'true');
				cloneMulti.insertBefore("#modulecontent");
				// Copy checkbox for multishop
				cloneActiveShop = $.trim($('table[class="table"] tr:nth-child(2) th').first().html());
				$(cloneActiveShop).insertAfter("#tab_translation");
			}
		}

		// Custom Select
		$('.selectpicker').selectpicker();

		// Fix bug form builder + bootstrap select
		$('.selectpicker').each(function(){
			var select = $(this);
			select.on('click', function() {
				$(this).parents('.bootstrap-select').addClass('open');
				$(this).parents('.bootstrap-select').toggleClass('open');
			});
		});

		// Show tooltip for helping merchant
		$('a').tooltip();

		// Custom Textarea
		$('.textarea-animated').autosize({
			append: "\n"
		});
	};

	return {
		init: function () {
			runEvent();
			runCustomElement();
		}
	};
}();

// Load functions
$(window).load(function() {
	Main.init();
});

$ = tmp;