if (typeof(numeral) === 'undefined') {
	numeral = undefined;
}

if(typeof(moment) == 'undefined'){
	moment = undefined;
}

;(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
    typeof define === 'function' && define.amd ? define(factory) :
    global.roishop = factory();
}(this, (function () {

	'use strict';
	var roishop = (function(el, options) {
		var users = {};
		var modals = {};

		var user_defaults = {};

		users.options = $.extend(true, options, user_defaults);

		users.el = $(el);
		users.history = [];
		users.historyIndex = -1;

		users.setHistory = function(changes) {
			var index = ++users.historyIndex;
				users.history = (users.history = users.history.slice(0, index + 1));
				users.history[index] = changes;
		}
		
		users.init = function(){
			var options = users.options,
				permission = users.permission,
				companyName = options.company.company_name,
				licenses = users.options.company.users,
				totalUsers = users.options.users.length,
				managers = users.options.users;
				managers.unshift({
					text: "",
					value: ""
				});

			var $container = {
				attributes: {
					class: "white-bg",
					id: "page-wrapper"
				},
				children: [{
					attributes: {
						class: "row border-bottom white-bg dashboard-header",
						id: "section1"
					},
					access: 49,
					permission: permission,
					children: [{
						attributes: {
							class: "col-lg-12"
						},
						children: [{
							html: sprintf('<h1 style="margin-bottom: 20px;">%s Users</h1>', companyName)
						}]
					}]
				},{
					attributes: {
						class:"row border-bottom gray-bg dashboard-header"
					},
					children:[{
						attributes: {
							class: "row"
						},
						children: [{
							attributes: {
								class: "col-lg-12"
							},
							children: [{
								attributes: {
									class: "ibox"
								},
								children: [{
									attributes: {
										class: "ibox-content"
									},
									children: [{
										html: $(sprintf('<p class="alert alert-success m-b-sm">%s currently has %s%s%s licenses and %s users.</p>', companyName, permission < 99 ? '' : '<a style="color: inherit;" class="company_licenses">', licenses, permission < 99 ? '' : '</a>', totalUsers)),
										actions: {
											'click .company_licenses': function(){
												users.changeLicenses();
											}
										}
									},{
										tag: 'table',
										attributes:{
											id: 'users-table'
										},
										type: 'table',
										options: {
											showExport: true,
											recordsName: 'users',
											buttons: {
												btnAdd: {
													text: 'Add a new user',
												  	icon: 'glyphicon-plus',
													event: function () {
														modals.addUser();
													},
													attributes: {
														title: 'Add a new user'
													}
												}
											},
											search: true,
											buttonsOrder: ['export', 'btnAdd'],
											exportDataType: 'all',
											exportOptions: {
												ignoreColumn: [3]
											},
											columns: [{
												field: 'username',
												title: 'Username',
												width: '40',
												widthUnit: '%',
												sortable: true
											},{
												field: 'user_rois',
												title: 'Created Cases',
												width: '10',
												widthUnit: '%',
												sortable: true
											},{
												field: 'manager',
												title: 'Manager',
												width: '30',
												widthUnit: '%',
												sortable: true,
												editable: {
													type: "select",
													source: function(){
														return managers;
													}
												}
											},{
												field: 'actions',
												title: 'Actions',
												width: '20',
												widthUnit: '%',
												formatter: function(value, row, index){
													return ['<div class="pull-right">',
																'<button type="button" class="btn btn-success btn-sm" change-username>Edit User</button>',
																'<button type="button" style="margin-left: 3px;" class="btn btn-primary btn-sm" transfer>Transfer</button>',
																'<button type="button" style="margin-left: 3px;" class="btn btn-danger btn-sm" delete>Delete User</button>',
															'</div>'].join('')
												},
												events: {
													'click [change-username]': function(e, value, row, index){
														var $modal = $('<div></div>'),
															password_change = false,
															username = row.username,
															user_templates = [],
															structures = roishop.current.options.company.templates,
															templates = [];
											
														if(structures){
															$.each(structures, function(){
																if(this.versions && this.versions.length){
																	$.each(this.versions, function(){
																		var template = {
																			value: this.version_id,
																			text: this.version_name
																		}
											
																		templates.push(template);
																	})
																}
															});
														};

														console.log(templates);

														$modal.rsmodal({
															header: {
																content: [{
																	html: "<h2>Edit User <small class=\"font-bold\"><br/><span style=\"color: white;\">Change the username if a new user will be using this account.</small>"
																}]
															},
															body: {
																content: [{
																	attributes: {
																		class: 'form-group'
																	},
																	children: [{
																		html: '<label class="col-lg-5">Username</label>'
																	},{
																		attributes:{
																			class: 'col-lg-7 input-holder'
																		},
																		children:[{
																			tag: 'input',
																			attributes: {
																				class: 'form-control',
																				value: username,
																				username: ''
																			}
																		}]
																	}]
																},{
																	attributes: {
																		class: "form-group"
																	},
																	children: [{
																		attributes: {
																			class: "col-lg-12"
																		},
																		children: [{
																			attributes: {
																				class: "pull-right"
																			},
																			children: [{
																				html: $('<a>Reset Password <i class="fa fa-caret-down"></i></a>'),
																				actions: {
																					click: function(){
																						password_change = !password_change;
																						$('#ResetPassword').toggle();
																					}
																				}
																			}]
																		}]
																	}]
																},{
																	attributes: {
																		id: 'ResetPassword',
																		style: 'display: none;'
																	},
																	children:[{
																		attributes: {
																			class: 'form-group'
																		},
																		children: [{
																			html: '<label class="col-lg-5">Password</label>'
																		},{
																			attributes:{
																				class: 'col-lg-7 input-holder'
																			},
																			children:[{
																				tag: 'input',
																				attributes: {
																					class: 'form-control',
																					password: ''
																				}
																			}]
																		}]
																	},{
																		attributes: {
																			class: 'form-group'
																		},
																		children:[{
																			attributes: {
																				class: 'col-lg-12'
																			},
																			children: [{
																				attributes: {
																					class: 'alert alert-danger'
																				},
																				children:[{
																					html: "If a password isn't entered one will automatically be created for the user."
																				}]
																			}]
																		}]
																	}]
																},{
																	html: '<hr/>'
																},{
																	attributes: {
																		class: 'form-group'
																	},
																	children: [{
																		html: '<label class="col-lg-5">Pick User Templates: </label>'
																	},{
																		attributes:{
																			class: 'col-lg-7 input-holder'
																		},
																		children:[{
																			tag: 'select',
																			type: 'select',
																			attributes: {
																				multiple: ''
																			},
																			choices: templates,
																			actions: {
																				change: function(){
																					user_templates = $(this).val();
																				}
																			}
																		}]
																	}]
																},{
																	attributes: {
																		style: "float:right;"
																	},
																	children:[{
																		html: $('<a type="button" class="btn btn-primary">Reset Username</a>'),
																		actions: {
																			click: function(){
																				var username = $modal.find('[username]').val();
																				var password = $modal.find('[password]').val()
																				
																				rsAjax({
																					type: "POST",
																					data: {
																						action: "updateUser",
																						user: row.user_id,
																						username: username,
																						password: password,
																						availTemplates: templates,
																						templates: user_templates
																					},
																					success: function(response){
																						if(response === "user exists") {
																							toastr.error('User already exists');
																						} else {
																							toastr.info('User successfully updated');
																							$('table#users-table').bootstrapTable('refresh');
							
																							$modal.rsmodal('close');
																						}
																					}
																				});
																			}
																		}
																	},{
																		html: $('<a type="button" class="btn btn-white">Close</a>'),
																		actions: {
																			click: function(){
																				$modal.rsmodal('close');
																			}
																		}
																	}]
																}]
															}
														})
													},
													'click [transfer]': function(e, value, row, index){
														var $modal = $('<div></div>'),
															newUserId,
															newUser;
														
														$modal.rsmodal({
															header: {
																content: [{
																	tag: 'h2',
																	children: [{
																		html: 'Transfer User ROIs'
																	},{
																		tag: "small",
																		attributes: {
																			class: "font-bold"
																		},
																		children: [{
																			"html": "<br/><span style=\"color: white;\">Transfer all of the user's ROIs to another user</span>"	
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
																		html: '<label class="col-lg-7">Transfer ' + row.username + '\'s ROIs to: </label>'
																	},{
																		attributes:{
																			class: 'col-lg-5 input-holder'
																		},
																		children:[{
																			tag: 'select',
																			type: 'select',
																			choices: users.options.users,
																			actions: {
																				change: function(){
																					newUserId = $(this).val();
																					newUser = $(this).find('option:selected').text();
																				}
																			}
																		}]
																	}]
																},{
																	attributes: {
																		style: "float:right;"
																	},
																	children:[{
																		tag: "a",
																		attributes: {
																			type: "button",
																			class: "btn btn-primary",
																		},
																		actions: {
																			click: function(){
																				rsAjax({
																					type: 'POST',
																					data: {
																						action: "transferRois",
																						target: newUserId,
																						user: row.user_id
																					},
																					success: function(){
																						toastr.info(row.username + 's ROIs successfully transferred to ' + newUser);
																						$modal.rsmodal('close');
							
																						$('table#users-table').bootstrapTable('refresh');
																					}
																				});
																			}
																		},
																		children:[{
																			html: "Transfer ROIs"
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
																}]
															}
														});
													},
													'click [delete]': function(e, value, row, index){
														var $modal = $('<div></div>');
														
														$modal.rsmodal({
															header: {
																content: [{
																	tag: 'h2',
																	children: [{
																		html: 'Delete ' + row.username + '?'
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
																		html: '<h4>Are you sure you want to delete ' + row.username + '? This action cannot be undone and all created ROIs may be lost</h4><hr/>'
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
																						action: "deleteUser",
																						user: row.user_id
																					},
																					success: function(returned){
																						toastr.info(row.username + 'was successfully deleted');
																						$modal.rsmodal('close');
							
																						$('table#users-table').bootstrapTable('refresh');
																					}
																				});
																			}
																		},
																		children:[{
																			html: "Delete " + row.username
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
																}]
															}
														});
													},			
												}
											}],
											url: '/assets/api/index.php?action=retrieveUsers&company=' + users.options.company.company_id,
											pagination: true,
											onEditableSave: function(field, row, rowIndex, oldValue, $el){
												rsAjax({
													type: "POST",
													data: {
														action: "changeManager",
														user: row.user_id,
														manager: row.manager
													}
												})
											}
										}
									}]
								}]											
							}]
						}]
					}]
				},{
					attributes: {
						class: "row border-bottom white-bg dashboard-header",
						id: "section2"
					},
					access: 49,
					permission: permission,
					children: [{
						attributes: {
							class: "col-lg-12"
						},
						children: [{
							html: sprintf('<h1 style="margin-bottom: 20px;">%s User Calculators</h1>', companyName)
						}]
					}]
				},{
					attributes: {
						class: "row border-bottom gray-bg dashboard-header"
					},
					children:[{
						attributes: {
							class: "row"
						},
						children: [{
							attributes: {
								class: "col-lg-12"
							},
							children: [{
								attributes: {
									class: "ibox"
								},
								children: [{
									attributes: {
										class: "ibox-content"
									},
									children: [{
										tag: 'table',
										attributes:{
											id: 'users-roi-table'
										},
										type: 'table',
										options: {
											showExport: true,
											recordsName: 'calculators',
											search: true,
											exportDataType: 'all',
											exportOptions: {
												ignoreColumn: [2, 6]
											},
											columns: [{
												field: 'username',
												title: 'Username',
												sortable: true
											},{
												field: 'roi_title',
												title: 'Roi Name',
												sortable: true
											},{
												field: 'link_to_roi',
												title: 'Link to ROI'
											},{
												field: 'created_dt',
												title: 'Created Date',
												sortable: true,
												sorter: function(a, b){
													if(new Date(a) < new Date(b)) return 1;
													if(new Date(a) > new Date(b)) return -1;
													return 0;
												}
											},{
												field: 'visits',
												title: 'Visits',
												sortable: true
											},{
												field: 'unique_ip',
												title: 'Unique',
												sortable: true
											},{
												field: 'actions',
												formatter: function(value, row, index){
													return ['<button type="button" style="margin-left: 3px;" class="btn btn-primary btn-sm" transfer>Transfer</button>',
															'<button type="button" style="margin-left: 3px;" class="btn btn-danger btn-sm" delete>Delete</button>'].join('')
												},
												events: {
													'click [transfer]': function(e, value, row, index){
														var $modal = $('<div></div>'),
															newUserId,
															newUser;
														
														$modal.rsmodal({
															header: {
																content: [{
																	html: sprintf('<h2>Transfer %s <br/><small class="font-bold"><span style=\"color: white;\">Transfer %s to a company user</span></small></h2>', row.roi_title, row.roi_title)
																}]
															},
															body: {
																content: [{
																	attributes: {
																		class: 'form-group'
																	},
																	children: [{
																		html: sprintf('<label class="col-lg-7">Transfer %s to: </label>', row.roi_title)
																	},{
																		attributes:{
																			class: 'col-lg-5 input-holder'
																		},
																		children:[{
																			tag: 'select',
																			type: 'select',
																			choices: users.options.users,
																			actions: {
																				change: function(){
																					newUserId = $(this).val();
																					newUser = $(this).find('option:selected').text();
																				}
																			}
																		}]
																	}]
																},{
																	attributes: {
																		style: "float:right;"
																	},
																	children:[{
																		tag: "a",
																		attributes: {
																			type: "button",
																			class: "btn btn-primary",
																		},
																		actions: {
																			click: function(){
																				rsAjax({
																					type: 'POST',
																					data: {
																						action: "transferRoi",
																						user_id: newUserId,
																						roi_id: row.roi_id
																					},
																					success: function(){
																						toastr.info(sprintf('%s successfully transfered to %s', row.roi_title, newUser));
																						$modal.rsmodal('close');
	
																						roishop.$companyUsers.bootstrapTable('refresh');
																						roishop.$companyUserRois.bootstrapTable('refresh');
																					}
																				});
																			}
																		},
																		children:[{
																			html: "Transfer"
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
																}]
															}
														});
													},
													'click [delete]': function(e, value, row, index){
														var $modal = $('<div></div>');
														
														$modal.rsmodal({
															header: {
																content: [{
																	tag: 'h2',
																	children: [{
																		html: 'Delete ' + row.roi_title + '?'
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
																		html: '<p style="font-size: 22px;">Are you sure you want to delete ' + row.roi_title + '? This action cannot be undone.</p>'
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
																						action: "deleteRoi",
																						roi: row.roi_id
																					},
																					success: function(returned){
																						toastr.info(row.roi_title + ' was successfully deleted');
																						$modal.rsmodal('close');
							
																						$('table#users-roi-table').bootstrapTable('refresh');
																					}
																				});
																			}
																		},
																		children:[{
																			html: "Delete " + row.roi_title
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
																}]
															}
														});												
													}
												}
											}],
											url: '/assets/api/index.php?action=retrieveRoisByCompany&company=' + users.options.company.company_id,
											pagination: true
										}
									}]
								}]											
							}]
						}]
					}]
				},{
					attributes: {
						class: "row border-bottom white-bg dashboard-header",
						id: "section3"
					},
					access: 99,
					permission: permission,
					children: [{
						attributes: {
							class: "col-lg-12"
						},
						children: [{
							html: sprintf('<h1 style="margin-bottom: 20px;">%s Structures</h1>', companyName)
						}]
					}]
				},{
					attributes: {
						class: "row border-bottom gray-bg dashboard-header"
					},
					children:[{
						attributes: {
							class: "row"
						},
						children: [{
							attributes: {
								class: "col-lg-12"
							},
							children: [{
								attributes: {
									class: "ibox"
								},
								children: [{
									attributes: {
										class: "ibox-content"
									},
									children: [{
										tag: 'table',
										attributes:{
											id: 'company-templates'
										},
										type: 'table',
										options: {
											recordsName: 'templates',
											detailView: true,
											onExpandRow: function(index, row, $detail){
												var $version = {
													tag: 'table',
													type: 'table',
													options: {
														buttons: {
															btnAdd: {
																text: 'Add a new user',
																icon: 'glyphicon-plus',
																event: function () {
																},
																attributes: {
																  title: 'Create a new version'
																}
															  }
														},
														buttonsOrder: ['btnAdd'],
														columns: [{
															field: 'version_name',
															title: 'Version Name',
															sortable: true
														},{
															field: 'version_stage',
															title: 'Version Stage',
															sortable: true
														},{
															field: 'ep_version_level',
															title: 'ROI Shop Platform',
															sortable: true
														},{
															field: 'created_dt',
															title: 'Created Date',
															sortable: true,
															sorter: function(a, b){
																if(new Date(a) < new Date(b)) return 1;
																if(new Date(a) > new Date(b)) return -1;
																return 0;
															}
														},{
															field: 'actions',
															formatter: function(value, row, index){
																return ['<div class="pull-right">',
																			'<button type="button" style="margin-left: 3px;" class="btn btn-success btn-sm" edit>Edit</button>',
																			'<button type="button" style="margin-left: 3px;" class="btn btn-primary btn-sm" open>Open</button>',
																			'<button type="button" style="margin-left: 3px;" class="btn btn-danger btn-sm" delete>Delete</button>',
																		'</div>'].join('')
															},
															events: {
																'click [open]': function(e, value, row, index){
																	window.location.href = "../../assets/design?structure=" + row.version_id;
																}
															}
														}],
														url: '/assets/api/index.php?action=retrieveStructureVersions&structure=' + row.structure_id
													}
												}

												$version.$parent = $detail;
												builder.build($version);
											},
											search: true,
											columns: [{
												field: 'structure_title',
												title: 'Structure Name',
												sortable: true
											},{
												field: 'active',
												title: 'Active',
												sortable: true
											},{
												field: 'created_dt',
												title: 'Created Date',
												sortable: true,
												sorter: function(a, b){
													if(new Date(a) < new Date(b)) return 1;
													if(new Date(a) > new Date(b)) return -1;
													return 0;
												}
											},{
												field: 'actions',
												formatter: function(value, row, index){
													return ['<div class="pull-right">',
																'<button type="button" style="margin-left: 3px;" class="btn btn-success btn-sm" edit>Edit</button>',
																'<button type="button" style="margin-left: 3px;" class="btn btn-danger btn-sm" delete>Delete</button>',
															'</div>'].join('')
												},
												events: {
													'click [edit]': function(e, value, row, index){
														var $modal = $('<div></div>'),
															newUserId,
															newUser;
														
														$modal.rsmodal({
															header: {
																content: [{
																	html: sprintf('<h2>Transfer %s <br/><small class="font-bold"><span style=\"color: white;\">Transfer %s to a company user</span></small></h2>', row.roi_title, row.roi_title)
																}]
															},
															body: {
																content: [{
																	attributes: {
																		class: 'form-group'
																	},
																	children: [{
																		html: sprintf('<label class="col-lg-7">Transfer %s to: </label>', row.roi_title)
																	},{
																		attributes:{
																			class: 'col-lg-5 input-holder'
																		},
																		children:[{
																			tag: 'select',
																			type: 'select',
																			choices: users.options.users,
																			actions: {
																				change: function(){
																					newUserId = $(this).val();
																					newUser = $(this).find('option:selected').text();
																				}
																			}
																		}]
																	}]
																},{
																	attributes: {
																		style: "float:right;"
																	},
																	children:[{
																		tag: "a",
																		attributes: {
																			type: "button",
																			class: "btn btn-primary",
																		},
																		actions: {
																			click: function(){
																				rsAjax({
																					type: 'POST',
																					data: {
																						action: "transferRoi",
																						user_id: newUserId,
																						roi_id: row.roi_id
																					},
																					success: function(){
																						toastr.info(sprintf('%s successfully transfered to %s', row.roi_title, newUser));
																						$modal.rsmodal('close');
	
																						roishop.$companyUsers.bootstrapTable('refresh');
																						roishop.$companyUserRois.bootstrapTable('refresh');
																					}
																				});
																			}
																		},
																		children:[{
																			html: "Transfer"
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
																}]
															}
														});
													},
													'click [delete]': function(e, value, row, index){
														var $modal = $('<div></div>');
														
														$modal.rsmodal({
															header: {
																content: [{
																	tag: 'h2',
																	children: [{
																		html: 'Delete ' + row.roi_title + '?'
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
																		html: '<p style="font-size: 22px;">Are you sure you want to delete ' + row.roi_title + '? This action cannot be undone.</p>'
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
																						action: "deleteRoi",
																						roi: row.roi_id
																					},
																					success: function(returned){
																						toastr.info(row.roi_title + ' was successfully deleted');
																						$modal.rsmodal('close');
							
																						$('table#users-roi-table').bootstrapTable('refresh');
																					}
																				});
																			}
																		},
																		children:[{
																			html: "Delete " + row.roi_title
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
																}]
															}
														});												
													}
												}
											}],
											url: '/assets/api/index.php?action=retrieveCompanyStructures&company=' + users.options.company.company_id,
											pagination: true
										}
									}]
								}]											
							}]
						}]
					}]
				}]
			}

			$container.$parent = roishop.el;

			builder.build($container);

			var templates = users.options.company.templates;
			$.each(templates, function(){
				this.value = this.version_id;
				this.text = this.version_name;
			});
		}

		users.changeLicenses = function(){
			if(users.permission < 99) return false;
			
			var $modal = $('<div></div>'),
				licenses = users.options.company.users;

			$modal.rsmodal({
				header: {
					content: [{
						html: sprintf("<h2>Change licenses for %s</h2>", users.options.company.company_name)
					}]
				},
				body: {
					content: [{
						attributes: {
							class: 'form-group'
						},
						children: [{
							html: '<label class="col-lg-5">Total number of licenses</label>'
						},{
							attributes:{
								class: 'col-lg-7 input-holder'
							},
							children:[{
								tag: 'input',
								attributes: {
									class: 'form-control',
									value: licenses
								},
								actions: {
									change: function(){
										licenses = $(this).val();
									}
								}
							}]
						}]
					},{
						attributes: {
							style: "float:right;"
						},
						children:[{
							tag: "a",
							attributes: {
								type: "button",
								class: "btn btn-success",
							},
							actions: {
								click: function(){
									rsAjax({
										type: 'POST',
										data: {
											action: "updateCompanyLicenses",
											company_id: users.options.company.company_id,
											licenses: licenses
										},
										success: function(returned){
											toastr.info(users.options.company.company_name + ' now has ' + licenses + ' licenses');
											$modal.rsmodal('close');

											users.options.company.users = licenses;
											$('.company_licenses').html(licenses);
										}
									});
								}
							},
							children:[{
								html: "Update Licenses"
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
					}]
				}
			});
		}

		modals.addUser = function(){
			var $modal = $('<div/>'), 
				username, first, last, manager,
				user_templates = [],
				password = '',
				structures = roishop.current.options.company.templates,
				templates = [];

			if(structures){
				$.each(structures, function(){
					if(this.versions && this.versions.length){
						$.each(this.versions, function(){
							var template = {
								value: this.version_id,
								text: this.version_name
							}

							templates.push(template);
						})
					}
				});
			}

			var $user_info = $([
				'<div class="form-group">',
					'<div class="row">',	
						'<label>Email Address</label>',
						'<input email class="form-control">',
					'</div>',
				'</div>',
				'<div class="form-group">',
					'<div class="row">',
						'<label>Password</label>',
						'<input password class="form-control">',
						'<span class="form-text pull-right text-danger">',
							'If a password isn\'t entered one will automatically be created for the user.',
						'</span>',
					'</div>',
				'</div>',
				'<div class="form-group">',
					'<div class="row">',
						'<label>First Name</label>',
						'<input first class="form-control">',
					'</div>',
				'</div>',
				'<div class="form-group">',
					'<div class="row">',
						'<label>Last Name</label>',
						'<input last class="form-control">',
					'</div>',
				'</div>',
				'<div class="form-group">',
					'<div class="row">',
						'<label>Select Manager: </label>',
						'<div manager class="input-holder"></div>',
					'</div>',
				'</div>',
				'<div class="form-group">',
					'<div class="row">',
						'<hr>',
					'</div>',
				'</div>',
				'<div class="form-group">',
					'<div class="row">',
						'<label>Select Templates: </label>',
						'<div templates input-holder"></div>',
					'</div>',
				'</div>',
				'<div class="form-group" style="margin-top: 25px; margin-bottom: 0;">',
					'<div class="row">',
						'<div class="pull-right">',
							'<div class="btn-group">',
								'<button close class="btn btn-white btn-outline" type="button">Close</button>',
								'<button add-user class="btn btn-primary btn-outline" type="button">Add New User</button>',
							'</div>',
						'</div>',
					'</div>',
				'</div>'
			].join(''));

			var choices =  users.options.users;
			choices.unshift({
				text: "",
				value:""
			})
			
			builder.build({
				$parent: $user_info.find('div[manager]'),
				tag: 'select',
				type: 'select',
				choices: users.options.users,
				options:{
					allow_single_deselect: true
				},
				actions: {
					change: function(){
						manager = $(this).val();
					}
				}
			});

			var choices = templates;
			$.each(choices, function(){
				this.attributes = {
					selected: "selected"
				};

				user_templates.push(this.value);
			});

			builder.build({
				$parent: $user_info.find('div[templates]'),
				tag: 'select',
				type: 'select',
				attributes: { multiple: '' },
				choices: templates,
				actions: {
					change: function(){
						user_templates = $(this).val();
					}
				}
			});
			
			$modal.rsmodal({
				header: {
					content: [{
						html: "<h2>Add a new User</h2>"
					}]
				},
				body: {
					content: [{
						html: $user_info,
						actions: {
							'change input[email]': function(){
								username = $(this).val();
							},
							'change input[password]': function(){
								password = $(this).val();
							},
							'change input[first]': function(){
								first = $(this).val();
							},
							'change input[last]': function(){
								last = $(this).val();
							},
							'click button[add-user]': function(){
								rsAjax({
									type: 'POST',
									data: {
										action: 'addNewUser',
										company: roishop.current.options.company.company_id,
										username: username,
										password: password,
										first: first,
										last: last,
										manager: manager,
										templates: user_templates
									},
									success: function(response){
										switch(response) {
											case 'user exists':
												toastr.error('User already exists');
											break;

											case 'no licenses':
												toastr.error('Not enough licenses to add user');
											break;

											default:
												toastr.info('User successfully added');
												$('table#users-table').bootstrapTable('refresh');
												$modal.rsmodal('close');
											break;
										}
									}
								})
							},
							'click button[close]': function(){
								$modal.rsmodal('close');
							}
						}
					}]
				}							
			})
		}

		roishop.el = $(el);
		roishop.current = users;
		
		roishop.prepareAdmin();
		users.init();
	});

	roishop.prepareAdmin = function(){
		roishop.buildPermissions();
		roishop.navigation();
	}

	roishop.buildPermissions = function(){
		var users = roishop.current;
		
		users.permission = 0;
		$.each(users.options.permissions, function(){
			var rs_access = parseInt(this.rs_access),
				company_access = parseInt(this.company_access);

			users.permission = rs_access > users.permission ? rs_access : users.permission;
			if(this.company_id === users.options.company.company_id){
				users.permission = company_access > users.permission ? company_access : users.permission;
			}
		});
	}

	roishop.navigation = function(){		
		roishop.$topNavigation = $('<div class="row bottom-border"></div>');
		roishop.el.append(roishop.$topNavigation);

		var $navigation = {
			tag: 'nav',
			attributes: {
				role: 'navigation',
				class: 'navbar-default navbar-static-side'
			},
			children: [{
				attributes: {
					style: 'overflow: hidden; width: auto; height: 100%',
					class: 'sidebar-collapse sidebar-navigation'
				},
				children: [{
					tag: 'ul',
					attributes: {
						id: 'side-menu',
						class: 'nav'
					},
					children: [{
						tag: 'li',
						attributes: {
							class: 'nav-header'
						},
						children: [{
							attributes: {
								class: 'dropdown profile-element'
							},
							children: [{
								html: sprintf('<span><img id="company_logo" alt="image" src="https://www.theroishop.com/company_specific_files/%s/logo/logo.png"></span>', roishop.current.options.company.company_id)
							}]
						}]
					},{
						tag: 'li',
						attributes: {
							class: 'smooth-scroll'
						},
						children: [{
							html: '<a href="#"><i class="fa fa-calculator"></i> <span class="nav-label">Admin</span><span class="fa arrow"></span></a>'
						},{
							tag: 'ul',
							attributes: {
								class: 'nav nav-second-level collapse in'
							},
							children: [{
								tag: 'li',
								children: [{
									html: '<a href="#section1" class="section-navigator">Users</a>'
								}] 
							},{
								tag: 'li',
								access: 49,
								permission: roishop.current.permission,
								children: [{
									html: '<a href="#section2" class="section-navigator">Calculators</a>'
								}] 
							},{
								tag: 'li',
								access: 99,
								permission: roishop.current.permission,
								children: [{
									html: $('<a class="section-navigator">Templates</a>'),
									actions: {
										'click': function(){
											console.log('show templates');
										}
									}
								}] 
							}]
						}]
					},{
						tag: 'li',
						children: [{
							tag: 'a',
							attributes: {
								href: '../../dashboard'
							},
							children: [{
								html: '<i class="fa fa-globe"></i>'
							},{
								html: '<span class="nav-label">My Dashboard</span>'
							}]
						}]
					}]
				}]
			}]
		}

		$navigation.$parent = roishop.el;
		builder.build($navigation);

		var $navigation = $([
			'<nav class="navbar navbar-fixed-top" role="navigation">',
				'<div class="navbar-header">',
					'<h3>Admin</h3>',
				'</div>',
				'<ul class="nav navbar-top-links navbar-right">',
					'<li>',
						'<span class="m-r-sm text-muted welcome-message">Powered by <a href="https:\\www.theroishop.com" style="padding-left: 0;">The ROI Shop</a></span>',
					'</li>',
					'<li class="dropdown myactions-dropdown">',
					'</li>',
					'<li>',
						'<a href="../../assets/logout.php">',
							'<i class="fa fa-sign-out"></i> Log Out',
						'</a>',
					'</li>',
				'</ul>',
			'</nav>'
		].join(''));

		roishop.$topNavigation.append($navigation);
	}

    if (typeof(jQuery) != 'undefined') {
        (function($){
            $.fn.roishop = function(method) {
                var spreadsheetContainer = $(this).get(0);
                if (! spreadsheetContainer.roishop) {
                    return roishop($(this).get(0), arguments[0]);
                } else {
                    return spreadsheetContainer.roishop[method].apply(this, Array.prototype.slice.call( arguments, 1 ));
                }
            };
    
        })(jQuery);
	}
	
	rsAjax({
		data: {
			action: 'companySpecs',
			company: getQueryVariable('companyid')
		},
		success: function(specs){
			specs = JSON.parse(specs);

			$('#wrapper').roishop({
				users: specs.users,
				rois: specs.rois,
				company: specs.company,
				permissions: specs.permissions
			});
		}
	});

	return roishop;
})));

function getQueryVariable(variable) {
		
	var query = window.location.search.substring(1),
		vars = query.split("&");

	for (var i=0;i<vars.length;i++) {
		
		var pair = vars[i].split("=");
		if(pair[0] == variable){ return pair[1]; }
	}
	
	return(false);
};