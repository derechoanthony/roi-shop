;(function($, window, document, undefined) {
	
	var RoiShopCalculator = function(element, options) {
		this.options = options;
        this.el = $(element);
		this.elements = [];
		this.values = [];
		this.renderedElements = [];
		this.elementOptions = [];

        this.init();	
	};
	
	RoiShopCalculator.DEFAULTS = {
		calcHolder: '#wrapper',
		equalizer: 'rs-equalize-',
		pullVerificationLink: true,
		SFDCIntegration: true,
		userLoggedIn: true,
		manageContributors: true,
		canDeleteContributors: true,
		changeCurrency: true,
		hideSections: true,
		resetTemplate: true,
		fadeInSpeed: 1000,
		fadeOutSpeed: 1000,
		language: {
			delimiters: {
				thousands: ',',
				decimal: '.'
			},
			abbreviations: {
				thousand: 'k',
				million: 'm',
				billion: 'b',
				trillion: 't'
			},
			ordinal: function (number) {
				var b = number % 10;
				return (~~ (number % 100 / 10) === 1) ? 'th' :
					(b === 1) ? 'st' :
					(b === 2) ? 'nd' :
					(b === 3) ? 'rd' : 'th';
			},
			currency: {
				symbol: '$'
			}
		}
	};
	
	RoiShopCalculator.TABLE_DEFAULTS = {
		classes: 'table table-hover',
		sortClass: undefined,
		locale: undefined,
		height: undefined,
		undefinedText: '-',
		sortName: undefined,
		sortOrder: 'asc',
		sortStable: false,
		striped: false,
		columns: [[]],
		data: [],
		totalField: 'total',
		dataField: 'rows',
		method: 'get',
		url: undefined,
		ajax: undefined,
		cache: true,
		contentType: 'application/json',
		dataType: 'json',
		ajaxOptions: {},
		queryParams: function (params) {
			return params;
		},
		queryParamsType: 'limit', // undefined
		responseHandler: function (res) {
			return res;
		},
		pagination: 0,
		onlyInfoPagination: false,
		paginationLoop: true,
		sidePagination: 'client', // client or server
		totalRows: 0, // server side need to set
		pageNumber: 1,
		pageSize: 10,
		pageList: [10, 25, 50, 100],
		paginationHAlign: 'right', //right, left
		paginationVAlign: 'bottom', //bottom, top, both
		paginationDetailHAlign: 'left', //right, left
		paginationPreText: '&lsaquo;',
		paginationNextText: '&rsaquo;',
		search: false,
		searchOnEnterKey: false,
		strictSearch: false,
		searchAlign: 'right',
		selectItemName: 'btSelectItem',
		addRecord: 0,
		showHeader: true,
		showFooter: false,
		showColumns: false,
		showPaginationSwitch: false,
		showRefresh: false,
		showToggle: false,
		buttonsAlign: 'right',
		smartDisplay: true,
		escape: false,
		minimumCountColumns: 1,
		idField: undefined,
		uniqueId: undefined,
		cardView: false,
		detailView: false,
		detailFormatter: function (index, row) {
			return '';
		},
		trimOnSearch: true,
		clickToSelect: false,
		singleSelect: false,
		toolbar: undefined,
		toolbarAlign: 'left',
		checkboxHeader: true,
		sortable: true,
		silentSort: true,
		maintainSelected: false,
		searchTimeOut: 500,
		searchText: '',
		iconSize: undefined,
		buttonsClass: 'default',
		iconsPrefix: 'fa', // glyphicon of fa (font awesome)
		icons: {
			paginationSwitchDown: 'fa-chevron-circle-down',
			paginationSwitchUp: 'fa-chevron-circle-up',
			refresh: 'fa-sync-alt',
			toggle: 'fa-list-alt',
			columns: 'fa-th',
			detailOpen: 'fa-plus',
			detailClose: 'fa-minus',
			addRecord: 'fa-file',
			deleteRecord: 'fa-trash',
			editRecord: 'fa-pencil'
		}
	};
	
	RoiShopCalculator.LOCALES = {};
	
	RoiShopCalculator.LOCALES['en-US'] = RoiShopCalculator.LOCALES.en = {
        formatLoadingMessage: function () {
            return 'Loading, please wait...';
        },
        formatRecordsPerPage: function (pageNumber) {
            return sprintf('%s rows per page', pageNumber);
        },
        formatShowingRows: function (pageFrom, pageTo, totalRows) {
            return sprintf('Showing %s to %s of %s rows', pageFrom, pageTo, totalRows);
        },
        formatDetailPagination: function (totalRows) {
            return sprintf('Showing %s rows', totalRows);
        },
        formatSearch: function () {
            return 'Search';
        },
        formatNoMatches: function () {
            return 'No matching records found';
        },
        formatPaginationSwitch: function () {
            return 'Hide/Show pagination';
        },
        formatRefresh: function () {
            return 'Refresh';
        },
        formatToggle: function () {
            return 'Toggle';
        },
		formatAddRecord: function() {
			return 'Add Record';
		},
		formatDeleteRecord: function() {
			return 'Delete Record';
		},
		formatEditRecord: function() {
			return 'Edit Record';
		},
        formatColumns: function () {
            return 'Columns';
        },
        formatAllRows: function () {
            return 'All';
        }
    };
	
	$.extend(RoiShopCalculator.DEFAULTS, RoiShopCalculator.LOCALES['en-US']);

	RoiShopCalculator.prototype.init = function (){
		this.initLocale();
		this.setupLanguage();
		this.renderContainers();
		this.sideNavigation();
		this.topNavigation();
		this.calxSheet();
		this.roiElements();
 		this.cleanUpCells();
		this.setupCalculator();
		this.setScroll();
		this.setVisibility();
	}
	
    RoiShopCalculator.prototype.initLocale = function () {
        if (this.options.locale) {
            var parts = this.options.locale.split(/-|_/);
            parts[0].toLowerCase();
            if (parts[1]) parts[1].toUpperCase();
            if ($.fn.roishopCalculator.locales[this.options.locale]) {
                // locale as requested
                $.extend(this.options, $.fn.roishopCalculator.locales[this.options.locale]);
            } else if ($.fn.roishopCalculator.locales[parts.join('-')]) {
                // locale with sep set to - (in case original was specified with _)
                $.extend(this.options, $.fn.roishopCalculator.locales[parts.join('-')]);
            } else if ($.fn.roishopCalculator.locales[parts[0]]) {
                // short locale language code (i.e. 'en')
                $.extend(this.options, $.fn.roishopCalculator.locales[parts[0]]);
            }
        }
    }
	
	RoiShopCalculator.prototype.setupLanguage = function() {		
		numeral.language('RSShop', this.options.language);		
	}

	RoiShopCalculator.prototype.renderContainers = function () {
		this.$sideNavigation = $('<nav class="navbar-default navbar-static-side" role="navigation"></nav>');
		this.$topNavigation = $('<div class="row bottom-border"></div>');		
		this.$roiCalculator = $('<div id="page-wrapper"></div>');
		this.$roiFooter = $('<div class="footer fixed"><div><strong>Copyright</strong> The ROI Shop</div></div>');
		this.$roiContent = $('<div id="roiContent" style="min-height: 1200px;""></div>');
		this.$roiCalxSheet = $('<div id="roiCalxSheet" style="display: none;""></div>');
		
		this.el.append(this.$sideNavigation);
		this.el.append(this.$roiCalculator);
		this.$roiCalculator.append(this.$topNavigation);
		this.$roiCalculator.append(this.$roiContent);
		this.$roiCalculator.append(this.$roiFooter);
		this.$roiCalculator.append(this.$roiCalxSheet);
	}
	
	RoiShopCalculator.prototype.sideNavigation = function(){
		var that = this,
			list = [];

		var navigation = $([
			'<div class="sidebar-collapse sidebar-navigation" style="overflow: hidden; width: auto; height: 100%;">',
				'<ul class="nav" id="side-menu">',
					'<li class="nav-header">',
						'<div class="dropdown profile-element">',
							'<span>',
								sprintf('<img id="company_logo" class="some-button" alt="image" src="../company_specific_files/%s/logo/logo.png">', this.options.roiTemplate.company_id),
							'</span>',
						'</div>',
					'</li>',	
				'</ul>',
			'</div>'		
		].join(''));
		
		this.$sideMenu = navigation.find('#side-menu');

		var navigationItem = function($element, navItem){
			var $navigation = $(sprintf('<li class="smooth-scroll%s"%s />', 
									navItem.id ? ' rs-include-' + navItem.id : '',
									navItem.el_visibility == 0 ? ' style="display: none;"' : ''));
									
			$navigation.data('navigation.data', navItem);
			
			var $a = $(sprintf('<a href="%s">', navItem.href ? navItem.href : '#'));
			$navigation.append($a);
					
			var a = [];		
			
			a.push(navItem.icon ? sprintf('<i class="%s"></i>', navItem.icon) : '', navItem.label, navItem.children ? '<span class="fa arrow"></span>' : '');
			$a.html(a.join(''));
			
			$element.append($navigation);
			
			if (navItem.children){
				var $list = $('<ul class="nav nav-second-level collapse in">')
				$navigation.append($list);
				
				$.each(navItem.children, function(){
					navigationItem($list, this);
				});	
			}
		}

		if (this.options.navigation){
			$.each(this.options.navigation, function(){
				navigationItem(that.$sideMenu, this);
			});
		}
		
		this.$sideNavigation.append(navigation);
		
		if (this.options.userLoggedIn){
			this.$sideMenu.append(
				$('<li><a href="../../dashboard"><i class="fa fa-globe"></i><span class="nav-label">My ROIs</span></a></li>')
			);
		};

		this.$sideNavigation.find('li.smooth-scroll a[href="#pdf"]').off('click').on('click', $.proxy(this.createCharts, this));
	}

	RoiShopCalculator.prototype.topNavigation = function(){
		var myactions = [];
		if (this.options.pullVerificationLink) {
			myactions.push('<li><a class="showVerificationLink">Show Verification Link</a></li>');
		}
		
		if (this.options.SFDCIntegration) {
			myactions.push('<li><a class="connectToSFDC">Connect to Saleforce</a></li>');
		}		
		
		if (this.options.resetTemplate){
			myactions.push('<li><a class="resetTemplate">Reset Template</a></li>')
		}
		
		if (this.options.userLoggedIn) {
			myactions.push('<li class="divider"></li><li><a><i class="fa fa-user"></i> View Your Profile</a></li>');
			myactions.push('<li><a><i class="fa fa-sign-out"></i> Log Out</a></li>');
		}
		
		var navigation = $([
			'<nav class="navbar navbar-fixed-top" role="navigation" style="margin-bottom: 0">',
				'<div class="navbar-header">',
					sprintf('<h3>%s</h3>', this.options.roiInfo.roi_title),
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
		
		this.$topNavigation.append(navigation);
		
		var $dropdownAlerts = this.$topNavigation.find('.myactions-dropdown');
		if (myactions.length){
			var $myActionsDropdown = [];
			$myActionsDropdown.push(
				'<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">',
					'My Actions <i class="fa fa-caret-down"></i>',
				'</a>',
				'<ul class="dropdown-menu dropdown-alerts">');
				
			$.each(myactions, function(){
				$myActionsDropdown.push(this);
			});
			
			$myActionsDropdown.push('</ul>');
			$dropdownAlerts.append($($myActionsDropdown.join('')));
		}
		
		this.$topNavigation.find('.showVerificationLink').off('click').on('click', $.proxy(this.showVerificationLink, this));
		this.$topNavigation.find('.connectToSFDC').off('click').on('click', $.proxy(this.connectToSFDC, this));
		this.$topNavigation.find('.manageContributors').off('click').on('click', $.proxy(this.manageContributors, this));
		this.$topNavigation.find('.changeCurrency').off('click').on('click', $.proxy(this.changeCurrency, this));
		this.$topNavigation.find('.hideSections').off('click').on('click', $.proxy(this.hideSections, this));
		this.$topNavigation.find('.resetTemplate').off('click').on('click', $.proxy(this.resetTemplate, this));
	}
	
	RoiShopCalculator.prototype.connectToSFDC = function() {
		var RSCalc = this;
		
		var $modal = $([
			'<div class="modal inmodal fade in">',
				'<div class="modal-dialog">',
					'<div class="modal-content animated fadeIn">',
						'<div class="modal-header">',
							'<button type="button" class="close" data-dismiss="modal">',
								'<span aria-hidden="true">&times;</span>',
								'<span class="sr-only">Close</span>',
							'</button>',
							'<i class="fa fa-shield modal-icon"></i>',
							'<h4 class="modal-title">Connect to Salesforce</h4>',
							'<small class="font-bold">Opportunities are retrieve on load. This may take a few minutes depending on how many opportunities are available. Once completed they will load in the dropdown</small>',
						'</div>',
						'<div class="modal-body">',
							'Connect to opportunity: <select class="form-control"></select>',
						'</div>',
						'<div class="modal-footer">',
							'<button type="button" class="btn btn-danger save-opportunity">Save to Opportunity</button>',
							'<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>',
						'</div>',
					'</div>',
				'</div>',
			'</div>'
		].join(''));
		
		$modal.find('select.form-control').chosen({width: '100%', disable_search_threshold: 10});
		
		$.ajax({
			type: "GET",
			url: "assets/ajax/calculator.get.php",
			data: {
				action: "getSFDCconnection",
				roi: getQueryVariable('roi')
			},
			success: function(opportunities){
				if(opportunities){console.log(opportunities);
					var opportunities = $.parseJSON(opportunities);
					var selectOptions = '';
					
					opportunities.sort(function(a, b){
						return ((a.Name.toLowerCase() < b.Name.toLowerCase()) ? -1 : ((a.Name.toLowerCase() > b.Name.toLowerCase()) ? 1 : 0));
					});
					
					for(var i=0; i<opportunities.length; i++) {
						var opp = opportunities[i];
						selectOptions += "<option value='" + opp.Id + "'>" + opp.Name + "</option>";
					};
					
					$modal.find('select.form-control').append(selectOptions);
					$modal.find('select.form-control').trigger('chosen:updated');					
				}
			},
			error: function(error){

			}
		});

		var onSaveOpportunity = function(){
			$.ajax({
				type: "POST",
				url: "assets/ajax/calculator.post.php",
				data: {
					action: "saveOpportunity",
					roi: getQueryVariable('roi'),
					link: $modal.find('select.form-control').val(),
					instance: 'opportunities',
					full_link: sprintf('%s&v=%s', RSCalc.options.roiInfo.roi_full_path.replace('../', getRootUrl()), RSCalc.options.roiInfo.verification_code)
				},
				success: function(opp){
					console.log(opp);
				}
			});
		}

		$modal.find('button.save-opportunity').off('click').on('click', onSaveOpportunity);		

		this.$openModal = $modal.modal('show');
	}	
	
	RoiShopCalculator.prototype.showVerificationLink = function() {
		var that = this;
		
		var $modal = $([
			'<div class="modal inmodal fade in">',
				'<div class="modal-dialog">',
					'<div class="modal-content animated fadeIn">',
						'<div class="modal-header">',
							'<button type="button" class="close" data-dismiss="modal">',
								'<span aria-hidden="true">&times;</span>',
								'<span class="sr-only">Close</span>',
							'</button>',
							'<i class="fa fa-shield modal-icon"></i>',
							'<h4 class="modal-title">ROI Verification Link</h4>',
							'<small class="font-bold">The following link can be used to give anyone access to this ROI.</small>',
						'</div>',
						'<div class="modal-body">',
							sprintf('<textarea class="ver-link-output" spellcheck="false" readonly="readonly">%s&v=%s</textarea>', this.options.roiInfo.roi_full_path.replace('../', getRootUrl()), this.options.roiInfo.verification_code),
						'</div>',
						'<div class="modal-footer">',
							'<button type="button" class="btn btn-danger reset-link">Reset Link</button>',
							'<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>',
						'</div>',
					'</div>',
				'</div>',
			'</div>'
		].join(''));

		this.$openModal = $modal.modal('show');
		
		var resetVerificationConfirm = function() {
			var that = this;
			this.$openModal.modal('hide');
			
			var $modal = $([
				'<div class="modal inmodal fade in">',
					'<div class="modal-dialog">',
						'<div class="modal-content animated fadeIn">',
							'<div class="modal-header">',
								'<button type="button" class="close" data-dismiss="modal">',
									'<span aria-hidden="true">&times;</span>',
									'<span class="sr-only">Close</span>',
								'</button>',
								'<i class="fa fa-shield modal-icon"></i>',
								'<h4 class="modal-title">Reset Verification Link?</h4>',
							'</div>',
							'<div class="modal-body">',
								'<p>Would you like to reset the verification link for this ROI? Once the link is reset it <b>cannot</b> be undone and no prospects will be able to view the ROI without the new link.</p>',
							'</div>',
							'<div class="modal-footer">',
								'<button type="button" class="btn btn-danger reset-confirmed">Yes, Reset</button>',
								'<button type="button" class="btn btn-white cancel-reset">Cancel</button>',
							'</div>',
						'</div>',
					'</div>',
				'</div>'
			].join(''));

			this.$openModal = $modal.modal('show');
			
			var resetVerification = function(){
				$.ajax({
					type: "POST",
					url: "/assets/ajax/calculator.post.php",
					data: {
						action: "resetVerification",
						roi: getQueryVariable('roi')
					},
					success: function(verification){
						that.options.roiInfo.verification_code = verification;
						that.$openModal.modal('hide');
						that.showVerificationLink();
					}
				});
			}
			
			var cancelReset = function(){
				this.$openModal.modal('hide');
				this.showVerificationLink();
			}
			
			$modal.find('.reset-confirmed').off('click').on('click', resetVerification);
			$modal.find('.cancel-reset').off('click').on('click', $.proxy(cancelReset, this));
		};
		
		$modal.find('.reset-link').off('click').on('click', $.proxy(resetVerificationConfirm, this));
		$modal.find('.ver-link-output').off('click').on('click', function(){ $(this).select() });
	}
	
	RoiShopCalculator.prototype.calxSheet = function(){
		var RSCalc = this,
			auto_id = 0;
			
		this.fields = [];

		$.each(this.options.fields, function(){
			RSCalc.fields[this.el_field_name]= this;
			
			$element = $(sprintf('<input data-cell="%s"%s%s></input>', this.el_field_name, this.el_formula ? sprintf(' data-formula="%s" ', this.el_formula) : '', this.f_format ? sprintf(' data-format="%s" ', this.f_format) : ''));
			
			$element.val(this.el_value);
			RSCalc.$roiCalxSheet.append($element);
		});
		console.log(this.fields);
		this.nextAutoId = auto_id++;
	}

	RoiShopCalculator.prototype.roiElements = function() {
		RSCalc = this;
		
		$.each(this.options.elements, function(i, options){
			RSCalc.renderElement(options, RSCalc.$roiContent);
		});
	}
	
	RoiShopCalculator.prototype.renderElement = function(options, $container) {
		
		if(options){
			switch (options.el_type){
				case 'button':
					this.button($container, options);
					break;
						
				case 'checkbox':
					this.checkbox($container, options);
					break;
						
				case 'select':
					this.selector($container, options);
					break;
						
				case 'graph':
					this.graph($container, options);
					break;
						
				case 'holder': 
					this.holder($container, options);
					break;
						
				case 'input':
					this.input($container, options);
					break;

				case 'rating':
					this.rating($container, options);
					break;
						
				case 'slider':
					this.slider($container, options);
					break;
						
				case 'text':
					this.htmltext($container, options);
					break;
					
				case 'table':
					this.table($container, options);
					break;
					
				case 'tblrow':
					this.tablerow($container, options);
					break;
						
				case 'textarea':
					this.textarea($container, options);
					break;
					
				case 'toggle':
					this.toggle($container, options);
					break;
					
				case 'video':
					this.video($container, options);
					break;
					
				case 'modal':
					this.modal($container, options);
					break;
			};				
		}	
	}
	
	RoiShopCalculator.prototype.holder = function($container, options){
		var that = this,
			$element = $(sprintf('<div%s%s></div>', options.el_class ? sprintf(' class="%s"', options.el_class) : '', ' element-id="' + options.el_id + '"'));
		
		$container.append($element);
		
		if (options.el_visibility == 0){
			$element.hide();
		}
		
		this.logElement($element, options);
		
		if (options.children && options.children.length > 0) {
			$.each(options.children, function(i, options){
				that.renderElement(options, $element);
			})
		}
	}
	
	RoiShopCalculator.prototype.htmltext = function($container, options){
		$container.append(options.el_text);
		
		if(options.el_visibility == 0){
			$element.hide();
		};
	}
	
	RoiShopCalculator.prototype.video = function($container, options){
		var that = this,
			$element = $('<div class="player"></div>');
			
		$container.append($element);
		
		this.logElement($element, options);			
		
		$container = $([
			sprintf('<a class="popup-iframe" href="%s"></a>', options.el_src),
			sprintf('<iframe class="fit-vid" style="margin-left: 5px;" width="425" height="239" src="%s" frameborder="0"/>', options.el_src),
		].join(''));
			
		$element.append($container);
		$container.fitVids();	
	}
	
	RoiShopCalculator.prototype.buildInput = function(options){
		
		var $container = $([
			'<div class="form-group">',
			sprintf('<label class="%s">%s</label>', options.el_label_class, options.el_text),
			sprintf('<div class="%s input-holder">', options.el_class),
			options.el_tooltip || options.el_append || options.el_formula ?
				'<div class="input-group">' : '',
				sprintf('<input class="form-control' + ( options.el_tooltip || options.el_formula ? ' input-addon' : '' ) + '" %s%s%s>', options.el_field_name ? ' name="' + options.el_field_name + '"' : '', options.el_field_name ? ' data-cell="' + options.el_field_name + '"' : '', options.el_format ? ' data-format="' + options.el_format + '"' : ''),
			options.el_append ?
				sprintf('<span class="input-group-addon right append">%s</span>', options.el_append) : '',
			options.el_tooltip || options.el_append || options.el_formula ?
				'</div>' : '',
			'</div>',
			'</div>'
		].join(''));

		return $container;
	}
	
	RoiShopCalculator.prototype.textarea = function($container, options){
		var RSCalc = this,
			$element = $('<div></div>');
			
		$container.append($element);
		
		this.logElement($element, options);		
			
		var $container = $([
			'<div class="form-group">',
			sprintf('<label class="%s">%s</label>', options.el_label_class, options.el_text),
			sprintf('<div class="%s">', options.el_class),
			sprintf('<textarea class="form-control" style="width: 100%; resize: vertical" rows="%s">%s</textarea>', options.el_src || 4, options.el_value || ''),
			'</div>',
			'</div>'
		].join(''));

		$element.addClass('form-horizontal').append($container);
		if (options.el_visibility == 0){
			$element.hide();
		};
		var $textarea = $container.find('textarea');
	
		var textareaChange = function(){
			var val = $(this).val(),
				formatted = val,
				field = options.el_field_name;

			if (options.el_format){
				formatted = numeral().unformat(val);
			}
				
			RSCalc.setValue(field, val, formatted);
		}
		
		$textarea.off('change').on('change', textareaChange);		
	}
	
	RoiShopCalculator.prototype.input = function($container, options){
		var RSCalc = this,
			$element = $('<div></div>');

		$container.append($element);
		
		this.logElement($element, options);
		
		$container = this.buildInput(options);
		
		$element.addClass('form-horizontal').append($container);
		$element.data('element.options', options);
		
		var $form = $container.find('.form-horizontal');
		var $group = $container.find('.form-group');
		var $label = $container.find('label');
		var $input = $container.find('input');

		if(options.el_enabled == 0) $input.prop('disabled', 'disabled');
		if(options.el_formula) $input.attr('data-formula', options.el_formula);
		if(options.el_formula || options.el_tooltip) this.tooltip($input, options);
		if(options.el_value) $input.val(numeral(options.el_value).format(options.el_format || "0,0[.]00"));	
			
		var inputFocus = function(){
			$(this).select();
			$(this).parent().find('.helper').addClass('input-addon-border');
		}
			
		var inputChange = function(){
			var val = $(this).val(),
				formatted = val,
				field = options.el_field_name;

			if (options.el_format){
				formatted = numeral().unformat(val);
			}
				
			RSCalc.setValue(field, val, formatted);
		}
			
		var inputBlur = function(){
			$(this).parent().find('.helper').removeClass('input-addon-border');
		}
			
		$input.off('focus').on('focus', inputFocus);
		$input.off('change').on('change', inputChange);
		$input.off('blur').on('blur', inputBlur);
			
		if(options.el_visibility == 0){
			$element.hide();
		};
	}
	
	RoiShopCalculator.prototype.buildDropdown = function(options){

		var $container = $([
			'<div class="form-group">',
			( options.el_text ? sprintf('<label class="%s">%s</label>', options.el_label_class, options.el_text) : '' ),
			sprintf('<div class="%s">', options.el_class),
				'<select data-cell="' + options.el_field_name + '" class="form-control">',
			'</div>',
			'</div>'
		].join(''));
		
		var $dropdown = $container.find('select');
		
		if(options.choices){
			var choices = options.choices,
				selections = [];

			if (!options.choice_value) {
				options.choice_value = options.choices[0].choice_text;
			}
			if (!options.choice_formatted_value) options.choice_formatted_value = options.choices[0].choice_text;
			
			$.each(choices, function(count, choice){
				selections.push(sprintf('<option value="%s"%s>%s</option>', choice.choice_value ? choice.choice_value : choice.choice_text, ( choice.choice_value == options.el_value ? ' selected="selected"' : '' ), choice.choice_text));
			});
			selections = selections.join('');
				
			$dropdown.append($(selections));
		};
		
		return $container;
	}
	
	RoiShopCalculator.prototype.selector = function($container, options){
		var RSCalc = this,
			$element = $(sprintf('<div%s></div>', options.el_id ? sprintf(' element-id="%s"', options.el_id) : ''));
			
		$container.append($element);
		this.logElement($element, options);
		
		$container = this.buildDropdown(options);
			
		$element.addClass('form-horizontal').append($container);
		var $form = $container.find('.form-horizontal');
		var $group = $container.find('.form-group');
		var $label = $container.find('label');
		var $dropdown = $container.find('select');

		var onSelectChange = function(){
			console.log($(this));
			var field_name = options.el_field_name,
				selected = $(this).find('option:selected')[0],
				val = selected.value,
				text = selected.text;

				RSCalc.setValue(field_name, val, text);
								
				$.each(RSCalc.renderedElements['table'], function(){
					RSCalc.redraw($(this));
				});
		}
		
		$dropdown.chosen({width: '100%', disable_search_threshold: 10});
		$dropdown.off('change').on('change', onSelectChange);		
	}
	
	RoiShopCalculator.prototype.table = function($container, t_options){
		var RSCalc = this,
			$element = $('<div></div>'),
			options = $.extend({}, RoiShopCalculator.TABLE_DEFAULTS, t_options),
			sort_column = -1,
			sort_order = 'asc',
			totalPages, $allSelected = false,
			pageFrom, pageTo,
			pageSize = parseInt(options.pageSize),
			pageNumber = parseInt(options.pageNumber),
			iPageNumber = parseInt(pageNumber),
			totalRows = parseInt(options.totalRows), i, from, to, $first, $last, $number,
			pageList = options.pageList,
			searchText,	table_data;

		$container.append($element);

		this.logElement($element, t_options);

		var $container = $([
			options.table_name ? sprintf('<div class="table-title"><h4 class="text-main pad-btm bord-btm">%s</div>',options.table_name) : '',
			'<div class="bootstrap-table">',
			'<div class="fixed-table-toolbar"></div>',
			options.paginationVAlign === 'top' || options.paginationVAlign === 'both' ?
			'<div class="fixed-table-pagination" style="clear: both;"></div>' :
			'',
			'<div class="fixed-table-container">',
				'<div class="fixed-table-header"><table></table></div>',
				'<div class="fixed-table-body">',
					'<div class="fixed-table-loading">',this.options.formatLoadingMessage(),'</div>',
				'</div>',
				'<div class="fixed-table-footer"><table><tr></tr></table></div>',
				options.paginationVAlign === 'bottom' || options.paginationVAlign === 'both' ?
				'<div class="fixed-table-pagination"></div>' :
				'',
			'</div>',
			'</div>'
        ].join(''));

        $element.append($container);
		if (options.el_visibility == 0) $element.hide();

        var $tableContainer = $container.find('.fixed-table-container');
        var $tableHeader = $container.find('.fixed-table-header');
        var $tableBody = $container.find('.fixed-table-body');
        var $tableLoading = $container.find('.fixed-table-loading');
        var $tableFooter = $container.find('.fixed-table-footer');
        var $toolbar = $container.find('.fixed-table-toolbar');
        var $pagination = $container.find('.fixed-table-pagination');

        $container.after('<div class="clearfix"></div>');
		
		$table = $('<table/>').appendTo($tableBody).addClass(options.el_class);
		$header = $('<thead></thead>').appendTo($table);
		$body = $('<tbody></tbody>').appendTo($table);

		if (options.colgroup){
			var $colgroup = $('<colgroup/>');

			$.each(options.columns, function(i, col){
				col_style = '';
				
				for(a in options.colgroup){
					if(options.colgroup[a].col_number == i) col_style = options.colgroup[a].el_options;
				}
				
				$col = $(sprintf('<col %s>', col_style));
				$colgroup.append($col);
			});
			
			$table.append($colgroup);
		}

		if (options.columns){
			
			$.each(options.columns, function(i, column){
				if (column.headers){
					$.each(column.headers, function(i, header){
						
						if (!$header.find('tr')[i]) {
							$header.append('<tr/>');
						}
						
						var $column = $header.find('tr')[i];
						var $column_header = $('<th/>');

						if(column.visible == 0){
							$column_header.hide();
						}

						$column.append($column_header[0]);
						
						var $inner = $(sprintf('<div class="th-inner %s">', options.sortable ? 'sortable both' : ''));
						var text = header.header_text;
						
						$inner.append(text);
						$column_header.append($inner);

					});
				}
			});			
		}

		$container.off('click', '.th-inner').on('click', '.th-inner', function(event){
			if (options.sortable){
				onSort(event);
			}
		});
		
		var onSort = function(event){
			var $this = event.type == "keypress" ? $(event.currentTarget) : $(event.currentTarget).parent();
			if (sort_column == $this.index()){
				sort_order = sort_order === 'asc' ? 'desc' : 'asc';
			} else {
				sort_column = $this.index();
				sort_order = 'asc';
			}

			getCaret();
			initSort();
			initBody();
		}
		
		var getCaret = function(){
			$.each($element.find('.fixed-table-body > table > thead').find('th'), function(i, th){
				$(th).find('.sortable').removeClass('desc asc').addClass($(th).index() === sort_column ? sort_order : 'both');
			});
		}
		
		var setTableData = function(){
			table_data = [];
			pre_data = [];
			columns = t_options.columns;
			next_row_id = 0;
		
			$.each(columns, function(){
				var cells = this.cells;
				
				if(cells){
					$.each(cells, function(i, a){
						field_value = RSCalc.fields[this.el_field_name];
	
						$.extend(this, field_value);
						
						if (a.field_row_id > next_row_id) next_row_id = a.field_row_id;
						
						if (!pre_data[i]) pre_data[i] = [];
						pre_data[i].push(a);
					});					
				}
			});

			if (options.filters){
				$.each(options.filters, function(){

					var filter = this;
					var data_to_search = pre_data;
					
					pre_data = [];
					$.each(data_to_search, function(index, row){

						var include = false;
						$.each(row, function(){
							
							var field = this;
							if (filter.column_id === this.field_column_id){

								switch(filter.operator){
									case '=':
										if (field[filter.column_attribute] == filter.attribute_value) include = true;
									break;
								}
							}
						});

						if (include) pre_data.push(row);
					});
				});				
			}
			
			table_data = pre_data;

			next_row_id++;
			options.nextRowId = next_row_id;
		}
		
		var initSort = function(){
			if (!options.sortable || sort_column < 0){
				return false;
			}

			var order = sort_order === 'desc' ? -1 : 1;

			table_data.sort(function(a,b){
				var aa = a[sort_column].el_formatted_value ? a[sort_column].el_formatted_value : a[sort_column].el_value,
					bb = b[sort_column].el_formatted_value ? b[sort_column].el_formatted_value : b[sort_column].el_value;

				if (aa == null) aa = '';
				if (bb == null) bb = '';
					
                if ($.isNumeric(aa) && $.isNumeric(bb)) {
                    aa = parseFloat(aa);
                    bb = parseFloat(bb);
                    if (aa < bb) {
                        return order * -1;
                    }
                    return order;
                }
				
                if (aa === bb) {
                    return 0;
                }

                if (typeof aa !== 'string') {
                    aa = aa.toString();
                }

                if (aa.localeCompare(bb) === -1) {
                    return order * -1;
                }

                return order;
			});
		}
		
		var initToolbar = function(){
			var html = [];
			
			$toolbar.html('');
			if (typeof options.toolbar === 'string' || typeof options.toolbar === 'object') {
				$(sprintf('<div class="bs-bars pull-%s"></div>', options.toolbarAlign))
					.appendTo($toolbar)
					.append($(options.toolbar));
			}

			html = [sprintf('<div class="columns columns-%s btn-group pull-%s">', options.buttonsAlign, options.buttonsAlign)];
			
			if (typeof options.icons === 'string') {
				options.icons = calculateObjectValue(null, options.icons);
			}

			if (options.showPaginationSwitch) {
				html.push(sprintf('<button class="btn' +
						sprintf(' btn-%s', options.buttonsClass) +
						sprintf(' btn-%s', options.iconSize) +
						'" type="button" name="paginationSwitch" aria-label="pagination Switch" title="%s">',
						RSCalc.options.formatPaginationSwitch()),
					sprintf('<i class="%s %s"></i>', options.iconsPrefix, options.icons.paginationSwitchDown),
					'</button>');
			}
			
			if (options.showRefresh) {
				html.push(sprintf('<button class="btn' +
						sprintf(' btn-%s', options.buttonsClass) +
						sprintf(' btn-%s', options.iconSize) +
						'" type="button" name="refresh" aria-label="refresh" title="%s">',
						RSCalc.options.formatRefresh()),
					sprintf('<i class="%s %s"></i>', options.iconsPrefix, options.icons.refresh),
					'</button>');
			}
			
		   if (options.showToggle) {
				html.push(sprintf('<button class="btn' +
						sprintf(' btn-%s', options.buttonsClass) +
						sprintf(' btn-%s', options.iconSize) +
						'" type="button" name="toggle" aria-label="toggle" title="%s">',
						RSCalc.options.formatToggle()),
					sprintf('<i class="%s %s"></i>', options.iconsPrefix, options.icons.toggle),
					'</button>');
			}

			if (options.addRecord > 0) {
				html.push(sprintf('<button class="btn' +
						sprintf(' btn-%s', options.buttonsClass) +
						sprintf(' btn-%s', options.iconSize) +
						'" type="button" name="addRecord" aria-label="file" title="%s">',
						RSCalc.options.formatAddRecord()),
					sprintf('<i class="%s %s"></i>', options.iconsPrefix, options.icons.addRecord),
					'</button>');
			}
			
			if (options.deleteRecord) {
				html.push(sprintf('<button class="btn' +
						sprintf(' btn-%s', options.buttonsClass) +
						sprintf(' btn-%s', options.iconSize) +
						'" type="button" name="deleteRecord" aria-label="file" title="%s">',
						RSCalc.options.formatDeleteRecord()),
					sprintf('<i class="%s %s"></i>', options.iconsPrefix, options.icons.deleteRecord),
					'</button>');
			}

			if (options.editRecord) {
				html.push(sprintf('<button class="btn' +
						sprintf(' btn-%s', options.buttonsClass) +
						sprintf(' btn-%s', options.iconSize) +
						'" type="button" name="editRecord" aria-label="file" title="%s">',
						RSCalc.options.formatEditRecord()),
					sprintf('<i class="%s %s"></i>', options.iconsPrefix, options.icons.editRecord),
					'</button>');
			}

			html.push('</div>');
			
			if (html.length > 2) {
				$toolbar.append(html.join(''));
			}
			
			var addTableRow = function(){
			
				var next_row_id = options.nextRowId;

				$(this).find(':data(element.options)').each(function(){
					
					var opts = $(this).data('element.options'),
						field_name = opts.column_tag + next_row_id;
						table_cell = {},
						new_field = {};

					table_cell.field_column_id = opts.column_tag;
					table_cell.field_row_id = next_row_id;
					table_cell.f_data_type = opts.el_type;
					table_cell.el_field_name = opts.column_tag + next_row_id;
					table_cell.roi_id = getQueryVariable('roi');
					table_cell.version_id = opts.version_id;
					
					new_field.roi_id = getQueryVariable('roi');
					new_field.el_field_name = opts.column_tag + next_row_id;
					new_field.f_data_type = opts.el_type;
					new_field.f_text = opts.f_text;
					new_field.choice_id = opts.choice_id;
					new_field.el_formula = opts.el_formula;
					new_field.el_value = opts.el_value;
					new_field.el_formatted_value = opts.el_formatted_value;
					new_field.f_format = opts.f_format;
					new_field.version_id = opts.version_id;					

					if(!RSCalc.fields[field_name]){
						RSCalc.fields[field_name] = new_field;
					}
					
					var store_array = [];
					store_array.push(new_field);
					
					table_columns = $element.data('element.options').columns;
					$.each(table_columns, function(){
						if(this.column_tag == opts.column_tag){
							if(!this.cells) this.cells = [];
							this.cells.push(table_cell);
						}
					});
					
					RSCalc.storeOption(store_array);
					$.post("assets/ajax/calculator.post.php",{
							action: 'storeTableCell',
							roi: getQueryVariable('roi'),
							cell: table_cell
						}, function(){});
				});
				
				$.each(RSCalc.renderedElements['table'], function(){
					RSCalc.redraw($(this));
				});
			}

			var addRecord = function(){
				
				var tbl = $element,
					tbl_opts = tbl.data('element.options'),
					tbl_cols = tbl_opts.columns;
				
				var modal = [];

				modal.push(
					'<div class="modal inmodal fade in">',
						'<div class="modal-dialog modal-lg">',
							'<div class="modal-content animated fadeIn">',
								'<div class="modal-header">',
									'<button type="button" class="close" data-dismiss="modal">',
										'<span aria-hidden="true">&times;</span>',
										'<span class="sr-only">Close</span>',
									'</button>',
									'<i class="fa fa-user modal-icon"></i>',
									'<h4 class="modal-title">Add New Table Row</h4>',
								'</div>',
								'<div class="modal-body">',
								'</div>',
								'<div class="modal-footer">',
									'<button class="btn btn-small btn-success" addTableRow>Add</button>',
									'<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>',
								'</div>',
							'</div>',
						'</div>',
					'</div>');
					
				$modal = $(modal.join(''));
				
				$modal.find('[addTableRow]').off('click').on('click', $.proxy(addTableRow, $modal));
				
				$modal_body = $modal.find('.modal-body');
				$.each(tbl_cols, function(){
					
					switch(this.el_type){
						
						case 'input':
							this.el_label_class = 'control-label col-lg-4';
							this.el_class = 'col-lg-8';

							$container = RSCalc.buildInput(this);
							$container.data('element.options', this);
							
							$container.find('input').off('change').on('change', inputChange);	
							
							$modal_body.append($container);
						break;
						
						case 'select':
							this.el_label_class = 'control-label col-lg-4';
							this.el_class = 'col-lg-8';

							$container = RSCalc.buildDropdown(this);
							$container.data('element.options', this);
							
							this.el_value = this.choices[0].choice_value;
							this.el_formatted_value = this.choices[0].choice_formatted_value;
							
							$dropdown = $container.find('select');
							
							$dropdown.chosen({width: '100%', disable_search_threshold: 10});
							$dropdown.off('change').on('change', onDropdownChange);	
							
							$modal_body.append($container);							
						break;
					}
				});
				
				$modal.modal('show');
			}
			
			var inputChange = function(){
				
				var val = $(this).val(),
					formatted = val;

				if (options.el_format){
					formatted = numeral().unformat(val);
				}

				var opts = $(this).closest(':data(element.options)').data('element.options');
				
				opts.el_value = val;
				opts.el_formatted_value = formatted;
			}
			
			var onDropdownChange = function(){
				
				var selected = $(this).find('option:selected')[0],
					index = selected.index,
					val = selected.value,
					text = selected.text;				
				
				var opts = $(this).closest(':data(element.options)').data('element.options');
				
				opts.el_value = val;
				opts.el_formatted_value = text;
			}
			
			if (options.addRecord > 0) {
				$toolbar.find('button[name="addRecord"]').off('click').on('click', addRecord);
			}
		}
		
		var initPagination = function(){
			if (!options.pagination) {
				pageFrom = 1;
				pageTo = table_data.length;
				$pagination.hide();
				return;
			} else {
				$pagination.show();
			}	
			
			var html = [];
			totalRows = table_data.length;

			totalPages = 0;
			if (totalRows) {
				if (pageSize === RSCalc.options.formatAllRows()) {
					pageSize = totalRows;
					$allSelected = true;
				} else if (pageSize === totalRows) {
					var pageLst = typeof pageList === 'string' ?
						RSCalc.options.pageList.replace('[', '').replace(']', '')
							.replace(/ /g, '').toLowerCase().split(',') : options.pageList;
					if ($.inArray(this.options.formatAllRows().toLowerCase(), pageLst)  > -1) {
						$allSelected = true;
					}
				}

				totalPages = ~~((totalRows - 1) / pageSize) + 1;
			}
			if (totalPages > 0 && iPageNumber > totalPages) {
				iPageNumber = totalPages;
			}

			pageFrom = (iPageNumber - 1) * pageSize + 1;
			pageTo = iPageNumber * pageSize;
			if (pageTo > totalRows){
				pageTo = totalRows;
			}

			html.push(
				'<div class="pull-' + options.paginationDetailHAlign + ' pagination-detail">',
				'<span class="pagination-info">',
				options.onlyInfoPagination ? RSCalc.options.formatDetailPagination(totalRows) :
				RSCalc.options.formatShowingRows(pageFrom, pageTo, totalRows),
				'</span>');

			if (!options.onlyInfoPagination) {
				html.push('<span class="page-list">');

				var pageNumber = [
						sprintf('<span class="btn-group %s">',
							options.paginationVAlign === 'top' || options.paginationVAlign === 'both' ?
								'dropdown' : 'dropup'),
						'<button type="button" class="btn' +
						sprintf(' btn-%s', options.buttonsClass) +
						sprintf(' btn-%s', options.iconSize) +
						' dropdown-toggle" data-toggle="dropdown">',
						'<span class="page-size">',
						$allSelected ? RSCalc.options.formatAllRows() : pageSize,
						'</span>',
						' <span class="caret"></span>',
						'</button>',
						'<ul class="dropdown-menu" role="menu">'
					];

				if (typeof options.pageList === 'string') {
					var list = options.pageList.replace('[', '').replace(']', '')
						.replace(/ /g, '').split(',');

					pageList = [];
					$.each(list, function (i, value) {
						pageList.push(value.toUpperCase() === RSCalc.options.formatAllRows().toUpperCase() ?
							RSCalc.options.formatAllRows() : +value);
					});
				}

				$.each(pageList, function (i, page) {
					if (!RSCalc.options.smartDisplay || i === 0 || pageList[i - 1] < RSCalc.options.totalRows) {
						var active;
						if ($allSelected) {
							active = page === RSCalc.options.formatAllRows() ? ' class="active"' : '';
						} else {
							active = page === RSCalc.options.pageSize ? ' class="active"' : '';
						}
						pageNumber.push(sprintf('<li role="menuitem"%s><a href="#">%s</a></li>', active, page));
					}
				});
				pageNumber.push('</ul></span>');

				html.push(RSCalc.options.formatRecordsPerPage(pageNumber.join('')));
				html.push('</span>');

				html.push('</div>',
					'<div class="pull-' + options.paginationHAlign + ' pagination">',
					'<ul class="pagination' + sprintf(' pagination-%s', options.iconSize) + '">',
					'<li class="page-pre"><a href="#">' + options.paginationPreText + '</a></li>');

				if (totalPages < 5) {
					from = 1;
					to = totalPages;
				} else {
					from = iPageNumber - 2;
					to = from + 4;
					if (from < 1) {
						from = 1;
						to = 5;
					}
					if (to > totalPages) {
						to = totalPages;
						from = to - 4;
					}
				}

				if (totalPages >= 6) {
					if (iPageNumber >= 3) {
						html.push('<li class="page-number' + (1 === iPageNumber ? ' active' : '') + '">',
							'<a href="#">', 1, '</a>',
							'</li>');

						from++;
					}

					if (iPageNumber >= 4) {
						if (iPageNumber == 4 || totalPages == 6 || totalPages == 7) {
							from--;
						} else {
							html.push('<li class="page-first-separator disabled">',
								'<a href="#">...</a>',
								'</li>');
						}

						to--;
					}
				}

				if (totalPages >= 7) {
					if (iPageNumber >= (totalPages - 2)) {
						from--;
					}
				}

				if (totalPages == 6) {
					if (iPageNumber >= (totalPages - 2)) {
						to++;
					}
				} else if (totalPages >= 7) {
					if (totalPages == 7 || iPageNumber >= (totalPages - 3)) {
						to++;
					}
				}

				for (i = from; i <= to; i++) {
					html.push('<li class="page-number' + (i === iPageNumber ? ' active' : '') + '">',
						'<a href="#">', i, '</a>',
						'</li>');
				}

				if (totalPages >= 8) {
					if (iPageNumber <= (totalPages - 4)) {
						html.push('<li class="page-last-separator disabled">',
							'<a href="#">...</a>',
							'</li>');
					}
				}

				if (totalPages >= 6) {
					if (iPageNumber <= (totalPages - 3)) {
						html.push('<li class="page-number' + (totalPages === iPageNumber ? ' active' : '') + '">',
							'<a href="#">', totalPages, '</a>',
							'</li>');
					}
				}

				html.push(
					'<li class="page-next"><a href="#">' + options.paginationNextText + '</a></li>',
					'</ul>',
					'</div>');
			}
			$pagination.html(html.join(''));

			$pageList = $pagination.find('.page-list a');
			$pre = $pagination.find('.page-pre');
			$next = $pagination.find('.page-next');
			$number = $pagination.find('.page-number');

			if (options.smartDisplay) {
				if (totalPages <= 1) {
					$pagination.find('div.pagination').hide();
				}
				if (pageList.length < 2 || totalRows <= pageList[0]) {
					$pagination.find('span.page-list').hide();
				}
				$pagination[table_data.length ? 'show' : 'hide']();
			}

			if (!options.paginationLoop) {
				if (iPageNumber === 1) {
					$pre.addClass('disabled');
				}
				if (iPageNumber === totalPages) {
					$next.addClass('disabled');
				}
			}

			if ($allSelected) {
				pageSize = RSCalc.options.formatAllRows();
			}
			$pageList.off('click').on('click', onPageListChange);
			$pre.off('click').on('click', onPagePre);
			$next.off('click').on('click', onPageNext);
			$number.off('click').on('click', onPageNumber);			
		}
		
		var updatePagination = function(event){
			if (event && $(event.currentTarget).hasClass('disabled')){
				return;
			}
			
			initPagination();
			initBody();
		}
		
		var onPageListChange = function(event){
			event.preventDefault();
			var $this = $(event.currentTarget);

			$this.parent().addClass('active').siblings().removeClass('active');
			pageSize = $this.text().toUpperCase() === RSCalc.options.formatAllRows().toUpperCase() ?
				RSCalc.options.formatAllRows() : +$this.text();
			$toolbar.find('.page-size').text(pageSize);

			updatePagination(event);
			return false;
		}
		
		var onPagePre = function(event){
			event.preventDefault();
			if ((iPageNumber - 1) === 0) {
				iPageNumber = options.totalPages;
			} else {
				iPageNumber--;
			}
			updatePagination(event);
			return false;
		}
		
		var onPageNext = function(event){
			event.preventDefault();
			if ((iPageNumber + 1) > options.totalPages) {
				iPageNumber = 1;
			} else {
				iPageNumber++;
			}
			updatePagination(event);
			return false;
		}
		
		var onPageNumber = function(event){
			event.preventDefault();
			if (iPageNumber === +$(event.currentTarget).text()) {
				return;
			}
			iPageNumber = +$(event.currentTarget).text();
			updatePagination(event);
			return false;			
		}
		
		var initBody = function(){
			var data = table_data;				
			$body = $element.find('.fixed-table-body > table > tbody').html('');

			var hasTr, $tr, $td, renderedElements = [];

			for (var i = pageFrom - 1; i < pageTo; i++) {
				$row = $('<tr></tr>');
				
				for(a in data[i]){
					var opts = data[i][a],
						$cell = $('<td></td>').data('element.options', opts);

					if (options.columns[a].visible == 0){
						$cell.hide();
					}
					switch(opts.f_data_type){
						case 'html':
							$cell.html(opts.el_formatted_value ? opts.el_formatted_value : opts.el_value);
						break;

						case 'input':
							$cell.html(opts.el_formatted_value ? opts.el_formatted_value : opts.el_value);
							$cell.addClass('editable');
						break;
						
						case 'modal':
							$cell.html('<a>Link to Use Case</a>');
							
							var displayModal = function(){
								
								var current_roi	= getQueryVariable('roi'),
									action 		= 'getchildren',
									element		= $(this).closest('td').data('element.options').el_field_name,
									ajax_url 	= 'assets/ajax/calculator.get.php';
								
								$.get( ajax_url, { action: action, roi: current_roi, element: element } )
									.done(function(elements){
										
										elements = JSON.parse(elements);

										var $modal = $([
											'<div class="modal fade" style="overflow-y:auto;">',
												'<div class="modal-dialog modal-lg">',
													'<div class="modal-content animated fadeIn">',
														'<div class="modal-body">',
														'</div>',
													'</div>',
												'</div>',
											'</div>'
										].join(''));
										
										var $modal_body = $modal.find('.modal-body');

										$.each(elements.structure, function(i, options){
											RSCalc.renderElement(options, $modal_body);
										});
										
										$modal.modal('show');
									});
							}
							
							$cell.off('click').on('click', displayModal);
						break;
						
						case 'select':
							$cell.html(opts.el_formatted_value ? opts.el_formatted_value : opts.el_value);
							$cell.addClass('editable');
						break;

						case 'text':
							$cell.html(opts.el_formatted_value ? opts.el_formatted_value : opts.el_value);
							$cell.addClass('editable');
						break;
						
						case 'toggle':
							var $toggle = $([
								'<div class="pretty p-icon p-toggle">',
									'<input type="checkbox"',
									opts.el_value == 1 ? ' checked="checked">' : '>',
								'</div>'
							].join(''));

							var $choices = [];
							if (opts.choices){
								$.each(opts.choices, function(count, choice){
									$choices.push(sprintf('<div class="%s">%s</div>', choice.choice_class, choice.choice_text))
								});
								$choices.join('');								
							}
							
							$toggle.append($choices);
							$cell.append($toggle);
							
							var toggleToggle = function(){
								var field_name = $(this).closest('td').data('element.options').el_field_name,
									value = $(this).is(':checked') ? 1 : 0,
									formatted = RSCalc.fields[field_name].choices[value].choice_formatted_value;

								RSCalc.setValue(field_name, value, formatted);

								$.each(RSCalc.renderedElements['table'], function(){
									RSCalc.redraw($(this));
								});
							}
							
							$toggle.find('input').off('change').on('change', toggleToggle);
						break;
					}

					$row.append($cell);
				}

				$body.append($row);
			}

			if (!table_data.length) {
				$body.append('<tr class="no-records-found">' +
					sprintf('<td colspan="%s">%s</td>',
					$header.find('th').length,
					RSCalc.options.formatNoMatches()) +
					'</tr>');
			}			
		}
		
		var initEditing = function(){
			
			var Mode = {
				edit: function(td){
					
					var $cell = $(td),
						options = $cell.data('element.options'),
						value = options.el_value,
						display_value = options.el_formatted_value,
						field_name = options.el_field_name,
						format = options.el_format;

					var $input;

					if (format){
						value = format.indexOf('%') != -1 ? value * 100 : value;
					}

					switch(options.f_data_type){
						
						case 'input':
							$input = $('<input/>')
										.addClass('form-control input-sm')
										.val(value);
								
								$(td).html($input);
							break;
						
						case 'text':
							$input = $('<textarea style="resize: vertical;"></textarea>')
										.addClass('form-control')
										.html(value);
							
							$input.height($input.scrollHeight);
							$(td).html($input);
							
							$input.height($input[0].scrollHeight);
							break;
							
						case 'select':
							var $select = $('<select/>');

							if (options.choices){
								var choices = options.choices,
									selections = [];
								
								$.each(choices, function(i, choice){
									selections.push(sprintf('<option value="%s"%s>%s</option>', choice.choice_value, (choice.choice_value === options.el_value ? ' selected="selected"' : ''), choice.choice_text));
								});
								selections = selections.join('');
								
								$select.append($(selections));
							}
							
							$(td).html($select);
							
							$select.chosen({width: '100%', disable_search_threshold: 10});
							break;
							
					}
					
					if ($input) $input.select();
					$cell.addClass('editing').removeClass('editable');
				},
				view: function(td){
					console.log(td);
					$(td).each(function() {
						var $cell = $(this),
							options = $cell.data('element.options'),
							field_name = options.el_field_name,
							format = options.el_format,
							val;

						$cell.addClass('editable').removeClass('editing');
						
						switch(options.f_data_type){
							
							case 'input':
								val = $(this).find('input').val();
								RSCalc.setValue(field_name, val, val);
								
								$.each(RSCalc.renderedElements['table'], function(){
									RSCalc.redraw($(this));
								});
								
								$(this).html(val);
								break;
								
							case 'text':
								var val = $(this).find('textarea').val();
								RSCalc.setValue(field_name, val, val);
								
								$.each(RSCalc.renderedElements['table'], function(){
									RSCalc.redraw($(this));
								});
								
								$(this).html(val);
								break;

							case 'select':
								var selected = $(this).find('select > option:selected')[0],
									index = selected.index,
									val = selected.value,
									text = selected.text;

								RSCalc.setValue(field_name, val, text);
								
								$.each(RSCalc.renderedElements['table'], function(){
									RSCalc.redraw($(this));
								});								
								
								$(this).html(val);
								break;
						}
					});
					
					//RSCalc.updateGraphs();
					//RSCalc.storeOptions();
				}
			};
			
			$table.on('click', 'td.editable', function(event){
				if (event.handled !== true){
					event.preventDefault();
					
					Mode.view($('td.editing'));
					Mode.edit(this);
					
					event.handled = true;
				}
			});
			
			$(document).on('click', function(event){
				var table = $element.find('.fixed-table-body > table');
				if (!table.is(event.target) && table.has(event.target).length === 0){
					var editors = table.find('.editing');
					if (editors.length) Mode.view(editors);
				}
			});
		}
		
		var setVisibility = function(){
			var columns = options.columns;
			
			$.each(columns, function(i, col){
				if (this.visible == 0){
					$element.find('.fixed-table-body > table > tbody > tr').find('td:eq(' + i + ')').hide();
					$element.find('.fixed-table-body > table > thead > tr').find('th:eq(' + i + ')').hide();
				}
			});
		}
		
		setTableData();
		initSort();
		initToolbar();
		initPagination();
		initBody();
		initEditing();
		setVisibility();
	}
	
	RoiShopCalculator.prototype.setVisibility = function(){
		var	RSCalc = this,
			filters = this.options.filters;
		
		var elementAction = function(element, action){
			switch(action){
				case 'show':
					$(element).show();
				break;
				
				case 'hide':
					$(element).hide();
				break;
			}
		}
		
		if(filters){
			$.each(filters, function(){
				switch(this.control_operator){
					case '=':
						if(RSCalc.fields[this.control_element][this.control_element_attribute] == this.control_attribute_value){
							elementAction(this.element_id, this.element_action);
						}
					break;
				}
			});
		}
	}
	
	RoiShopCalculator.prototype.setScroll = function(){
		var RSCalc = this;
		var scrollTo = function(){
			var target = $(this.hash);
			if (target.length){
				target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
				$('html, body').animate({
					scrollTop: target.offset().top - 63
				}, 1000);
			};

			var parent = $(this).parent();
			$('#side-menu').find('li').each(function(){
				if ($(this) !== parent) $(this).removeClass('active');
			});
			$(this).parent().addClass('active');

			var navigation = $(this).parent().data('navigation.data');
			if (navigation){
				if (navigation.nav_show){
					var show_elements = navigation.nav_show.split(",");

					if (show_elements){								
						$.each(show_elements, function(){
							if ($('[element-id="' + this + '"')){
								$('[element-id="' + this + '"').show();								
							}
						});
					}
				}
				
				if (navigation.nav_hide){
					var hide_elements = navigation.nav_hide.split(",");
	
					if (hide_elements){
						$.each(hide_elements, function(){
							if ($('[element-id="' + this + '"')){
								$('[element-id="' + this + '"').hide();
								console.log($('[element-id="' + this + '"'));
							}
						});					
					}
				}

				if (navigation.report_id && navigation.report_id != 10){
					RSCalc.createPdf(navigation.report_id);
				}
				
				if (navigation.report_id == 10){
					RSCalc.downloadCSV({filename: "stock-data.csv"});
				}
			}
			
			return false;
		}
		
		$('.smooth-scroll a').off('click').on('click', scrollTo);
		$('a.smooth-scroll').off('click').on('click', scrollTo);
	}
	
	RoiShopCalculator.prototype.createPdf = function(reportId){
		
		$.ajax({
			type: "POST",
			url: "assets/ajax/calculator.post.php",
			data: {
				action: "createpdf",
				roi: getQueryVariable('roi'),
				reportId: reportId
			},
			success: function(returned){
				$('<a href="/webapps/assets/customwb/10016/pdf/preview-' + reportId + '.pdf" download>')[0].click();
			},
			error: function(error){

			}
		});		
	}	
	
	RoiShopCalculator.prototype.downloadCSV = function(args){
		var stockData = this.options.fields
		var csvData = [];
		
		$.each(stockData, function(){
			var data = {};
			
			data.text = String(this.f_text).replace(/\,/g,";");
			data.value = String(this.el_value).replace(/\,/g,";");
			data.formattedValue = String(this.el_formatted_value).replace(/\,/g,";");
			
			csvData.push(data);
		});
		
		var data, filename, link;
        var csv = this.convertArrayOfObjectsToCSV({
            data: csvData
        });
        if (csv == null) return;

        filename = args.filename || 'export.csv';

        if (!csv.match(/^data:text\/csv/i)) {
            csv = 'data:text/csv;charset=utf-8,' + csv;
        }
        data = encodeURI(csv);

        link = document.createElement('a');
        link.setAttribute('href', data);
        link.setAttribute('download', filename);
        link.click();		
	}
	
	RoiShopCalculator.prototype.convertArrayOfObjectsToCSV = function(args){
 
        var result, ctr, keys, columnDelimiter, lineDelimiter, data;

        data = args.data || null;
        if (data == null || !data.length) {
            return null;
        }

        columnDelimiter = args.columnDelimiter || ',';
        lineDelimiter = args.lineDelimiter || '\n';

        keys = Object.keys(data[0]);

        result = '';
        result += keys.join(columnDelimiter);
        result += lineDelimiter;

        data.forEach(function(item) {
            ctr = 0;
            keys.forEach(function(key) {
                if (ctr > 0) result += columnDelimiter;

                result += item[key];
                ctr++;
            });
            result += lineDelimiter;
        });

        return result;
    }

	RoiShopCalculator.prototype.setValue = function(field, value, formatted) {
		var RSCalc = this,
			cell = $(this.options.calcHolder).calx('getCell', field),
			changed_field = this.fields[field],
			array = [];
	
		if(changed_field){
			if (changed_field.elements){
				$.each(changed_field.elements, function(){
					var opts = $(this).data('element.options'),
						calx = $(RSCalc.options.calcHolder).calx('getCell', opts.el_calx_cell);

					if (calx){
						calx.setValue(value).renderComputedValue();
					}
				});					
			}

			changed_field.el_value = value;
			changed_field.el_formatted_value = formatted;
			
			array.push(changed_field);
			this.storeOption(array);
			
			this.setVisibility();			
		}
	}
	
	RoiShopCalculator.prototype.storeOption = function(option){
		var RSCalc = this;

		var changed_field = $.map(option, function(n, i){
			return $.extend({}, n, {elements: null});
		});

		$.post("assets/ajax/calculator.post.php",{
				action: 'storeRoiOption',
				roi: getQueryVariable('roi'),
				fields: changed_field
			}, function(callback){

			});		
	}
	
	RoiShopCalculator.prototype.storeOptions = function() {
		var options = this.options,
			fields = this.fields;

		var fields_to_store = [];
		
		for (a in fields){
			for (b in fields[a]){
				fields_to_store.push(fields[a][b]);
			}
		}

		var fields_to_store = $.map(fields_to_store, function(n, i){
			return $.extend({}, n, {elements: null});
		});
		
		$.post("assets/ajax/calculator.post.php",{
			action: 'storeRoiOption',
			roi: getQueryVariable('roi'),
			fields: fields_to_store
		}, function(callback){
console.log(callback);
		});
		
/* 		$.each(fields_to_store, function(index, array){
			
			var option_to_store = [];
			option_to_store.push(array);

			$.post("assets/ajax/calculator.post.php",{
				action: 'storeRoiOption',
				roi: getQueryVariable('roi'),
				fields: option_to_store
			}, function(callback){

			});
		}); */
	}
	
	RoiShopCalculator.prototype.tooltip = function($element, options){
		var RSCalc = this,
			html = [];

		html.push(
			sprintf('<span class="input-group-addon input right helper %s">', options.el_enabled == 1 ? '' : 'output'));

		if (options.el_formula){
			html.push('<i class="fa fa-calculator" data-placement="right" title="Click here to view the calculation breakdown" calculation/>');
		}

		if (options.el_tooltip){
			html.push(
				sprintf('<i style="margin-left: 5px;" class="fa fa-question-circle tooltipstered" data-placement="right" title="%s"/>', options.el_tooltip));
		}

		html.push('<span/>');
		var $tooltip = $(html.join(''));
			
		$element.after($tooltip);
			
		$tooltip.find('.tooltipstered').tooltipster({
			theme: 'tooltipster-light',
			maxWidth: 300,
			animation: 'grow',
			position: 'right',
			arrow: false,
			interactive: true,
			contentAsHTML: true				
		});
			
		var $calculator = $tooltip.find('[calculation]');
			
		$calculator.tooltipster({
			theme: 'tooltipster-light',
			maxWidth: 300,
			animation: 'grow',
			position: 'right',
			arrow: false,
			interactive: true,
			contentAsHTML: true				
		});
		
		var calculationBreakdown = function(){
			var html = [],
				element_opts,
				has_dependencies,
				formula_text,
				dependencies = $('#wrapper').calx('getCell', options.el_field_name).dependencies;

			html.push('<div class="form-group"><div class="panel panel-default">');
			
			html = inputDependency(html, dependencies);
			
			html.push('</div></div>');
			
			var $modal = $('<div class="modal inmodal fade in"></div>');
				
			var $container = $([
				'<div class="modal-dialog modal-lg">',
					'<div class="modal-content animated fadeIn">',
					'</div>',
				'</div>'
			].join(''));
				
			var $modalContent = $container.find('.modal-content');
			var $modalBody = $([
				'<div class="modal-body">',
				sprintf('<h1 class="rsmodal-title">%s</h1><hr/>', options.el_text),
				'</div>'
			].join(''));
				
			$modalContent.append($modalBody);
			$modalBody.append($(html.join('')));
				
			var formula_txt = String(options.el_formula);
				
			for(dependent in dependencies) {
				element_opts = $.extend(true, {}, RSCalc.values[dependent]);
				if (formula_txt) formula_txt = formula_txt.replace(dependent, element_opts.el_text);
			}
				
			$modalBody.append(	'<div class="form-group" style="margin-bottom: 0;"><h4>Equation</h4></div>\
								<div class="form-group">\
									<label class="control-label col-lg-12 col-md-12 col-sm12">' + formula_txt + ' </label>\
								</div>');
				
			var $modalFooter = $([
				'<div class="modal-footer">',
					'<div class="rsmodal-buttonwrapper">',
						'<button type="button" class="btn rsmodal-button rsmodal-cancel" data-dismiss="modal">Close</button>',
					'</div>',
				'</div>'
			].join(''));
			$modalContent.append($modalFooter);
			$modal.append($container);
				
			$delete = $modalFooter.find('[delete]');
			$delete.off('click').on('click', $.proxy(this.deleteRoi, this));
			
			$modal.modal('show');
			$modal.on('hidden.bs.modal', function(){
				$modal.remove();
			});
		}
		
		var inputDependency = function(html, dependencies){
			for (dependent in dependencies){
				has_dependencies = $.isEmptyObject(dependencies[dependent].dependencies);

				element_opts = $.extend(true, {}, RSCalc.values[dependent]);
				current_value = $('#wrapper').calx('getCell', dependent).getFormattedValue() || numeral(0).format(element_opts.el_format);

				html.push(
					'<div class="panel-heading">',
						sprintf('<h5 class="panel-title collapsed" href="#panel%s" data-toggle="collapse">', dependent),
							sprintf('<%s style="margin-bottom: 0;">%s %s', has_dependencies ? 'p' : 'a' , has_dependencies ? '' : '<i class="fa fa-plus-circle" style="color: blue;"></i>', element_opts.el_text),
							sprintf('<span class="pull-right">%s</span>', current_value),
							sprintf('</%s>', has_dependencies ? 'p' : 'a'),						
						'</h5>',
					'</div>');

			}
			
			if (!has_dependencies){
				html.push(
					sprintf('<div id="panel%s" class="panel-collapse collapse" style="height: 0px; margin: 0 0 0 15px; padding: 10px; background-color: #eee; border-left: 3px solid blue;">', dependent));

				var cur = dependencies[dependent],
					formula_txt = element_opts.el_formula;
					
				html.push(inputDependency(html, cur.dependencies));
				
				for (dependent in cur.dependencies){
					element_opts = $.extend(true, {}, RSCalc.values[dependent]);
					console.log(element_opts);
					if (formula_txt) formula_txt = formula_txt.replace(dependent, element_opts.el_text);
				}
					
				html.push(sprintf('<div style="margin: 15px;"><strong>Equation: %s</strong></div>', formula_txt));
					
				html.push('</div>');
			}
			
			return html;
		}
		
		$calculator.off('click').on('click', calculationBreakdown);		
	}
	
 	RoiShopCalculator.prototype.cleanUpCells = function() {
		var duplicated_cells = 1,
			new_cell_name,
			cells_created = [];
			
		var $cells = $('#roiContent').find('[data-cell]');
			
		$.each($cells, function(){
			$(this).attr('data-cell', 'RSCALX' + duplicated_cells);
			$(this).closest(':data(element.options)').data('element.options').el_calx_cell = 'RSCALX' + duplicated_cells;
			duplicated_cells++;
		});
	}
	
	RoiShopCalculator.prototype.redraw = function($element){
		var options = $element.data('element.options'),
			element_type = options.el_type;
			
		this.renderedElements[element_type] = $.map(this.renderedElements[element_type], function(item, index){
			if( item[0] === $element[0] ) return null;
			return item;
		});
		
		$holder = $('<div>');
		$element.replaceWith($holder);

		this.renderElement(options, $holder, false);
	}
	
	RoiShopCalculator.prototype.setupCalculator = function() {
		
		$(this.options.calcHolder).calx({
			autoCalculate: false
		});

		$(this.options.calcHolder).calx('getSheet').calculate();
	}
	
	RoiShopCalculator.prototype.logElement = function($element, options){
		
		if ($element) $element.attr('id', options.el_id);
		
		if ($element && options) $element.data('element.options', options);
		
		if (options.el_type){
			if (!this.renderedElements[options.el_type]) this.renderedElements[options.el_type] = [];
			this.renderedElements[options.el_type].push($element);			
		}
		
		var field = options.el_field_name;
		if (field && this.fields[field]){
			if (!this.fields[field].elements) this.fields[field].elements = [];
			this.fields[field].elements.push($element);			
		}
	}
	
	var allowedMethods = [
		'updateGraphs','equalizeHeights',
		'setupCalculator','renderGraphs',
		'storeOptions', 'setOptions','getOptions','storeRoiArray'
	];
	
	$.fn.roishopCalculator = function(option) {

        var value,
            args = Array.prototype.slice.call(arguments, 1);

        this.each(function () {
            var $this = $(this),
                data = $this.data('roishop.calculator'),
                options = $.extend({}, RoiShopCalculator.DEFAULTS, $this.data(),
                    typeof option === 'object' && option);

            if (typeof option === 'string') {
				if ($.inArray(option, allowedMethods) < 0) {
                    throw new Error("Unknown method: " + option);
                }

                value = data[option].apply(data, args);

                if (option === 'destroy') {
                    $this.removeData('roishop.calculator');
                }
            }

            if (!data) {
                $this.data('roishop.calculator', (data = new RoiShopCalculator(this, options)));
            }
        });

        return typeof value === 'undefined' ? this : value;
    };

})(window.jQuery || window.Zepto, window, document);

var sprintf = function (str) {
	var args = arguments,
		flag = true,
		i = 1;

	str = str.replace(/%s/g, function () {
		var arg = args[i++];

		if (typeof arg === 'undefined') {
			flag = false;
			return '';
		}
		return arg;
	});
	
	return flag ? str : '';
};

var getBaseUrl = function(){
	return window.location.href.match(/^.*\//);
};

var getRootUrl = function(){
	return window.location.origin?window.location.origin+'/':window.location.protocol+'/'+window.location.host+'/';
};