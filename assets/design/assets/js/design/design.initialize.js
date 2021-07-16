;(function( $ ){
	
	if(getQueryVariable('structure')){
		rsAjax({
			data: {
				action: 'design',
				structure: getQueryVariable('structure')
			},
			success: function(specs){
				var setup = JSON.parse(specs),
					sections = setup.sections,
					specs = setup.specs,
					version = setup.version,
					entries = setup.entries;
					calculations = setup.calculations;

				$('#wrapper').design({
					sections: sections,
					specs: specs,
					version: version,
					entries: entries,
					calculations: calculations
				});	
			}
		});
	} else {
		rsAjax({
			data: {
				action: 'companies',
			},
			success: function(specs){
				var setup = JSON.parse(specs),
					companies = setup.companies;
	
				$('#wrapper').roishopDesign({
					companies: companies
				});	
			}
		});
	}
	
})( window.jQuery || window.Zepto );

if (typeof(numeral) === 'undefined') {
	numeral = undefined;
}

if(typeof(moment) == 'undefined'){
	moment = undefined;
}

;(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
    typeof define === 'function' && define.amd ? define(factory) :
    global.design = factory();
}(this, (function () {

	'use strict';
	var design = (function(el, options) {
		var calc = {},
			defaults = {},
			elements = {
				$topNavigation: $('<div class="row bottom-border"></div>')
			};

		calc = $.extend(true, elements);
		calc.options = $.extend(true, options, defaults);
		
		calc.el = $(el);
		calc.sections = [];
		calc.entries = [];
		calc.sectionEntries = [];
		calc.cells = [];

		calc.init = function(){
			design.current = calc;

			calc.navigation();
			calc.version();
			calc.sections();
			calc.calculations();
		}

		calc.navigation = function(){
			calc.el.append(calc.$topNavigation);
	
			$('h3.template-name').html('Current Title from Jquery');
			
			calc.$pageWrapper = $('<div id="page-wrapper"></div>');
			calc.el.append(calc.$pageWrapper);
		}

		calc.version = function(){
			var $template = $([
				'<div class="row border-bottom white-bg dashboard-header">',
					'<div class="col-lg-12">',
						sprintf('<h1 style="margin-bottom: 20px;">%s Specifics</h1>', 'Tempate'),
					'</div>',
				'</div>',
				'<div class="row border-bottom gray-bg dashboard-header">',
					'<div class="row">',
						'<div class="col-lg-12">',
							'<div class="ibox">',
								'<div class="ibox-content">',
									'<div class="form-group row">',
										'<label class="control-label col-lg-5">Template Name:</label>',
										'<div class="col-lg-7">',
											sprintf('<input template value="%s" class="form-control">', calc.options.version.version_name),
										'</div>',
									'</div>',
									'<div class="form-group row">',
										'<label class="control-label col-lg-5">Return Period:</label>',
										'<div class="col-lg-7">',
											sprintf('<input return value="%s" class="form-control">', calc.options.specs.retPeriod),
										'</div>',
									'</div>',
									'<div class="form-group">',
										'<div class="col-lg-12">',
											'<div class="float-right">',
												'<p>',
													'<button delete type="button" class="btn btn-danger">Delete Template</button>',
													'<button update type="button" class="btn btn-primary">Update Template</button>',
												'</p>',
											'</div>',
										'</div>',
									'</div>',
									'<div style="clear: both;"></div>',
								'</div>',
							'</div>',
						'</div>',
					'</div>',
				'</div>'
			].join(''));

			calc.$pageWrapper.append($template);
			
			$template.find('[update]').off('click').on('click', function(){
				var template = $template.find('input[template]').val();
				var returnPeriod = $template.find('input[return]').val();

				rsAjax({
					type: "POST",
					data: {
						action: "updateTemplate",
						template: template,
						returnPeriod: returnPeriod,
						id: getQueryVariable('structure')
					}
				})
			})
		}

		calc.buildSectionList = function(){
			calc.$sectionsList = calc.$sections.find('[section-list]');

			if(calc.$sectionsList.data('nestable')){
				calc.$sectionsList.nestable('destroy');
				calc.$sectionsList.empty();
			};
			calc.$sectionsList.append($('<ol class="dd-list"></ol>'));

			for(var i in calc.sections){
				var section = calc.sections[i];

				calc.sectionListItem(section);
				calc.buildSection(section);
			}

			calc.$sectionsList.nestable({
				callback: function(l, e){
					var order = calc.$sectionsList.nestable('serialize');

					$.each(order, function(i, item){
						calc.sections['section:' + item.id].Position = i;
	
						rsAjax({
							type: "POST",
							data: {
								action: "updateSectionPosition",
								position: i,
								section: item.id
							}
						})
					});
				}
			});
		}

		calc.sectionListItem = function(section, count){
			var $section = $(sprintf(
				'<li class="dd-item" data-id="%s">\
					<div class="form-group" style="margin-bottom: 0;">\
						<div class="dd-handle dd3-handle">Drag</div>\
						<div class="dd3-content">\
							<div class="col-lg-12"><i class=\"fa fa-pencil edit-section\"></i> <span section-title>%s</span></div>\
						</div>\
					</div>\
				</li>', section.ID, section.Title));

			calc.$sectionsList.find('ol').append($section);

			if(section.new){
				$section.hide().fadeIn();
				section.new = false;
			}

			var sectionEdit = function(){
				
				var editor = $section.find('[edit]');
				if(editor.length) {
					editor.remove();
					return;
				}
				
				var $edit = $(sprintf(
					'<div edit>\
						<div class="form-group row">\
							<label class="control-label col-lg-5">Section Name:</label>\
							<div class="col-lg-7">\
								<textarea rows="1" style="resize: vertical;" title class="form-control">%s</textarea>\
							</div>\
						</div>\
						<div class="form-group row">\
							<label class="control-label col-lg-5">Section Writeup:</label>\
							<div class="col-lg-7">\
								<div class="form-group" writeup>%s</div>\
							</div>\
						</div>\
						<div class="form-group row">\
							<label class="control-label col-lg-5">Section Video:</label>\
							<div class="col-lg-7">\
								<input video value="%s" class="form-control">\
							</div>\
						</div>\
						<div class="form-group row">\
							<label class="control-label col-lg-5">Section Formula:</label>\
							<div class="col-lg-7">\
								<input formula value="%s" class="form-control">\
							</div>\
						</div>\
						<div class="form-group">\
							<div class="col-lg-12">\
								<div class="float-right">\
									<a delete type="button" class="btn btn-danger">Delete Section</a>\
									<a update type="button" class="btn btn-primary">Update Section</a>\
								</div>\
							</div>\
						</div>\
						<div style="clear: both;"></div>\
					</div>', section.Title ? section.Title : '', section.Caption ? section.Caption : '', section.Video ? section.Video : '', section.formula ? section.formula : ''));

				$edit.find('[writeup]').summernote();
				$section.append($edit);

				$edit.find('[update]').off('click').on('click', function(){
					
					var title = $edit.find('textarea[title]').val(),
						writeup = $edit.find('[writeup]').summernote('code'),
						video = $edit.find('input[video]').val(),
						formula = $edit.find('input[formula]').val(),
						id = section.ID;
						
					rsAjax({
						type: "POST",
						data: {
							action: "updateSection",
							title: title,
							writeup: writeup,
							video: video,
							formula: formula,
							id: id
						},
						success: function(){
							$section.find('[section-title]').fadeOut(function(){
								$(this).text(title);
							}).fadeIn();

							section.Title = title;
							section.Caption = writeup;
							section.Video = video;
							section.formula = formula;
						}
					})
				});
				
				$edit.find('[delete]').off('click').on('click', function(){
					var $modal = $('<div></div>');
					
					$modal.rsmodal({
						header: {
							content: [{
								html:
									'<h2>\
										Delete ' + section.Title + '?\
										<small class="font-bold"><br/><span style=\"color: white;\">This action cannot be undone.</span></small>\
									</h2>'
							}]
						},
						body: {
							content: [{
								attributes: {
									class: 'form-group'
								},
								children: [{
									html: '<h4>Are you sure you want to delete the section ' + section.Title + '? This action cannot be undone.</h4><hr/>'
								}]
							},{
								attributes: {
									style: "float:right;"
								},
								children:[{
									tag: "a",
									attributes: {
										type: "button",
										class: "btn btn-danger",
									},
									actions: {
										click: function(){
											rsAjax({
												type: 'POST',
												data: {
													action: "deleteSection",
													section: section.ID
												},
												success: function(returned){
													toastr.info(section.Title + 'was successfully deleted');
													$modal.rsmodal('close');

													$section.fadeOut(function(){
														$section.remove();
													});
												}
											});
										}
									},
									children:[{
										html: "Delete " + section.Title
									}]
								},{
									tag: "a",
									attributes: {
										type: "button",
										class: "btn btn-white",
									},
									actions: {
										click: function(){
											$modal.rsmodal('close');
										}
									},
									children:[{
										html: "Close"
									}]
								}]
							},{
								attributes: {
									"style": "clear: both;"
								}
							}]
						}
					});
				})
			}

			$section.find('.edit-section').off('click').on('click', sectionEdit);
		}

		calc.sections = function(){
			calc.sections = [];

			$.each(calc.options.sections, function(i, section){
				calc.sections['section:' + section.ID] = section;
			});

			calc.$sections = $([
				'<div class="row border-bottom white-bg dashboard-header">',
					'<div class="col-lg-12">',
						'<h1 style="margin-bottom: 20px;">Current Sections</h1>',
					'</div>',
				'</div>',
				'<div class="row border-bottom gray-bg dashboard-header">',
					'<div class="row">',
						'<div class="col-lg-12">',
							'<div class="ibox">',
								'<div class="ibox-content">',
									'<div section-list class="dd">',
									'</div>',
								'</div>',
							'</div>',
						'</div>',
					'</div>',
				'</div>'
			].join(''));

			calc.$pageWrapper.append(calc.$sections);
			calc.buildSectionList();

			var $newBtn = $('<div style="margin-top: 10px; float: right;"><a add type="button" class="btn btn-primary">Add New Section</a></div><div style="clear: both;"></div>');
			calc.$sections.find('.ibox-content').append($newBtn);

			$newBtn.find('[add]').off('click').on('click', function(){

				var position = Object.keys(calc.sections).length;
				rsAjax({
					type: "POST",
					data: {
						action: "addNewSection",
						structure: calc.options.specs.compID,
						title: "New Section",
						position: position
					},
					success: function(id){
						var section = [];

						section.ID = id;
						section.Title = "New Section";
						section.Position = position;
						section.new = true;

						calc.sections['section:' + section.ID] = section;
						calc.buildSectionList();
					}
				})
			});
		}

		calc.createSectionWithHeader = function(options){
			var $element = $([
				'<div class="row border-bottom white-bg dashboard-header">',
					'<div class="col-lg-12">',
						sprintf('<h1 style="margin-bottom: 20px;">%s</h1>', options.Title),
					'</div>',
				'</div>',
				'<div class="row border-bottom gray-bg dashboard-header">',
					'<div class="row">',
						'<div class="col-lg-12">',
							'<div class="ibox">',
								'<div class="ibox-content">',
								'</div>',
							'</div>',
						'</div>',
					'</div>',
				'</div>'
			].join(''));

			return $element;
		}

		calc.buildSection = function(section){
			var sectionEntryId = 'sectionEntryId:' + section.ID;
			calc.sectionEntries[sectionEntryId] = section;

			var $header = calc.createSectionWithHeader(section);

			calc.$pageWrapper.append($header);
			section.$header = $header;

			$.each(section.entries, function(i, entry){
				var entryId = 'entryId:' + entry.ID;
				calc.entries[entryId] = entry;
			});

			calc.buildSectionEntries(section);
		}

		calc.buildEntryItem = function(entry_data, $entryList){
			var dd_item_tag,
				dd_type,
				dd_format,
				dd_address,
				entry = $.extend(true, entry_data, []);

			switch(entry.Type){
				case '0':
				case 'input':
					dd_type = 'input';
				break;

				case '1':
				case 'output':
					dd_type = 'output';
				break;

				case '2':
				case 'textarea':
					dd_type = 'textarea';
				break;

				case '3':
				case 'dropdown':
					dd_type = 'dropdown';
				break;

				case '11':
				case 'slider':
					dd_type = 'slider';
				break;

				case '13':
				case 'header':	
					dd_type = 'header';
				break;

				case 'text':
					dd_type = 'text';

				default:
					dd_item_tag = 'Not an input';
				break;				
			}

			dd_item_tag = entry.Title.replaceAll('<', '&lt;');
			dd_item_tag = dd_item_tag.replaceAll('>', '&gt;');

			if(entry.Format == 1 || entry.Format == 2){
				dd_format = '0,0';

				if(entry.precision > 0){
					dd_format += '.';

					for(var i=0; i<entry.precision; i++){
						dd_format += '0';
					}
				}
			}

			switch(entry.Format){
				case '0':
					dd_format = 0;
				break;

				case '1':
					dd_format = '$' + dd_format;
				break;

				case '2': 
					dd_format += '%';
				break;

				default:
					dd_format = entry.Format;
				break;
			}

			dd_address = entry.address ? entry.address : 'A' + entry.ID;

			var $entry = $(sprintf(
				'<li class="dd-item" data-id="%s">\
					<div class="form-group" style="margin-bottom: 0;">\
						<div class="dd-handle dd3-handle">Drag</div>\
						<div class="dd3-content">\
							<div class="col-lg-12">\
								<span class="col-lg-12" style="white-space: nowrap; overflow: hidden;" entry-title><i class=\"fa fa-pencil edit-entry\"></i> %s</span>\
						</div>\
					</div>\
				</li>', entry.ID, dd_item_tag));

			$entryList.find('ol').append($entry);
			
			var entryEdit = function(){

				var $modal = $('<div/>');

				var $id = $(
							sprintf(
								'<div class="form-group">\
									<label class="control-label col-lg-3">Auto ID:</label>\
									<div class="col-lg-9">\
										<input disabled="disabled" value="%s" class="form-control">\
									</div>\
								</div>', entry.ID
							)
						);
				
				var $content = $(
						sprintf(
							'<div class="form-group">\
								<label class="control-label col-lg-3">Title:</label>\
								<div class="col-lg-9">\
									<div class="form-group">%s</div>\
								</div>\
							</div>', entry.Title ? entry.Title : ''
						)		
					);

					var subsectionHeader = function (context){
						var ui = $.summernote.ui;
	
						var button = ui.button({
							contents: 'Sub',
							tooltip: 'Add a Subsection Header',
							click: function() {
								context.invoke(
									'editor.pasteHTML', 
									'<h2 class="subsection">Change Header Here</h2>'
								);
							}
						});
	
						return button.render();
					}

				var includeExclude = function (context){
					var ui = $.summernote.ui;

					var button = ui.button({
						contents: 'Inc',
						tooltip: 'Add an Include/Exclude Section',
						click: function() {
							context.invoke(
								'editor.pasteHTML', 
								sprintf( '<div class="subsection-header underlined" style="margin: 15px 0 15px 0">\
									<div class="row">\
										<h2 class="col-lg-10">Rename header here</h2>\
										<div class="col-lg-2">\
											<button rs-type="toggle" \
												class="btn btn-block btn-primary" \
												calc-id="%s" \
												data-states=\'[{"value":0,"class":"btn-danger","text":"<i class=\\\"fa fa-times\\\"></i> Excluded"},{"value":1,"class":"btn-primary","text":"<i class=\\\"fa fa-check\\\"></i> Included"}]\'></button>\
										</div>\
									</div>\
								</div>', entry.address ? entry.address : 'A' + entry.ID )
							);
						}
					});

					return button.render();
				}

				$content
					.find('div.form-group')
					.summernote({
						dialogsInBody: true,
						toolbar: [
							['para', ['style', 'ul', 'ol', 'paragraph']],
							['style', ['bold', 'italic', 'clear']],
							['insert', ['link', 'picture', 'video']],
							['table', ['table']],
							['include', ['includeExclude', 'subsectionHeader']],
							['view', ['codeview']]
						],
						buttons: {
							includeExclude: includeExclude,
							subsectionHeader: subsectionHeader
						},
						callbacks: {
							onChange: function(contents){
								entry.Title = contents;
							}
						}
					});

				var $type = $(
					'<div class="form-group">\
						<label class="control-label col-lg-3">Type:</label>\
						<div class="col-lg-9">\
							<select class="form-control">\
								<option></option>\
								<option value="input">Input</option>\
								<option value="output">Output</option>\
								<option value="textarea">Textarea</option>\
								<option value="dropdown">Dropdown</option>\
								<option value="slider">Slider</option>\
								<option value="header">Header</option>\
								<option value="text">HTML</option>\
							</select>\
						</div>\
					</div>'
				);

				$type.find('select').chosen({
					width: '100%',
					disable_search_threshold: 10
				}).val(dd_type).trigger('chosen:updated');
				
				$type.find('select').off('change').on('change', function(){ entry.Type = $(this).val(); })

				var $choices = $(
					'<div class="form-group">\
						<label class="control-label col-lg-3">Choices:</label>\
						<div class="col-lg-9" style="text-align: right;">\
							<button class="btn btn-primary">Add New Choice</button>\
						</div>\
					</div>'						
				)

				$choices.find('button').off('click').on('click', function(e){
					e.preventDefault();

					var $modal = $('<div/>'),
						choice = {};

					var $text = $(
						'<div class="form-group">\
							<label class="control-label col-lg-3">Text:</label>\
							<div class="col-lg-9">\
								<input class="form-control">\
							</div>\
						</div>'
					);

					$text.find('input').off('change').on('change', function(){
						choice.value = $(this).val();
					});

					var $value = $(
						'<div class="form-group">\
							<label class="control-label col-lg-3">Value:</label>\
							<div class="col-lg-9">\
								<input class="form-control">\
							</div>\
						</div>'
					);

					$value.find('input').off('change').on('change', function(){
						choice.dropdown_value = $(this).val();
					});

					var $show = $(
							'<div class="form-group">\
								<label class="control-label col-lg-3">Elements to Show:</label>\
								<div class="col-lg-9">\
									<textarea rows="2" class="form-control"></textarea>\
								</div>\
							</div>'		
						);
	
					var mentions = $.map(calc.options.entries, function(n, i){
						var title = n.Title,
							mention = {};

							title = title.replaceAll('<', '&lt;');
							title = title.replaceAll('>', '&gt;');

							title = title.length > 75 ? title.substring(0, 75) + '...' : title;

							mention.key = title;
							mention.value = n.ID;
						
						return mention;
					});

					var tribute = new Tribute({
						trigger: '#',
						values: mentions
					});

					tribute.attach($show.find('textarea')[0]);

					var $confirm = $('<button type="button" class="btn btn-primary">Add Choice</button>');
					var $cancel = $('<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>');

					var $submit = 
					$(
						'<p style="margin: 0; text-align: right;"></p>'	
					);

					$submit
						.append($confirm)
						.append($cancel);

					$modal
						.rsmodal({
							size: 'modal-sm',
							stacked: true,
							header: {
								content: [{
									html:
										'<h2 class="modal-title">Add New Choice</h2>'
								}]
							},
							body: {
								content: '<form class="form-horizontal"></form>'
							}
						});
				
					$modal
						.find('.form-horizontal')
						.append($text)
						.append($value)
						.append($show)
						.append('<hr style="border-top: 1px solid #bbb">')
						.append($submit);

					$confirm.off('click').on('click', function(){
						choice.show_map = $show.find('textarea').val();

						rsAjax({
							type: "POST",
							data: {
								action: "addNewChoice",
								text: choice.value,
								value: choice.dropdown_value,
								show: choice.show_map,
								entry: entry.ID
							},
							success: function(choice){	
								toastr.info('Choice successfully added');
							}
						});								
					})
				});

				var $format = $(
					sprintf(
						'<div class="form-group">\
							<label class="control-label col-lg-3">Format:</label>\
							<div class="col-lg-9">\
								<input value="%s" class="form-control">\
							</div>\
						</div>', dd_format
					)					
				);

				$format.find('input').off('change').on('change', function(){ entry.Format = $(this).val() });

				var $tooltip = $(
						sprintf(
							'<div class="form-group">\
								<label class="control-label col-lg-3">Tooltip:</label>\
								<div class="col-lg-9">\
									<div class="form-group">%s</div>\
								</div>\
							</div>', entry.Tip ? entry.Tip : ''
						)		
					);

				$tooltip
					.find('div.form-group')
					.summernote({
						hint: {
							mentions: ['jayden', 'sam', 'alvin', 'david'],
							match: /\B@(\w*)$/,
							search: function(keyword, callback){
								callback($.grep(this.mentions, function(item){
									return item.indexOf(keyword) == 0;
								}));
							},
							content: function(item){console.log(item);
								return '@' + item;
							}
						},
						dialogsInBody: true,
						callbacks: {
							onChange: function(contents){
								entry.Tip = contents.replaceAll('"', '&quot;');
							}
						}
					});

				var $append = $(
					sprintf(
						'<div class="form-group">\
							<label class="control-label col-lg-3">Append:</label>\
							<div class="col-lg-9">\
								<input value="%s" class="form-control">\
							</div>\
						</div>', entry.append ? entry.append : ''
					)					
				);

				$append.find('input').off('change').on('change', function(){ entry.append = $(this).val() });

				var $formula = $(
					sprintf(
						'<div class="form-group">\
							<label class="control-label col-lg-3">Formula:</label>\
							<div class="col-lg-9">\
								<textarea rows="2" class="form-control">%s</textarea>\
							</div>\
						</div>', entry.formula ? entry.formula : ''
					)					
				);

				$formula.find('textarea').off('change').on('change', function(){ entry.formula = $(this).val() });

				var mentions = $.map(calc.options.entries, function(n, i){
					var title = n.Title,
						mention = {};

						title = title.replaceAll('<', '&lt;');
						title = title.replaceAll('>', '&gt;');

						title = title.length > 75 ? title.substring(0, 75) + '...' : title;

						mention.key = title;
						mention.value = n.address ? n.address : 'A' + n.ID;
					
					return mention;
				});

				var tribute = new Tribute({
					trigger: '@',
					values: mentions,
					selectTemplate: function(item){console.log(item);
						return item.original.value;
					}
				});

				tribute.attach($formula.find('textarea')[0]);

				var $address = $(
					sprintf(
						'<div class="form-group">\
							<label class="control-label col-lg-3">Address:</label>\
							<div class="col-lg-9">\
								<input value="%s" class="form-control">\
							</div>\
						</div>', entry.address ? entry.address : ''
					)					
				);

				$address.find('input').off('change').on('change', function(){ entry.address = $(this).val() });

				var $submit = 
					$(
						'<p style="margin: 0; text-align: right;">\
						</p>'	
					);
				
				var $confirm = $('<button type="button" class="btn btn-primary">Update Entry</button>');
				var $delete = $('<button type="button" class="btn btn-danger">Delete</button>');
				var $cancel = $('<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>');

				$confirm.off('click').on('click', function(){
					rsAjax({
						type: "POST",
						data: {
							action: "updateEntry",
							title: entry.Title,
							type: entry.Type,
							format: entry.Format,
							tooltip: entry.Tip,
							address: entry.address,
							append: entry.append,
							formula: entry.formula,
							rules: entry.rules,
							entry: entry.ID
						},
						success: function(){
							$entry.find('[entry-title]').fadeOut(function(){
								$(this).text(entry.Title);
							}).fadeIn();

							toastr.info('Entry successfully updated');
						}
					});
				})

				$submit
					.append($confirm)
					.append($delete)
					.append($cancel);

				$modal
					.rsmodal({
						size: 'modal-lg',
						header: {
							content: [{
								html:
									'<h2 class="modal-title">Update Entry</h2>'
							}]
						},
						body: {
							content: '<form class="form-horizontal"></form>'
						}
					});
				
				var $choices_list = '';
				$choices_list = $(
					'<div class="form-group">\
					</div>'					
				);

				$modal
					.find('.form-horizontal')
					.append($id)
					.append($content)
					.append($type)
					.append($choices)
					.append($choices_list)
					.append($format)
					.append($tooltip)
					.append($append)
					.append($formula)
					.append($address)
					.append('<hr style="border-top: 1px solid #bbb">')
					.append($submit);

				var buildEntryChoices = function(entry) {
					if(entry.choices && entry.choices.length){
	
						var $list = $(
							'<div class="dd" style="margin: 0 15px;">\
								<ol class="dd-list">\
								</ol>\
							</div>'
						);
						
						$.each(entry.choices, function(){
							var choice = this;
							var $choice = $(sprintf(
								'<li class="dd-item" data-id="%s">\
									<div style="margin-bottom: 0;">\
										<div class="dd-handle dd3-handle">Drag</div>\
										<div class="dd3-content">\
											<div class="col-lg-12">\
												<span choice-title>%s</span><span class="float-right"><a class="edit-choice">Edit</a></span>\
											</div>\
										</div>\
									</div>\
								</li>', choice.id, choice.value));						
							
							$list.find('ol').append($choice);
	
							$choice.find('.edit-choice').off('click').on('click', function(){
								var $modal = $('<div/>');
	
								var $text = $(
									sprintf(
										'<div class="form-group">\
											<label class="control-label col-lg-3">Text:</label>\
											<div class="col-lg-9">\
												<input value="%s" class="form-control">\
											</div>\
										</div>', choice.value
									)
								);
	
								$text.find('input').off('change').on('change', function(){
									choice.value = $(this).val();
								});
	
								var $value = $(
									sprintf(
										'<div class="form-group">\
											<label class="control-label col-lg-3">Value:</label>\
											<div class="col-lg-9">\
												<input value="%s" class="form-control">\
											</div>\
										</div>', choice.dropdown_value
									)
								);
	
								$value.find('input').off('change').on('change', function(){
									choice.dropdown_value = $(this).val();
								});
	
								var $show = $(
										sprintf(
											'<div class="form-group">\
												<label class="control-label col-lg-3">Elements to Show:</label>\
												<div class="col-lg-9">\
													<textarea rows="2" class="form-control">%s</textarea>\
												</div>\
											</div>', choice.show_map
										)		
									);
				
								var mentions = $.map(calc.options.entries, function(n, i){
									var title = n.Title,
										mention = {};
	
										title = title.replaceAll('<', '&lt;');
										title = title.replaceAll('>', '&gt;');
	
										title = title.length > 75 ? title.substring(0, 75) + '...' : title;
	
										mention.key = title;
										mention.value = n.ID;
									
									return mention;
								});
	
								var tribute = new Tribute({
									trigger: '#',
									values: mentions
								});
	
								tribute.attach($show.find('textarea')[0]);
	
								var $confirm = $('<button type="button" class="btn btn-primary">Update Choice</button>');
								var $delete = $('<button type="button" class="btn btn-danger">Delete</button>');
								var $cancel = $('<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>');
	
								var $submit = 
								$(
									'<p style="margin: 0; text-align: right;"></p>'	
								);
	
								$submit
									.append($confirm)
									.append($delete)
									.append($cancel);
			
								$modal
									.rsmodal({
										size: 'modal-sm',
										stacked: true,
										header: {
											content: [{
												html:
													'<h2 class="modal-title">Update Choice</h2>'
											}]
										},
										body: {
											content: '<form class="form-horizontal"></form>'
										}
									});
							
								$modal
									.find('.form-horizontal')
									.append($text)
									.append($value)
									.append($show)
									.append('<hr style="border-top: 1px solid #bbb">')
									.append($submit);
	
								$confirm.off('click').on('click', function(){
									choice.show_map = $show.find('textarea').val();
	
									rsAjax({
										type: "POST",
										data: {
											action: "updateEntryChoice",
											text: choice.value,
											value: choice.dropdown_value,
											id: choice.id,
											show: choice.show_map
										},
										success: function(){
											$choice.find('[choice-title]').fadeOut(function(){
												$(this).text(choice.value);
											}).fadeIn();
				
											toastr.info('Choice successfully updated');
										}
									});								
								});
	
								$delete.off('click').on('click', function(){
									$modal = $('<div/>');
									$modal.rsmodal({
										stacked: true,
										header: {
											content: [{
												tag: 'h2',
												children: [{
													html: 'Delete ' + choice.value + '?'
												},{
													tag: "small",
													attributes: {
														class: "font-bold"
													},
													children: [{
														"html": "<br/><span style=\"color: white;\">This action cannot be undone.</span>"	
													}]
												}]
											}]
										},
										body: {
											content: [{
												attributes: {
													class: 'form-group'
												},
												children: [{
													html: '<h4>Are you sure you want to delete ' + choice.value + '? This action cannot be undone.</h4><hr/>'
												}]
											},{
												attributes: {
													style: "float:right;"
												},
												children:[{
													tag: "a",
													attributes: {
														type: "button",
														class: "btn btn-danger",
													},
													actions: {
														click: function(){
															rsAjax({
																type: 'POST',
																data: {
																	action: "deleteChoice",
																	choice: choice.id
																},
																success: function(returned){
																	toastr.info(choice.value + 'was successfully deleted');
																	$modal.rsmodal('close');
				
																	$entry.fadeOut(function(){
																		$entry.remove();
																	});
																}
															});
														}
													},
													children:[{
														html: "Delete " + choice.value
													}]
												},{
													tag: "a",
													attributes: {
														type: "button",
														class: "btn btn-white",
													},
													actions: {
														click: function(){
															$modal.rsmodal('close');
														}
													},
													children:[{
														html: "Close"
													}]
												}]
											},{
												attributes: {
													"style": "clear: both;"
												}
											}]
										}
									});
								})
							});
						});
	
						$choices_list.append($list);
	
						$list.nestable({
							callback: function(l, e){
								var order = $list.nestable('serialize');
			
								$.each(order, function(i, item){
									rsAjax({
										type: "POST",
										data: {
											action: "updateChoicePosition",
											position: i,
											choice: item.id
										}
									})
								});
							}
						});
					}					
				}

				buildEntryChoices(entry);

				$delete.off('click').on('click', function(){
					$modal = $('<div/>');
					$modal.rsmodal({
						stacked: true,
						header: {
							content: [{
								tag: 'h2',
								children: [{
									html: 'Delete ' + entry.Title + '?'
								},{
									tag: "small",
									attributes: {
										class: "font-bold"
									},
									children: [{
										"html": "<br/><span style=\"color: white;\">This action cannot be undone.</span>"	
									}]
								}]
							}]
						},
						body: {
							content: [{
								attributes: {
									class: 'form-group'
								},
								children: [{
									html: '<h4>Are you sure you want to delete the entry ' + entry.Title + '? This action cannot be undone.</h4><hr/>'
								}]
							},{
								attributes: {
									style: "float:right;"
								},
								children:[{
									tag: "a",
									attributes: {
										type: "button",
										class: "btn btn-danger",
									},
									actions: {
										click: function(){
											rsAjax({
												type: 'POST',
												data: {
													action: "deleteEntry",
													entry: entry.ID
												},
												success: function(returned){
													toastr.info(entry.Title + 'was successfully deleted');
													$modal.rsmodal('close');

													$entry.fadeOut(function(){
														$entry.remove();
													});
												}
											});
										}
									},
									children:[{
										html: "Delete " + entry.Title
									}]
								},{
									tag: "a",
									attributes: {
										type: "button",
										class: "btn btn-white",
									},
									actions: {
										click: function(){
											$modal.rsmodal('close');
										}
									},
									children:[{
										html: "Close"
									}]
								}]
							},{
								attributes: {
									"style": "clear: both;"
								}
							}]
						}
					});
				})
			}

			$entry.find('.edit-entry').off('click').on('click', entryEdit);
		}

		calc.buildSectionEntries = function(section){
			var sectionEntryId = 'sectionEntryId:' + section.ID;
			if(calc.sectionEntries[sectionEntryId] && calc.sectionEntries[sectionEntryId].$entryList && calc.sectionEntries[sectionEntryId].$entryList.data('nestable')){
				calc.sectionEntries[sectionEntryId].$entryList.nestable('destroy');
				calc.sectionEntries[sectionEntryId].$entryList.empty();
			};

			var $entryList = $(
				'<div class="dd">\
					<ol class="dd-list"></ol>\
				</div>');

			section.$header.find('.ibox-content').append($entryList);
			section.$entryList = $entryList;

			$.each(section.entries, function(i, entry){
				calc.buildEntryItem(entry, $entryList);
			})
		
			var $newBtn = $('<div style="margin-top: 10px; float: right;"><a add type="button" class="btn btn-primary">Add New Entry</a></div><div style="clear: both;"></div>');
			$entryList.append($newBtn);

			$newBtn.find('[add]').off('click').on('click', function(){

				var $modal = $('<div></div>');

					$modal.rsmodal({
						header: {
							content: [{
								html:
									'<h2 class="modal-title">Create a New Entry</h2>'
							}]
						},
						body: {
							content: [{
								tag: 'form',
								attributes: {
									class: 'form-horizontal'
								},
								children: [{
									html: 	'<div class="form-group">\
												<label class="control-label col-lg-3">Title</label>\
												<div class="col-lg-9">\
													<textarea title class="form-control" rows="1"></textarea>\
												</div>\
											</div>\
											<div class="form-group">\
												<label class="control-label col-lg-3">Type</label>\
												<div class="col-lg-9">\
													<select type class="form-control">\
														<option value="input">Input</option>\
														<option value="output">Output</option>\
														<option value="textarea">Textarea</option>\
														<option value="dropdown">Dropdown</option>\
														<option value="slider">Slider</option>\
														<option value="text">Text / HTML</option>\
													</select>\
												</div>\
											</div>\
											<div class="form-group">\
												<label class="control-label col-lg-3">Format</label>\
												<div class="col-lg-9">\
													<input format class="form-control" />\
												</div>\
											</div>\
											<div class="form-group">\
												<label class="control-label col-lg-3">Tooltip</label>\
												<div class="col-lg-9">\
													<textarea tooltip class="form-control" rows="1"></textarea>\
												</div>\
											</div>\
											<div class="form-group">\
												<label class="control-label col-lg-3">Appended Text</label>\
												<div class="col-lg-9">\
													<input append class="form-control" />\
												</div>\
											</div>\
											<div class="form-group">\
												<label class="control-label col-lg-3">Formula</label>\
												<div class="col-lg-9">\
													<textarea formula class="form-control" rows="1"></textarea>\
												</div>\
											</div>\
											<div class="form-group">\
												<label class="control-label col-lg-3">Address</label>\
												<div class="col-lg-9">\
													<input address class="form-control" />\
												</div>\
											</div>\
											<hr style="border-top: 1px solid #bbb">\
											<p style="margin: 0; text-align: right;">\
												<button create-entry type="button" class="btn btn-primary">Create Entry</button>\
												<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>\
											</p>'
								}]
							}]
						}
					});

				$modal.find('[create-entry]').off('click').on('click', function(){
					var entry = {};
						entry.title = $modal.find('textarea[title]').val();
						entry.type = $modal.find('select[type]').val();
						entry.format = $modal.find('input[format]').val();
						entry.tooltip = $modal.find('textarea[tooltip]').val();
						entry.append = $modal.find('input[append]').val();
						entry.address = $modal.find('input[address]').val();
						entry.formula = $modal.find('textarea[formula]').val();

						var position = Object.keys(section.entries).length;

						rsAjax({
							type: "POST",
							data: {
								action: "addNewEntry",
								structure: calc.options.specs.compID,
								section: section.ID,
								position: position,
								title: entry.title,
								type: entry.type,
								format: entry.format,
								tip: entry.tooltip,
								append: entry.append,
								formula: entry.formula,
								address: entry.address
							},
							success: function(id){
								var entryId = 'entryId:' + entry.ID;
		
								entry.ID = id;
								entry.Title = entry.title;
								entry.Position = position;
								entry.new = true;
		
								section.entries.push(entry);
								calc.entries[entryId] = entry;
								calc.buildSectionEntries(section);

								$modal.find('form')[0].reset();
							}
						})
				})
			});

			$entryList.nestable({
				callback: function(l, e){
					var order = $entryList.nestable('serialize');

					$.each(order, function(i, item){
						var entryId = 'entryId:' + item.id;
						calc.entries[entryId].Position = i;

						rsAjax({
							type: "POST",
							data: {
								action: "updateEntryPosition",
								position: i,
								entry: item.id
							}
						})
					});
				}
			});			
		}
		
		calc.calculations = function(){
			calc.calculations = [];

			$.each(calc.options.calculations, function(i, calculation){
				calc.cells[calculation.address] = calculation;
			});


			calc.$calculations = $([
				'<div class="row border-bottom white-bg dashboard-header">',
					'<div class="col-lg-12">',
						'<h1 style="margin-bottom: 20px;">Calculations</h1>',
					'</div>',
				'</div>',
				'<div class="row border-bottom gray-bg dashboard-header">',
					'<div class="row">',
						'<div class="col-lg-12">',
							'<div class="ibox">',
								'<div class="ibox-content">',
									'<div calculation-list class="dd">',
									'</div>',
								'</div>',
							'</div>',
						'</div>',
					'</div>',
				'</div>'
			].join(''));

			calc.$pageWrapper.append(calc.$calculations);
			calc.buildCalculationList();

			// var $newBtn = $('<div style="margin-top: 10px; float: right;"><a add type="button" class="btn btn-primary">Add New Section</a></div><div style="clear: both;"></div>');
			// calc.$sections.find('.ibox-content').append($newBtn);
		}
		
		calc.buildCalculationList = function(){
			calc.$calculationsList = calc.$calculations.find('[calculation-list]');

			if(calc.$calculationsList.data('nestable')){
				calc.$calculationsList.nestable('destroy');
				calc.$calculationsList.empty();
			};
			calc.$calculationsList.append($('<ol class="dd-list"></ol>'));

			for(var i in calc.calculations){
				var calculation = calc.calculations[i];

				calc.calculationListItem(calculation);
			}

			calc.$calculationsList.nestable();
		}

		calc.calculationListItem = function(calculation, count){
			var $calculation = $(sprintf(
				'<li class="dd-item" data-id="%s">\
					<div class="form-group" style="margin-bottom: 0;">\
						<div class="dd-handle dd3-handle">Drag</div>\
						<div class="dd3-content">\
							<div class="col-lg-12"><i class=\"fa fa-pencil edit-calculation\"></i> <span calculation-title>%s</span></div>\
						</div>\
					</div>\
				</li>', calculation.id, calculation.label));

			calc.$calculationsList.find('ol').append($calculation);

			if(calculation.new){
				$section.hide().fadeIn();
				calculation.new = false;
			}

			var calculationEdit = function(){
				
				var editor = $calculation.find('[edit]');
				if(editor.length) {
					editor.remove();
					return;
				}
				
				var $edit = $(sprintf(
					'<div edit>\
						<div class="form-group row">\
							<label class="control-label col-lg-5">Calculation Name:</label>\
							<div class="col-lg-7">\
								<textarea rows="1" style="resize: vertical;" title class="form-control">%s</textarea>\
							</div>\
						</div>\
						<div class="form-group row">\
							<label class="control-label col-lg-5">Section Writeup:</label>\
							<div class="col-lg-7">\
								<div class="form-group" writeup>%s</div>\
							</div>\
						</div>\
						<div class="form-group row">\
							<label class="control-label col-lg-5">Section Video:</label>\
							<div class="col-lg-7">\
								<input video value="%s" class="form-control">\
							</div>\
						</div>\
						<div class="form-group row">\
							<label class="control-label col-lg-5">Section Formula:</label>\
							<div class="col-lg-7">\
								<input formula value="%s" class="form-control">\
							</div>\
						</div>\
						<div class="form-group">\
							<div class="col-lg-12">\
								<div class="float-right">\
									<a delete type="button" class="btn btn-danger">Delete Section</a>\
									<a update type="button" class="btn btn-primary">Update Section</a>\
								</div>\
							</div>\
						</div>\
						<div style="clear: both;"></div>\
					</div>', section.Title ? section.Title : '', section.Caption ? section.Caption : '', section.Video ? section.Video : '', section.formula ? section.formula : ''));

				$edit.find('[writeup]').summernote();
				$section.append($edit);

				$edit.find('[update]').off('click').on('click', function(){
					
					var title = $edit.find('textarea[title]').val(),
						writeup = $edit.find('[writeup]').summernote('code'),
						video = $edit.find('input[video]').val(),
						formula = $edit.find('input[formula]').val(),
						id = section.ID;
						
					rsAjax({
						type: "POST",
						data: {
							action: "updateSection",
							title: title,
							writeup: writeup,
							video: video,
							formula: formula,
							id: id
						},
						success: function(){
							$section.find('[section-title]').fadeOut(function(){
								$(this).text(title);
							}).fadeIn();

							section.Title = title;
							section.Caption = writeup;
							section.Video = video;
							section.formula = formula;
						}
					})
				});
				
				$edit.find('[delete]').off('click').on('click', function(){
					var $modal = $('<div></div>');
					
					$modal.rsmodal({
						header: {
							content: [{
								html:
									'<h2>\
										Delete ' + section.Title + '?\
										<small class="font-bold"><br/><span style=\"color: white;\">This action cannot be undone.</span></small>\
									</h2>'
							}]
						},
						body: {
							content: [{
								attributes: {
									class: 'form-group'
								},
								children: [{
									html: '<h4>Are you sure you want to delete the section ' + section.Title + '? This action cannot be undone.</h4><hr/>'
								}]
							},{
								attributes: {
									style: "float:right;"
								},
								children:[{
									tag: "a",
									attributes: {
										type: "button",
										class: "btn btn-danger",
									},
									actions: {
										click: function(){
											rsAjax({
												type: 'POST',
												data: {
													action: "deleteSection",
													section: section.ID
												},
												success: function(returned){
													toastr.info(section.Title + 'was successfully deleted');
													$modal.rsmodal('close');

													$section.fadeOut(function(){
														$section.remove();
													});
												}
											});
										}
									},
									children:[{
										html: "Delete " + section.Title
									}]
								},{
									tag: "a",
									attributes: {
										type: "button",
										class: "btn btn-white",
									},
									actions: {
										click: function(){
											$modal.rsmodal('close');
										}
									},
									children:[{
										html: "Close"
									}]
								}]
							},{
								attributes: {
									"style": "clear: both;"
								}
							}]
						}
					});
				})
			}

			$section.find('.edit-section').off('click').on('click', sectionEdit);
		}

		calc.init();
	});

    if (typeof(jQuery) != 'undefined') {
        (function($){
            $.fn.design = function(method) {
                var spreadsheetContainer = $(this).get(0);
                if (! spreadsheetContainer.design) {
                    return design($(this).get(0), arguments[0]);
                } else {
                    return spreadsheetContainer.roishop[method].apply(this, Array.prototype.slice.call( arguments, 1 ));
                }
            };
    
        })(jQuery);
	}

	return design;
})));