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
		pagination: true,
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
		addRecord: true,
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
	
	RoiShopCalculator.DEFAULTS = {
		calcHolder: '#wrapper',
		equalizer: 'rs-equalize-',
		pullVerificationLink: true,
		userLoggedIn: true,
		manageContributors: true,
		canDeleteContributors: true,
		changeCurrency: true,
		hideSections: false,
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
	
    RoiShopCalculator.EVENTS = {
        'all.rs.dashboard': 'onAll',
        'roi-created.rs.dashboard' : 'onRoiCreated'
    };
	
	RoiShopCalculator.prototype.init = function (){
		this.initLocale();
		this.setupLanguage();
		this.renderContainers();
		this.sideNavigation();
		this.topNavigation();
		this.roiElements();
		this.cleanUpCells();
		this.setupCalculator();
		this.renderGraphs();
		this.updateGraphs();
		this.equalizeHeights();
		this.setScroll();
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
		
		this.el.append(this.$sideNavigation);
		this.el.append(this.$roiCalculator);
		this.$roiCalculator.append(this.$topNavigation);
		this.$roiCalculator.append(this.$roiContent);
		this.$roiCalculator.append(this.$roiFooter);
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
	
	RoiShopCalculator.prototype.setScroll = function(){
		var that = this;
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
							$('[element-id="' + this + '"').data('element.options').el_visibility = 1;
							$('[element-id="' + this + '"').show();
						});
					}
				}
				
				if (navigation.nav_hide){
					var hide_elements = navigation.nav_hide.split(",");
	
					if (hide_elements){
						$.each(hide_elements, function(){
							$('[element-id="' + this + '"').data('element.options').el_visibility = 0;
							$('[element-id="' + this + '"').hide();
						});					
					}
				}
				
				if (navigation.report_id){
					that.createCharts(navigation.report_id);
				}
			}
			
			return false;
		}
		
		$('.smooth-scroll a').off('click').on('click', scrollTo);
		$('a.smooth-scroll').off('click').on('click', scrollTo);
	}
	
	RoiShopCalculator.prototype.createPdf = function(reportId){

		this.storeOptions();

		$.ajax({
			type: "POST",
			url: "/assets/ajax/calculator.post.php",
			data: {
				action: "createpdf",
				roi: getQueryVariable('roi'),
				reportId: reportId,
				roiPath: this.options.roiInfo.roi_full_path.replace('../', getRootUrl()) + '&v=' + this.options.roiInfo.verification_code
			},
			success: function(returned){
				$('<a href="/webapps/assets/customwb/10016/pdf/preview-' + reportId + '.pdf" download>')[0].click();
			},
			error: function(error){
				
			}
		});		
	}
	
	RoiShopCalculator.prototype.createCharts = function(reportId){
		var that = this,
			charts = this.renderedElements.graph.length,
			charts_created = 0;

		this.$pdf = [];
		
		if(this.options.roiSections){
			$.each(this.options.roiSections, function(){
				if(this.formula) charts++;
			});
		}

		if (this.renderedElements.graph){
			$.each(this.renderedElements.graph, function(){
				var chart_id = this.data('element.options').el_id;
				var highchart = this.highcharts();
				var opts = highchart.options;
				opts = $.extend(true, {}, opts);
				delete opts.chart.renderTo;
				delete opts.plotOptions.column.animation.duration;

				opts.credits.enabled = false;

				c_series = this.data('element.options').highchart.series;

				series = $.extend(true, {}, c_series);
				
				series = $.map(series, function(a){
					/* if (a.included == 1) */ return a;
				});			
	
				$.each(series, function(i, series){
					seriesData = [];
					
					$.each(series.formula, function(){
						if(this){
							evaluate = $(that.options.calcHolder).calx('evaluate', (this.formula || this));
							seriesData.push(evaluate);
						}
					});

					if (opts){
						opts.series[i].data = seriesData;
					}
				});

				options = {
					chart: {
						type: 'column',
						height: 280,
						options3d: {
							enabled: true,
							alpha: 0,
							beta: 0,
							depth: 60,
							viewDistance: 10
						},
						backgroundColor: 'transparent',
						marginTop: 5
					},
					credits: {
						enabled: false
					},
					exporting: {
						enabled: false
					},
					title: {
						text: ''
					},
					yAxis: {
						min: 0,
						style: {
							color: '#333',
							fontWeight: 'bold',
							fontSize: '12px',
							fontFamily: 'Trebuchet MS, Verdana, sans-serif'
						},				
						title: {
							text: 'Money'
						}
					},
					tooltip: {
						headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
						pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
							'<td style="color:{series.color};padding:0;padding-left:10px;"><b> {point.y:,.0f}</b></td></tr>',
						footerFormat: '</table>',
						shared: true,
						useHTML: true
					},
					plotOptions: {
						column: {
							pointPadding: 0.2,
							borderWidth: 0
						}
					}
				}			
				
				opts = $.extend(true, options, opts);
				opts.title.text = '';

				$.post('//export.highcharts.com', {
					options: JSON.stringify(opts),
					type: 'png',
					width: 700,
					async: false
				},
				function(img){
					$.post("/assets/ajax/calculator.post.php",{
						action: 'storePdf',
						roi: getQueryVariable('roi'),
						section: chart_id,
						type: 'barchart',
						company: that.options.roiInfo.roi_version_id,
						image: encodeURIComponent('http://export.highcharts.com/' + img)
					}, function(callback){ charts_created++; if (charts == charts_created) { that.createPdf(reportId) } });
				});			
			});			
		}
		
		if(this.options.roiSections){
			$.each(this.options.roiSections, function(){
				var section = this.ID;
				if (this.formula){
					var data = [];
					
					$.each(that.options.roiSections, function(){
						if (this.formula && this.included){
							var series_data = {};
							series_data.y = $(that.options.calcHolder).calx('evaluate', 'SECTIONGRAND(' + this.ID + ')');
							series_data.name = this.Title;
							if (section == this.ID) series_data.sliced = true;
							data.push(series_data);
						}
					});

					var highchart_opts = {
						chart: {
							type: 'pie',
							options3d: {
								enabled: true,
								alpha: 45,
								beta: 0
							},
							backgroundColor: 'transparent',
							marginTop: 0
						},
						credits: {
							enabled: false
						},
						title: {
							text: ''
						},
						plotOptions: {
							pie: {
								slicedOffset: 30,
								depth: 35,
								showInLegend: true,
								dataLabels: {
									enabled: false,
									borderRadius: 1,
									backgroundColor: 'rgba(252, 255, 197, 0.7)',
									color: 'black',
									borderWidth: 1,
									borderColor: '#AAA',
									distance: -15,
								}
							}
						},
						legend: {
							enabled: true,
							layout: 'vertical',
							align: 'right',
							verticalAlign: 'middle'
						},
						series: [{
							data: data
						}]
					};
					
					$.post('//export.highcharts.com', {
						options: JSON.stringify(highchart_opts),
						type: 'png',
						width: 600,
						async: false
					},
					function(img){
						$.post("/assets/ajax/calculator.post.php",{
							action: 'storePdf',
							roi: getQueryVariable('roi'),
							section: section,
							type: 'piechart',
							company: that.options.roiInfo.roi_version_id,
							image: encodeURIComponent('http://export.highcharts.com/' + img)
						}, function(callback){ charts_created++; if (charts == charts_created) { that.createPdf(reportId) } });
					});
				}
			});			
		}
		
		return false;
	}
	
	RoiShopCalculator.prototype.topNavigation = function(){
		var myactions = [];
		if (this.options.pullVerificationLink) {
			myactions.push('<li><a class="showVerificationLink">Show Verification Link</a></li>');
		}
		
		if (this.options.manageContributors) {
			myactions.push('<li><a class="manageContributors">Manage Contributors</a></li>');
		}
		
		if (this.options.changeCurrency) {
			myactions.push('<li><a class="changeCurrency">Change ROI Currency</a></li>')
		}
		
		if (this.options.hideSections){
			//myactions.push('<li><a class="hideSections">Show/Hide Elements</a></li>')
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
		this.$topNavigation.find('.manageContributors').off('click').on('click', $.proxy(this.manageContributors, this));
		this.$topNavigation.find('.changeCurrency').off('click').on('click', $.proxy(this.changeCurrency, this));
		this.$topNavigation.find('.hideSections').off('click').on('click', $.proxy(this.hideSections, this));
		this.$topNavigation.find('.resetTemplate').off('click').on('click', $.proxy(this.resetTemplate, this));
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
	
	RoiShopCalculator.prototype.manageContributors = function() {
		var that = this,
			modal = [];

		modal.push(
			'<div class="modal inmodal fade in">',
				'<div class="modal-dialog">',
					'<div class="modal-content animated fadeIn">',
						'<div class="modal-header">',
							'<button type="button" class="close" data-dismiss="modal">',
								'<span aria-hidden="true">&times;</span>',
								'<span class="sr-only">Close</span>',
							'</button>',
							'<i class="fa fa-user modal-icon"></i>',
							'<h4 class="modal-title">Current Contributors</h4>',
						'</div>',
						'<div class="modal-body">',
							'<table class="table table-body table-no-borders">',
								'<tbody class="contributors">',
								'</tbody>',
							'</table>',
							'<hr/>',
							'<div class="row">',
								'<div class="form-group">',
									'<label class="control-label col-lg-4 col-md-4 col-sm-12">Add a Contributor</label>',
									'<div class="col-lg-8 col-md-8 col-sm-12">',
										'<div class="input-group">',
											'<input class="form-control contributor-name" type="text">',
											'<span class="input-group-btn">',
												'<button type="button" class="btn btn-primary new-contributor">Add</button>',
											'</span>',
										'</div>',
									'</div>',
								'</div>',
							'</div>',
						'</div>',
						'<div class="modal-footer">',
							'<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>',
						'</div>',
					'</div>',
				'</div>',
			'</div>');
			
		$modal = $(modal.join(''));
		this.$contributors = $modal.find('tbody.contributors');
		
		$.each(this.options.contributors, function(){
			that.addContributor(this, 0);
		});
		
		$modal.modal('show');
		
		var addContributor = function(){
			var input = $modal.find('.contributor-name'),
				contributor = input.val();
			
			if (contributor){
				contributor = {username: contributor};
				that.addContributor(contributor, that.options.fadeInSpeed);
				that.options.contributors.push(contributor);
				input.val('');
				that.storeOptions();
			}
		};
		
		$modal.find('.new-contributor').off('click').on('click', addContributor);
	}
	
	RoiShopCalculator.prototype.displayModal = function(modal){
		var $modal = $('[element-id="' + modal + '"]');
		
		if( $.trim( $modal.html() ) == '' ){
			this.createModal($modal, $modal.data('element.options'));
		}
		
		$modal.modal('show');
	}
	
	RoiShopCalculator.prototype.changeCurrency = function() {
		var that = this,
			modal = [];

		modal.push(
			'<div class="modal inmodal fade in">',
				'<div class="modal-dialog">',
					'<div class="modal-content animated fadeIn">',
						'<div class="modal-header">',
							'<button type="button" class="close" data-dismiss="modal">',
								'<span aria-hidden="true">&times;</span>',
								'<span class="sr-only">Close</span>',
							'</button>',
							'<i class="fa fa-dollar-sign modal-icon"></i>',
							'<h4 class="modal-title">Current ROI Currency</h4>',
						'</div>',
						'<div class="modal-body">',
							'<div class="form-group">',
								'<label class="control-label col-lg-10 col-md-10 col-sm-12">Currency Symbol</label>',
								'<div class="col-lg-2 col-md-2 col-sm-12">',
									sprintf('<input class="form-control" currency value="%s"/>', this.options.language.currency.symbol),
								'</div>',
							'</div>',
							'<div class="form-group">',
								'<label class="control-label col-lg-10 col-md-10 col-sm-12">Thousandths Delimiter</label>',
								'<div class="col-lg-2 col-md-2 col-sm-12">',
									sprintf('<input class="form-control" del-thousands value="%s"/>', this.options.language.delimiters.thousands),
								'</div>',
							'</div>',
							'<div class="form-group">',
								'<label class="control-label col-lg-10 col-md-10 col-sm-12">Decimal Delimiter</label>',
								'<div class="col-lg-2 col-md-2 col-sm-12">',
									sprintf('<input class="form-control" del-decimal value="%s"/>', this.options.language.delimiters.decimal),
								'</div>',
							'</div>',
							'<div class="form-group">',
								'<label class="control-label col-lg-10 col-md-10 col-sm-12">Thousand Abbreviation</label>',
								'<div class="col-lg-2 col-md-2 col-sm-12">',
									sprintf('<input class="form-control" abb-thousand value="%s"/>', this.options.language.abbreviations.thousand),
								'</div>',
							'</div>',
							'<div class="form-group">',
								'<label class="control-label col-lg-10 col-md-10 col-sm-12">Million Abbreviation</label>',
								'<div class="col-lg-2 col-md-2 col-sm-12">',
									sprintf('<input class="form-control" abb-million value="%s"/>', this.options.language.abbreviations.million),
								'</div>',
							'</div>',
							'<div class="form-group">',
								'<label class="control-label col-lg-10 col-md-10 col-sm-12">Billion Abbreviation</label>',
								'<div class="col-lg-2 col-md-2 col-sm-12">',
									sprintf('<input class="form-control" abb-billion value="%s"/>', this.options.language.abbreviations.billion),
								'</div>',
							'</div>',
							'<div class="form-group">',
								'<label class="control-label col-lg-10 col-md-10 col-sm-12">Trillion Abbreviation</label>',
								'<div class="col-lg-2 col-md-2 col-sm-12">',
									sprintf('<input class="form-control" abb-trillion value="%s"/>', this.options.language.abbreviations.trillion),
								'</div>',
							'</div>',
						'</div>',
						'<div class="modal-footer">',
							'<button type="button" class="btn btn-primary update-currency">Update Currency</button>',
							'<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>',
						'</div>',
					'</div>',
				'</div>',
			'</div>');
			
		$modal = $(modal.join(''));

		var updateCurrency = function() {
			this.options.language.delimiters.thousands = this.$openModal.find('input[del-thousands]').val();
			this.options.language.delimiters.decimal = this.$openModal.find('input[del-decimal]').val();
			this.options.language.abbreviations.thousand = this.$openModal.find('input[abb-thousand]').val();
			this.options.language.abbreviations.million = this.$openModal.find('input[abb-million]').val();
			this.options.language.abbreviations.billion = this.$openModal.find('input[abb-brillion]').val();
			this.options.language.abbreviations.trillion = this.$openModal.find('input[abb-trillion]').val();
			this.options.language.currency.symbol = this.$openModal.find('input[currency]').val();
			
			this.setupLanguage();
			this.setupCalculator();
			
			this.$openModal = $modal.modal('hide');
		};
		
		$modal.find('.update-currency').off('click').on('click', $.proxy(updateCurrency, this));
		this.$openModal = $modal.modal('show');
	}
	
	
	RoiShopCalculator.prototype.hideSections = function() {
		var that = this,
			modal = [];

		modal.push(
			'<div class="modal inmodal fade in">',
				'<div class="modal-dialog">',
					'<div class="modal-content animated fadeIn">',
						'<div class="modal-header">',
							'<button type="button" class="close" data-dismiss="modal">',
								'<span aria-hidden="true">&times;</span>',
								'<span class="sr-only">Close</span>',
							'</button>',
							'<i class="fa fa-user modal-icon"></i>',
							'<h4 class="modal-title">Show/Hide Sections</h4>',
						'</div>',
						'<div class="modal-body">',
						'</div>',
						'<div class="modal-footer">',
							'<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>',
						'</div>',
					'</div>',
				'</div>',
			'</div>');
			
		$modal = $(modal.join(''));
		
		$.each(this.options.roiSections, function(){
			var section = [];
			
			section.push(
				'<div class="form-group">',
					'<label class="checkbox-inline i-checks">',
						sprintf('<input type="checkbox"%s>', this.included == 1 ? ' checked' : ''),
						sprintf('<span class="pull-right" style="margin-left: 45px;">%s</span>', this.Title),
					'</label>',				
				'</div>');
				
			$sections = $(section.join(''));
			$modal.find('.modal-body').append($sections);
			
			var checkSectionInclude = function(){
				var section = this,
					id = this.ID;
					
				section.included = section.included == 1 ? 0 : 1;
				
				$.each(that.options.roiSections, function(){
					if (this.ID == id) this.included = section.included;
				});
				
				if (!$.isEmptyObject(that.renderedElements.graph)){
					$.each(that.renderedElements.graph, function(){
						if(this.data('roishop.element')){
							highchart = this.data('roishop.element').el.highcharts();
							series = this.data('roishop.element').options.highchart.series;
							
							$.each(series, function(i, series){
								if (series.section == id) series.included = section.included;
							});								
						}
					});
				}
				
				$.each(that.options.navigation, function(){
					$.each(this.children, function(){
						if (this.id == id) this.el_visibility = section.included;
					});
				});
				
				$('[name="SECINC' + id + '"]').each(function(){
					$(this).val(section.included);
					$(this).closest(':data(roishop.element)').data('roishop.element').options.el_value = section.included;
				});
				
				$('.rs-include-' + id).each(function(){
					if (section.included){
						$(this).show();
					} else { 
						$(this).hide();
					}					
					
					var element = $(this).closest(':data(roishop.element)'),
						options = element.data('roishop.element');
					
					if (options){
						options.options.el_visibility = section.included;
						if (options.options.el_type == "text"){
							options.options.el_text = element.html();
						}
					}
				});
				
				that.setupCalculator();
				that.equalizeHeights();
				that.renderGraphs();
				that.updateGraphs();
				//that.setOptions();
				that.storeOptions();
			};
			
			$include = $sections.find('.i-checks').iCheck({
				checkboxClass: 'icheckbox_square-green'
			}).on('ifClicked', $.proxy(checkSectionInclude, this));
		});
		
		$modal.modal('show');
	}
	
	RoiShopCalculator.prototype.resetTemplate = function() {
		$.post("/assets/ajax/calculator.post.php",{
			action: 'resetTemplate',
			roi: getQueryVariable('roi')
		}, function(callback){
			location.reload();
		});		
	}
	
	RoiShopCalculator.prototype.addContributor = function(contributor, fadeIn) {
		var html = [];
		var that = this;
		
		html.push(
			'<tr>',
				sprintf('<td>%s</td>', contributor.username));
		
		if (this.options.canDeleteContributors){
			html.push(
				'<td><a class="btn btn-danger btn-sm pull-right remove-contributor"><i class="fa fa-times"></i> Remove</a></td>');
		}
		
		html.push(
			'</tr>');
		
		html = $(html.join(''));

		var removeContributor = function(){
			var $table =  $(this).closest('table');
			
			$(this).closest('tr').remove();
			
			var contributors = $table.find('tr');
			that.options.contributors = [];
			$.each(contributors, function(){
				username = $(this).find('td:eq(0)').html();
				contributor = {username: username}
				that.options.contributors.push(contributor);
			});
			
			that.storeOptions();
		};
		
		html.find('.remove-contributor').off('click').on('click', removeContributor);
		this.$contributors.append(html.hide().fadeIn(fadeIn));		
	}
	
	RoiShopCalculator.prototype.tooltip = function($element, options){
		var that = this,
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
				element_opts = $.extend(true, {}, that.values[dependent]);
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

				element_opts = $.extend(true, {}, that.values[dependent]);
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
					element_opts = $.extend(true, {}, that.values[dependent]);
					if (formula_txt) formula_txt = formula_txt.replace(dependent, element_opts.el_text);
				}
					
				html.push(sprintf('<div style="margin: 15px;"><strong>Equation: %s</strong></div>', formula_txt));
					
				html.push('</div>');
			}
			
			return html;
		}
		
		$calculator.off('click').on('click', calculationBreakdown);		
	}
	
	RoiShopCalculator.prototype.button = function($element, options){
		$element.addClass(options.el_class);
		$element.addClass(options.el_value == 1 ? options.on_class : options.off_class);
		$element.html(options.el_value == 1 ? options.on_text : options.off_text);
			
		var $input = $(sprintf('<input style="display: none" data-cell="BUT%s" value="%s">'), options.el_field_name, options.el_vaue);
		$element.after($input);
			
		var onButtonClick = function(){
			options.el_value = options.el_value == 1 ? 0 : 1;
			$element.removeClass(options.on_class).removeClass(options.off_class).addClass(options.el_value == 1 ? options.on_class : options.off_class);
			$element.html(options.el_value == 1 ? options.on_text : options.off_text);
		}
			
		$element.off('click').on('click', $.proxy(onButtonClick, this));
	}
	
	RoiShopCalculator.prototype.checkbox = function($container, options){
		var that = this,
			$element = $(sprintf('<div%s></div>', options.el_id ? sprintf(' element-id="%s"', options.el_id) : ''));
			
		$container.append($element);
		
		this.logElement($element, options);

		$element.addClass('form-horizontal');
		var $container = $([
			'<div class="form-group">',
			sprintf('<label class="%s">%s</label>', options.el_label_class, options.el_text),
			sprintf('<div class="%s checkbox-options">', options.el_class),
			'</div>',
			'</div>'
		].join(''));
		$element.append($container);

		var $checkbox = $container.find('.checkbox-options');
		var $input = $(sprintf('<input style="display: none;" data-cell="%s" data-format="%s" >', options.el_field_name, options.el_format));
		
		if(options.el_value) $input.val(numeral(options.el_value).format(options.el_format || "0,0[.]00"));
		$container.append($input);
			
		var checkOption = function(){
			var checkbox_val = 0,
				total_checked = 0,
				show_elements = [],
				hide_elements = [];
				
			$.each($checkbox.find('input[type="checkbox"]'), function(i, checkbox){
				options.choices[i].ch_checked = this.checked;

				if (this.checked){
					checkbox_val += parseInt(options.choices[i].ch_value);
					total_checked++;

					if (options.choices[i].ch_show) show_elements = options.choices[i].ch_show.split(',');
					if (options.choices[i].ch_hide) hide_elements = options.choices[i].ch_hide.split(',');
				} else {
					if (options.choices[i].ch_show_off) show_elements = options.choices[i].ch_show_off.split(',');
					if (options.choices[i].ch_hide_off) hide_elements = options.choices[i].ch_hide_off.split(',');
				}

				if (show_elements){
					$.each(show_elements, function(){
						if (this.length) that.toggleVisibility($('[element-id="' + this + '"'), 1);
					});
				}
						
				if (hide_elements){
					$.each(hide_elements, function(){
						if (this.length) that.toggleVisibility($('[element-id="' + this + '"'), 0);
					});						
				}
			});
				
			switch(options.el_formula){
				case 'average':
					checkbox_val = total_checked > 0 ? checkbox_val / total_checked : 0;
					break;
			}
				
			that.setValue($element, options, checkbox_val);				
			$(that.options.calcHolder).calx('getCell', options.el_field_name).setValue(checkbox_val).calculateAllDependents();
		};
			
		var $choices = [];
		$.each(options.choices, function(count, choice){
			$choices.push(sprintf('<div class="checkbox i-checks"><label><input type="checkbox" value="%s"%s><i></i>  %s</label></div>', choice.ch_value, choice.ch_checked ? ' checked' : '', choice.ch_text));
		});
		$choices.join('');
	
		$checkbox.append($choices);		
		
		$include = $checkbox.find('.i-checks').iCheck({
			checkboxClass: 'icheckbox_square-green'
		}).on('ifToggled', $.proxy(checkOption, this));
	}
	
	RoiShopCalculator.prototype.dropdown = function($container, options){
		var that = this,
			$element = $(sprintf('<div%s></div>', options.el_id ? sprintf(' element-id="%s"', options.el_id) : ''));
			
		$container.append($element);
		
		this.logElement($element, options);			
			
		var $container = $([
			'<div class="form-group">',
			( options.el_text ? sprintf('<label class="%s">%s</label>', options.el_label_class, options.el_text) : '' ),
			sprintf('<div class="%s">', options.el_class),
				'<select data-cell="' + options.el_field_name + '" class="form-control">',
			'</div>',
			'</div>'
		].join(''));
			
		$element.addClass('form-horizontal').append($container);
		var $form = $container.find('.form-horizontal');
		var $group = $container.find('.form-group');
		var $label = $container.find('label');
		var $dropdown = $container.find('select');		

		if(options.choices){
			var choices = options.choices,
				selections = [];

			if (!options.el_value) {
				options.el_value = options.choices[0].ch_text;
			}
			if (!options.el_formatted_value) options.el_formatted_value = options.choices[0].ch_text;
			
			$.each(choices, function(count, choice){
				selections.push(sprintf('<option value="%s"%s>%s</option>', choice.ch_value ? choice.ch_value : choice.ch_text, ( options.selectedIndex == count ? ' selected="selected"' : '' ), choice.ch_text));
			});
			selections = selections.join('');
				
			$dropdown.append($(selections));
		};
			
		var onDropdownChange = function(){
			var selected = $dropdown.find('option:selected')[0],
				index = selected.index,
				val = selected.value,
				text = selected.text;
								
			that.setValue($(this), options, val);
			
			var selectedOption = options.choices[index];
			options.selectedIndex = index;

			var show_elements = selectedOption.ch_show.split(',');
			var hide_elements = selectedOption.ch_hide.split(',');

			if (show_elements){
				$.each(show_elements, function(){
					if (this.length) that.toggleVisibility($('[element-id="' + this + '"'), 1);
				});
			}
						
			if (hide_elements){
				$.each(hide_elements, function(){
					if (this.length) that.toggleVisibility($('[element-id="' + this + '"'), 0);
				});						
			}

			$(that.options.calcHolder).calx('getCell', options.el_field_name).setValue($(this).val()).calculateAllDependents();

			that.storeOptions();
			that.updateGraphs();
		}
		
		$dropdown.chosen({width: '100%', disable_search_threshold: 10});
		$dropdown.off('change').on('change', onDropdownChange);			
	}
	
	RoiShopCalculator.prototype.graph = function($container, options){
		var that = this,
			$element = $(sprintf('<div%s></div>', options.el_class ? sprintf(' class="%s"', options.el_class) : ''));
		
		$container.append($element);
		
		this.logElement($element, options);	
	}
	
	RoiShopCalculator.prototype.holder = function($container, options){
		var that = this,
			$element = $(sprintf('<div%s></div>', options.el_class ? sprintf(' class="%s"', options.el_class) : ''));
		
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
	
	RoiShopCalculator.prototype.input = function($container, options){
		var that = this,
			$element = $(sprintf('<div%s></div>', options.el_id ? sprintf(' element-id="%s"', options.el_id) : ''));
		
		$container.append($element);
		
		this.logElement($element, options);
		
		var $container = $([
			'<div class="form-group">',
			sprintf('<label class="%s">%s</label>', options.el_label_class, options.el_text),
			sprintf('<div class="%s input-holder">', options.el_class),
			options.el_tooltip || options.el_append || options.el_formula ?
				'<div class="input-group">' : '',
				sprintf('<input class="form-control' + ( options.el_tooltip || options.el_formula ? ' input-addon' : '' ) + '" name="%s"%s%s>', options.el_field_name, options.el_field_name ? ' data-cell="' + options.el_field_name + '"' : '', options.el_format ? ' data-format="' + options.el_format + '"' : ''),
			options.el_append ?
				sprintf('<span class="input-group-addon right append">%s</span>', options.el_append) : '',
			options.el_tooltip || options.el_append || options.el_formula ?
				'</div>' : '',
			'</div>',
			'</div>'
		].join(''));

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
			that.setValue($element, options, $(this).val());
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
	
	RoiShopCalculator.prototype.rating = function($container, options){
		var that = this,
			$element = $(sprintf('<div%s></div>', options.el_id ? sprintf(' element-id="%s"', options.el_id) : ''));
			
		$container.append($element);
		
		this.logElement($element, options);
		
		var	html = [];

		html.push('<div class="form-group"><div class="rating">');
			
		for (var i = options.el_max; i >= options.el_min; i--){
			html.push(sprintf('<input type="radio" name="rating-%s" id="stars-%s-%s" value="%s"%s><label for="stars-%s-%s"><i class="fa fa-star"></i></label>', options.el_field_name, options.el_field_name, i, i, i == options.el_value ? ' checked' : '', options.el_field_name, i ));
		}
		
		html.push(sprintf('<input style="display: hidden" data-cell="%s" value="%s">', options.el_field_name, options.el_value ? options.el_value : 0));
			
		html.push(sprintf('<div class="%s">%s</div>', options.el_label_class, options.el_text));
		html.push('</div></div>');
			
		$element.addClass('form-horizontal').append($(html.join('')));
			
		var onRatingChange = function(){
			$(that.options.calcHolder).calx('getCell', options.el_field_name).setValue($(this).val()).calculateAllDependents();
			that.setValue($element, options, $(this).val());
		}
			
		$element.find('input').off('click').on('click', onRatingChange);		
	}
	
	RoiShopCalculator.prototype.slider = function($container, options){
		var that = this,
			$element = $(sprintf('<div%s></div>', options.el_id ? sprintf(' element-id="%s"', options.el_id) : ''));
			
		$container.append($element);
		
		this.logElement($element, options);
			
		var $sliderWrapper = $([
			'<div class="form-group">',
			sprintf('<label class="%s%s">%s</label>', options.el_label_class || '', options.el_stacked == 1 ? ' control-label col-lg-12' : '', options.el_text),
			sprintf('%s', options.el_stacked == 1 ? '' : sprintf('<div class="%s"><div class="row">', options.el_class)),
			sprintf('<div class="input-slider%s"></div>', options.el_stacked == 1 ? ' col-lg-12' : ' col-lg-6'),
			sprintf('%s', options.el_stacked == 1 ? '' : '</div></div></div>')
		].join(''));

		$element.addClass('form-horizontal').append($sliderWrapper);
		
		var $label = $element.find('label');
		var $slider = $('<div class="slider"></div>');
		$element.find('.input-slider').append($slider);

		if(options.el_stacked == 1){
			$input = $([
				'<span class="pull-right">',
					sprintf('<span><span class="slider-input" data-format="%s" data-cell="%s">%s</span>%s</span>', options.el_format, options.el_field_name, numeral(options.el_format.includes('%') ? parseInt(options.el_value) / 100 : options.el_value).format(options.el_format), options.el_append || ''),
				'</span>'
			].join(''));

			$label.append($input);
		} else {
			$input = $([
				'<div class="col-lg-6">',
					'<div class="input-group">',
						sprintf('<input class="form-control slider-input" data-format="%s" value="%s" data-cell="%s"></span>', options.el_format, numeral(options.el_format.includes('%') ? parseInt(options.el_value) / 100 : options.el_value).format(options.el_format), options.el_field_name),
					'</div>',
				'</div>'
			].join(''));
				
			$sliderWrapper.find('.input-slider').parent().append($input);
				
			var onInputFocus = function(){
				this.select();
			}
			$input.find('input').off('focus').on('focus', onInputFocus);
		}
		
		var initializeSlider = function(){
			$slider.slider({
				value: parseInt(options.el_value) || 0,
				min: options.el_min || 0,
				max: options.el_max || 100,
				orientation: "horizontal",
				range: "min",
				animate: true,
				slide: function(event, ui){
					that.setValue($element, options, ui.value);
					var cell = $element.find('[data-cell]').attr('data-cell');
					$(that.options.calcHolder).calx('getCell', cell).setValue(ui.value).calculateAllDependents();
				}
			});
		}	
			
		if($slider) initializeSlider();		
	}
	
	RoiShopCalculator.prototype.htmltext = function($container, options){
		$container.append(options.el_text);
		
		if(options.el_visibility == 0){
			$element.hide();
		};
	}
	
	RoiShopCalculator.prototype.table = function($container, t_options){
		var that = this,
			$element = $(sprintf('<div%s></div>', t_options.el_id ? sprintf(' element-id="%s"', t_options.el_id) : '')),
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

		options = $.extend({}, options, JSON.parse(options.el_options));

		var $container = $([
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
		
		$table = $('<table/>').appendTo($tableBody).addClass(options.classes);
		$header = $('<thead></thead>').appendTo($table);
		$body = $('<tbody></tbody>').appendTo($table);

		if (options.colgroups){
			
			$.each(options.colgroups, function(i, colgroup){
				var $html = $('<colgroup/>');
				
				$.each(colgroup.children, function(i, col){
					var html = [];
					html.push('<col');
					
					var col_opts = JSON.parse(col.el_options);
					$.each(col_opts, function(key, value){
						switch (key){
							case 'style':
								html.push(' style="' + value + '"');
								break;

							case 'style2':
								html.push(' style="' + value + '"');
								$html.attr('align', 'center');
								break;
						}
					});
					
					html.push('>');
					
					$col = $(html.join(''));
					
					$html.append($col);
				});
				
				$table.append($html);
			});
		}
		
		if (options.headers){
			$.each(options.headers, function(i, header){
				var $html = $(sprintf('<tr%s></tr>', header.el_id ? sprintf(' element-id="%s"', header.el_id) : ''));

				$.each(header.children, function(i, column){
					var $column_header = $('<th/>').data('rstable.column', column);

					$html.append($column_header);
					var $inner = $(sprintf('<div class="th-inner %s">', options.sortable ? 'sortable both' : ''));
					var text = column.el_text;
						
					$inner.append(text);
					$column_header.append($inner);
				});
					
				$header.append($html);
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

			$.each(options.data, function(i, row){
				if (row.el_visibility == "1") table_data.push(row);
			});
		}
		
		var initSort = function(){
			if (!options.sortable || sort_column < 0){
				return false;
			}
			
			var order = sort_order === 'desc' ? -1 : 1;

			options.data.sort(function(a,b){
				var aa = a.children[sort_column].el_text,
					bb = b.children[sort_column].el_text;
					
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
						that.options.formatPaginationSwitch()),
					sprintf('<i class="%s %s"></i>', options.iconsPrefix, options.icons.paginationSwitchDown),
					'</button>');
			}
			
			if (options.showRefresh) {
				html.push(sprintf('<button class="btn' +
						sprintf(' btn-%s', options.buttonsClass) +
						sprintf(' btn-%s', options.iconSize) +
						'" type="button" name="refresh" aria-label="refresh" title="%s">',
						that.options.formatRefresh()),
					sprintf('<i class="%s %s"></i>', options.iconsPrefix, options.icons.refresh),
					'</button>');
			}
			
		   if (options.showToggle) {
				html.push(sprintf('<button class="btn' +
						sprintf(' btn-%s', options.buttonsClass) +
						sprintf(' btn-%s', options.iconSize) +
						'" type="button" name="toggle" aria-label="toggle" title="%s">',
						that.options.formatToggle()),
					sprintf('<i class="%s %s"></i>', options.iconsPrefix, options.icons.toggle),
					'</button>');
			}

			if (options.addRecord) {
				html.push(sprintf('<button class="btn' +
						sprintf(' btn-%s', options.buttonsClass) +
						sprintf(' btn-%s', options.iconSize) +
						'" type="button" name="addRecord" aria-label="file" title="%s">',
						that.options.formatAddRecord()),
					sprintf('<i class="%s %s"></i>', options.iconsPrefix, options.icons.addRecord),
					'</button>');
			}
			
			if (options.deleteRecord) {
				html.push(sprintf('<button class="btn' +
						sprintf(' btn-%s', options.buttonsClass) +
						sprintf(' btn-%s', options.iconSize) +
						'" type="button" name="deleteRecord" aria-label="file" title="%s">',
						that.options.formatDeleteRecord()),
					sprintf('<i class="%s %s"></i>', options.iconsPrefix, options.icons.deleteRecord),
					'</button>');
			}

			if (options.editRecord) {
				html.push(sprintf('<button class="btn' +
						sprintf(' btn-%s', options.buttonsClass) +
						sprintf(' btn-%s', options.iconSize) +
						'" type="button" name="editRecord" aria-label="file" title="%s">',
						that.options.formatEditRecord()),
					sprintf('<i class="%s %s"></i>', options.iconsPrefix, options.icons.editRecord),
					'</button>');
			}

			html.push('</div>');
			
			if (html.length > 2) {
				$toolbar.append(html.join(''));
			}
			
			var addTableRow = function(){
				var table_row = {};
				table_row.el_type = "tblrow";
				table_row.el_visibility = 1;
				table_row.children = [];
				
				$modal.find('[element-id]').each(function(){
					var options = $(this).data('element.options');
					options.el_text = options.el_value;
					table_row.children.push(options);
				});
				
				$element.data('element.options').data.push(table_row);

				that.redraw($element);
				that.storeOptions();
				
				$modal.modal('hide');
			}

			var addRecord = function(){
				var modal = [],
					columns = $element.find('.fixed-table-body > table > thead').find('th');

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
				
				$modal.find('[addTableRow]').off('click').on('click', addTableRow);
				
				$modal_body = $modal.find('.modal-body');
				$.each(columns, function(){
					var column = $.extend(true, {}, $(this).data('rstable.column'));
					that.renderElement(column, $modal_body);
				});
				
				$modal.modal('show');
			}
			
			if (options.addRecord) {
				$toolbar.find('button[name="addRecord"]').off('click').on('click', addRecord);
			}
		}
		
		var initPagination = function(){
			if (!options.pagination) {
				$pagination.hide();
				return;
			} else {
				$pagination.show();
			}	
			
			var html = [];
			totalRows = table_data.length;

			totalPages = 0;
			if (totalRows) {
				if (pageSize === that.options.formatAllRows()) {
					pageSize = totalRows;
					$allSelected = true;
				} else if (pageSize === totalRows) {
					var pageLst = typeof pageList === 'string' ?
						that.options.pageList.replace('[', '').replace(']', '')
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
				options.onlyInfoPagination ? that.options.formatDetailPagination(totalRows) :
				that.options.formatShowingRows(pageFrom, pageTo, totalRows),
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
						$allSelected ? that.options.formatAllRows() : pageSize,
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
						pageList.push(value.toUpperCase() === that.options.formatAllRows().toUpperCase() ?
							that.options.formatAllRows() : +value);
					});
				}

				$.each(pageList, function (i, page) {
					if (!that.options.smartDisplay || i === 0 || pageList[i - 1] < that.options.totalRows) {
						var active;
						if ($allSelected) {
							active = page === that.options.formatAllRows() ? ' class="active"' : '';
						} else {
							active = page === that.options.pageSize ? ' class="active"' : '';
						}
						pageNumber.push(sprintf('<li role="menuitem"%s><a href="#">%s</a></li>', active, page));
					}
				});
				pageNumber.push('</ul></span>');

				html.push(that.options.formatRecordsPerPage(pageNumber.join('')));
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
				pageSize = that.options.formatAllRows();
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
			pageSize = $this.text().toUpperCase() === that.options.formatAllRows().toUpperCase() ?
				that.options.formatAllRows() : +$this.text();
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
			var data = options.data;				
			$body = $element.find('.fixed-table-body > table > tbody').html('');
			
			var hasTr, $tr, $td, renderedElements = [];

			for (var i = 0; i < data.length; i++) {
				that.renderElement(data[i], $body, false);
			}

			$.each(renderedElements, function(i, tr){
				if (i + 1 < pageFrom || i >= pageTo ) $(this).hide();
			});

			if (!table_data.length) {
				$body.append('<tr class="no-records-found">' +
					sprintf('<td colspan="%s">%s</td>',
					$header.find('th').length,
					that.options.formatNoMatches()) +
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
					
					switch(options.el_subtype){
						
						case 'input':
							$input = $('<input/>')
										.addClass('form-control input-sm')
										.val(value);
								
								$(td).html($input);
							break;
						
						case 'textarea':
							$input = $('<textarea style="resize: vertical;"></textarea>')
										.addClass('form-control')
										.html(value);
		
							$(td).html($input);
							break;
							
						case 'dropdown':
							var $select = $('<select/>');
							
							if (options.choices){
								var choices = options.choices,
									selections = [];
								
								$.each(choices, function(i, choice){
									selections.push(sprintf('<option value="%s"%s>%s</option>', choice.ch_text, (choice.ch_text === options.el_text ? ' selected="selected"' : ''), choice.ch_text));
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
					$(td).each(function() {
						
						var $cell = $(this),
							options = $cell.data('element.options'),
							field_name = options.el_field_name,
							format = options.el_format,
							val;

						$cell.addClass('editable').removeClass('editing');
						
						switch(options.el_subtype){
							
							case 'input':
								val = $(this).find('input').val();
								if (format){
									if (format.indexOf('%') != -1) val = val / 100;
									val = numeral(val).format(format);
								}

								that.setValue($(this), options, val);
								
								$(this).html(val);
								break;
								
							case 'textarea':
								var val = $(this).find('textarea').val();

								that.setValue($(this), options, val);
								
								$(this).html(val);
								break;

							case 'dropdown':
								var selected = $(this).find('select > option:selected')[0],
									index = selected.index,
									val = selected.value,
									text = selected.text;
									
								that.setValue($(this), options, val);

								selectedOption = options.choices[index];
								
								if (selectedOption.ch_action){
									var actions = JSON.parse(selectedOption.ch_action);
									for (var i in actions){
										switch(i){
											case 'moveTableRow':
												var element_to_move = actions[i][0];
												var move_to_table = actions[i][1];

												var row_data = $(sprintf('[element-id="%s"]', element_to_move)).data('element.options');
												var table = $(sprintf('[element-id="%s"]', element_to_move)).closest('.bootstrap-table').parent().data('element.options');

												table.data = $.map(table.data, function(v, i){
													return v.el_id === row_data.el_id ? null : v;
												});
												that.redraw($(sprintf('[element-id="%s"]', element_to_move)).closest('.bootstrap-table').parent());
												
												$(sprintf('[element-id="%s"]', move_to_table)).data('element.options').data.push(row_data);
												that.redraw($(sprintf('[element-id="%s"]', move_to_table)));												
												break;
										}
									}
									
								}
								
								$(this).html(val);
								break;
						}
					});
					
					that.updateGraphs();
					that.storeOptions();
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
		
		setTableData();
		initSort();
		initToolbar();
		initPagination();
		initBody();
		initEditing();
	}
	
	RoiShopCalculator.prototype.tablecell = function($container, options){
		var that = this,
			$element = $(sprintf('<td%s></td>', data[i].el_id ? sprintf(' element-id="%s"', data[i].el_id) : ''));
	}
	
	RoiShopCalculator.prototype.tablerow = function($container, options){
		var that = this,
			$element = $(sprintf('<tr%s></tr>', options.el_id ? sprintf(' element-id="%s"', options.el_id) : ''));
		
		this.logElement($element, options);
		
		$container.append($element);	

		if (options.el_visibility == 0) $element.hide();
		
		$.each(options.children, function(c, t_cell){
			var cell_type;
			switch(t_cell.el_subtype){
				case 'input':
					cell_type = 'editable';
					break;
					
				case 'textarea':
					cell_type = "editable";
					break;
					
				case 'dropdown':
					cell_type = "editable";
					break;
			}

			$td = $(sprintf('<td%s%s></td>', cell_type == "editable" ? ' class="editable"' : '', t_cell.el_id ? sprintf(' element-id="%s"', t_cell.el_id) : '')).data('element.options', t_cell);
		
			$element.append($td);

			that.logElement($td, t_cell);
			
			$td.html(t_cell.el_value ? ( t_cell.el_format ? numeral(t_cell.el_value).format(t_cell.el_format || "0,0[.]00") : t_cell.el_value ) : t_cell.el_text);			

			if (t_cell.el_field_name){
				$td.attr('data-cell', t_cell.el_field_name);
			}
					
			if (t_cell.el_formula){
				$td.attr('data-formula', t_cell.el_formula);
			}
					
			if (t_cell.el_format){
				$td.attr('data-format', t_cell.el_format || "0,0[.]00");
			}
					
			if (t_cell.children){
				$.each(t_cell.children, function(){
					that.renderElement(this, $td);
				});
			}
					
			var showModal = function(){
				that.displayModal(t_cell.el_src);
			}

			if (t_cell.el_subtype == "modal") $td.off('click').on('click', showModal);
		});
	}
	
	RoiShopCalculator.prototype.replaceTags = function(tag, value){
		$(sprintf('[tag="%s"]', tag)).each(function(){
			$(this).html(value);
			var parent = $(this).closest(':data(element.options)');

			parent_text = parent.data('element.options').el_text = parent.html();
		});
		this.storeOptions();
	}
	
	RoiShopCalculator.prototype.textarea = function($container, options){
		var that = this,
			$element = $(sprintf('<div%s></div>', options.el_id ? sprintf(' element-id="%s"', options.el_id) : ''));
			
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
			that.setValue($element, options, $(this).val());
		}
		
		$textarea.off('change').on('change', textareaChange);		
	}
	
	RoiShopCalculator.prototype.modal = function($container, options){
		var that = this,
			$element = $(sprintf('<div%s></div>', options.el_class ? sprintf(' class="%s"', options.el_class) : ''));
		
		$container.append($element);
		
		if (options.el_visibility == 0){
			$element.hide();
		}
		
		this.logElement($element, options);
	}
	
	RoiShopCalculator.prototype.createModal = function($container, options){
		var that = this;

		if (options.children && options.children.length > 0) {
			$.each(options.children, function(i, options){
				that.renderElement(options, $container);
			})
		}		
	}
	
	RoiShopCalculator.prototype.toggle = function($container, options){
		var that = this,
			$element = $(sprintf('<div%s></div>', options.el_id ? sprintf(' element-id="%s"', options.el_id) : ''));
			
		$container.append($element);
		
		this.logElement($element, options);	

		var $toggle = $([
			'<div class="pretty p-icon p-toggle">',
			'<input type="checkbox"',
			options.el_value == 1 ? ' checked="checked">' : '>',
			'</div>'
		].join(''));
			
		$element.append($toggle);
		var $checkbox = $toggle.find('input');

		var $choices = [];
		$.each(options.choices, function(count, choice){
			$choices.push(sprintf('<div class="%s">%s</div>', choice.ch_class, choice.ch_text));
		});
		$choices.join('');
		
		var toggleToggle = function(){
			var isChecked = options.el_value == options.choices[0].ch_value ? 1 : 0,
				choices = options.choices;

			var show_elements = choices[isChecked].ch_show.split(',');
			var hide_elements = choices[isChecked].ch_hide.split(',');

/* 			if (choices[isChecked].update_options){
				var update_options = JSON.parse(choices[isChecked].update_options);
				$.each(update_options, function(){
					var el_options = $('[element-id="' + this.el_id + '"').data('element.options');
					$.extend(el_options, this);
				});				
			} */

			if (show_elements){
				$.each(show_elements, function(){
					if ($('[element-id="' + this + '"').length) $('[element-id="' + this + '"').data('element.options').el_visibility = 1;
					$('[element-id="' + this + '"').show();
				});
			}
						
			if (hide_elements){
				$.each(hide_elements, function(){
					if ($('[element-id="' + this + '"').length) $('[element-id="' + this + '"').data('element.options').el_visibility = 0;
					$('[element-id="' + this + '"').hide();
				});						
			}

			options.el_value = options.choices[isChecked].ch_value;
			that.storeOptions();
		}
			
		$toggle.append($choices);
		$checkbox.off('change').on('change', $.proxy(toggleToggle, this));			
	}
	
	RoiShopCalculator.prototype.video = function($container, options){
		var that = this,
			$element = $(sprintf('<div class="player"%s></div>', options.el_id ? sprintf(' element-id="%s"', options.el_id) : ''));
			
		$container.append($element);
		
		this.logElement($element, options);			
		
		$container = $([
			sprintf('<a class="popup-iframe" href="%s"></a>', options.el_src),
			sprintf('<iframe class="fit-vid" style="margin-left: 5px;" width="425" height="239" src="%s" frameborder="0"/>', options.el_src),
		].join(''));
			
		$element.append($container);
		$container.fitVids();	
	}
	
	RoiShopCalculator.prototype.logElement = function($element, options){
		
		if ($element) $element.attr('element-id', options.el_id);
		
		if ($element && options) $element.data('element.options', options);
		
		if (options.el_type){
			if (!this.renderedElements[options.el_type]) this.renderedElements[options.el_type] = [];
			this.renderedElements[options.el_type].push($element);			
		}
		
		var id = options.el_field_name ? options.el_field_name : 'element.' + options.el_id;

		if (id){
			if (!this.elementOptions[id]) this.elementOptions[id] = {};
			if (options.el_id){
				this.elementOptions[id][options.el_id] = $element;
			}
		}
	}
	
	RoiShopCalculator.prototype.renderElement = function(options, $container) {
		
		switch (options.el_type){
			case 'button':
				this.button($container, options);
				break;
					
			case 'checkbox':
				this.checkbox($container, options);
				break;
					
			case 'dropdown':
				this.dropdown($container, options);
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
	
	RoiShopCalculator.prototype.roiElements = function() {
		var that = this;
		
		$.each(this.options.elements, function(i, options){
			that.renderElement(options, that.$roiContent);
		});
	}
	
	RoiShopCalculator.prototype.cleanUpCells = function() {
		var that = this,
			duplicated_cells = 1,
			new_cell_name,
			cells_created = [];
			
		var $cells = $('[data-cell]');
			
		$.each($cells, function(){
			if ($.inArray($(this).data('cell'), cells_created) != -1){
				$(this).attr('data-cell', 'RSCALX' + duplicated_cells);
				duplicated_cells++;
			} else { cells_created.push($(this).data('cell')) }
		});	
	}
	
	RoiShopCalculator.prototype.equalizeHeights = function() {
		var that = this,
		visibility,
			equalizers = [];

		var resize = function() {
			$('[class*="' + that.options.equalizer + '"]').each(function(){
				var cls = $(this).attr('class').split(' ');
				$.each(cls, function(){
					if (this.indexOf(that.options.equalizer) > -1){
						equalizers.push(this.replace(that.options.equalizer, ''));
					}
				});
			});
			
			equalizers = $.unique(equalizers);
			
			$.each(equalizers, function(){
				var maxHeight = 0;
				$('.' + that.options.equalizer + this).each(function(){
					maxHeight = $(this).height() > maxHeight ? $(this).height() : maxHeight;
				}).each(function(){
					$(this).height(maxHeight);
				});
			});
		}
		
		$(window).off('resize').on('resize', resize).resize();

		var scroll = function() {
			var item,
				target,
				height = $(window).height(),
				top = $(window).scrollTop();
			
			$('li.smooth-scroll a').each(function(){
				target = $(this.hash);
				if(target.length && target.is(':visible')){
					if (target.offset().top - height / 2 <= top) item = this;
				};
			});
			
			if (item){
				$('li.smooth-scroll a').not(item).each(function(){
					$(this).parent().removeClass('active');
				});
				
				$(item).parent().addClass('active');				
			}
		}
		
		$(window).off('scroll').on('scroll', scroll).scroll();
	}

	RoiShopCalculator.prototype.setupCalculator = function() {	
		var that = this;

		$(this.options.calcHolder).calx({
			autoCalculate: false
		});

		$(this.options.calcHolder).calx('registerFunction', 'ANNUALCOST', function(yr){
			var total_cost = 0;
			
			$(':data(roishop.element)').each(function(){
				var options = $(this).data('roishop.element').options;
					
				total_cost -= options.el_cost == 1 ? options.el_year == yr || yr == 'total' ? parseInt(options.el_value) : 0 : 0;
			});
			
			return total_cost || 0;
		});
		
		$(this.options.calcHolder).calx('registerFunction', 'SECTIONGRAND', function(id){
			return $(that.options.calcHolder).calx('getCell', 'SECTIONTOT' + id).getValue();			
		});
		
		$(this.options.calcHolder).calx('getSheet').calculate();

		for(var a in $(this.options.calcHolder).calx('getSheet').cells){
			var cell = $(that.options.calcHolder).calx('getCell', a);
			
			var parents = $('[data-cell="' + a + '"]').parents()[0].tagName;
			if (parents != "LABEL"){
				that.values[a] = $('[data-cell="' + a + '"]').closest(':data(element.options)').data('element.options');
			}
			
			if (that.values[a]){
				if (that.values[a].el_type != "dropdown"){
					that.values[a].el_cell = a;
					that.values[a].el_value = cell.getValue();
					that.values[a].el_formatted_value = cell.getFormattedValue();
					
					if (!cell.getFormattedValue()){
						that.values[a].el_formatted_value = numeral(0).format(that.values[a].el_format || "0,0");
					}					
				}
			}
		}
		
		if (this.renderedElements.checkbox){
			$.each(this.renderedElements.checkbox, function(){
				var checkbox_val = 0,
					total_checked = 0,
					show_elements = [],
					hide_elements = [],
					options = $(this).data('element.options');
					
				$.each($(this).find('input[type="checkbox"]'), function(i, checkbox){
					options.choices[i].ch_checked = this.checked;

					if (this.checked){
						checkbox_val += parseInt(options.choices[i].ch_value);
						total_checked++;

						if (options.choices[i].ch_show) show_elements = options.choices[i].ch_show.split(',');
						if (options.choices[i].ch_hide) hide_elements = options.choices[i].ch_hide.split(',');
					} else {
						if (options.choices[i].ch_show_off) show_elements = options.choices[i].ch_show_off.split(',');
						if (options.choices[i].ch_hide_off) hide_elements = options.choices[i].ch_hide_off.split(',');
					}

					if (show_elements){
						$.each(show_elements, function(){
							if (this.length) that.toggleVisibility($('[element-id="' + this + '"'), 1);
						});
					}
							
					if (hide_elements){
						$.each(hide_elements, function(){
							if (this.length) that.toggleVisibility($('[element-id="' + this + '"'), 0);
						});						
					}
				});
					
				switch(options.el_formula){
					case 'average':
						checkbox_val = total_checked > 0 ? checkbox_val / total_checked : 0;
						break;
				}
				
				that.setValue($(this), options, checkbox_val);
				$(that.options.calcHolder).calx('getCell', options.el_field_name).setValue(checkbox_val).calculateAllDependents();
			});			
		}
	}

	RoiShopCalculator.prototype.renderGraphs = function() {
		if ($.isEmptyObject(this.renderedElements.graph)){
			return false;
		}
	
		var options;
		
		Highcharts.wrap(Highcharts, 'numberFormat', function (proceed) {
			var ret = proceed.apply(0, [].slice.call(arguments, 1));
			return numeral(ret).format('0,0');
		});
	
		$.each(this.renderedElements.graph, function(){
			var c_options = this.data('element.options').highchart;

			options = $.extend(true, {}, c_options);
			
			if(options.series){
				options.series = $.map(options.series, function(a){
					/* if (a.included == 1) */ return a;
				});
			}

			if (options){
				if (!options.chart) options.chart = {};
				options.chart.renderTo = this[0];
				
				if(!options.plotOptions){
					options.plotOptions = {};
				}
				
				if(!options.plotOptions.pie){
					options.plotOptions.pie = {};
				}
				
				options.plotOptions.pie = {
					showInLegend: true,
					dataLabels: {
						enabled: false
					}
				};

				new Highcharts.Chart(options);
			}
		});
	}

	RoiShopCalculator.prototype.updateGraphs = function() {
		if ($.isEmptyObject(this.renderedElements.graph)){
			return false;
		}

		var that = this,
			highchart,
			series,
			seriesData,
			evaluate;
		
		if(this.renderedElements.graph){
			$.each(this.renderedElements.graph, function(){
				if(this.data('element.options').highchart) {
					highchart = this.highcharts();

					c_series = this.data('element.options').highchart.series;
   
				   series = $.extend(true, {}, c_series);
				   
				   series = $.map(series, function(a){
					   /* if (a.included == 1) */ return a;
				   });			
   
				   $.each(series, function(i, series){
					   seriesData = [];
					   if(series.formula){
						   	$.each(series.formula, function(){
								
								if(this){
									evaluate = $(that.options.calcHolder).calx('evaluate', (this.formula || this));
									var innerObj = {};
									innerObj['y'] = evaluate ? evaluate : 0;
									innerObj['name'] = this.name;
									seriesData.push(innerObj);
								}
							});
							
							if (highchart){
								highchart.series[i].setData(seriesData, true);
							}
					   }
				   });
				}
			});
		}
	}
	
	RoiShopCalculator.prototype.redraw = function($element){
		var options = $element.data('element.options');
		$element.empty();

		this.renderElement(options, $element, false);
	}
	
	RoiShopCalculator.prototype.toggleVisibility = function($element, state){
		if ($element.length) $element.data('element.options').el_visibility = state;
		if (state == 0) $element.hide();
		if (state == 1) $element.show();

		this.storeOptions();
	}
	
	RoiShopCalculator.prototype.setValue = function($element, options, value) {
		var that = this;
		var id = options.el_field_name ? options.el_field_name : 'element.' + options.el_id;

		if (this.elementOptions[id]){
			$.each(this.elementOptions[id], function(key, element){
				that.displayValue(element, value);
			});			
		}
		
		this.updateGraphs();
		this.storeOptions();
	}
	
	RoiShopCalculator.prototype.displayValue = function($element, value) {
		var that = this,
			options = $element.data('element.options'),
			id = options.el_field_name ? options.el_field_name : 'element.' + options.el_id;

		$.each(this.elementOptions[id], function(){
			var options = $(this).data('element.options'),
				id = options.el_field_name ? options.el_field_name : 'element.' + options.el_id;			
			
			switch(options.el_type) {
				case 'tblcell':
					$(this).html(value);
					break;
					
				case 'dropdown':
					$(this).find('select')
								.val(value)
								.trigger('chosen:updated');
					break;
			}

			options.el_value = value;
		});
		
		var updateOptions = function(id){
			var cell = $(that.options.calcHolder).calx('getCell', id);

			if (that.values[id]){
				that.values[id].el_value = cell.getValue();
				that.values[id].el_formatted_value = cell.getFormattedValue();				
			}
			
			var dependants = cell.dependant;

			var a;
			
			for(a in dependants){
				updateOptions(a); 
			}
		}
		
		var cell = $(that.options.calcHolder).calx('getCell', id);
		
		if (cell){
			cell
				.setValue(value)
				.calculateAllDependents();

			updateOptions(id);	
		}
	}
	
	RoiShopCalculator.prototype.storeOptions = function() {
		options = this.options;
		values = this.values;

		var values_to_store = [];
		
		for (var key in values){
			values_to_store.push(values[key]);
		}

		$.post("/assets/ajax/calculator.post.php",{
			action: 'storeRoiOptions',
			roi: getQueryVariable('roi'),
			options: JSON.stringify(options),
			values: JSON.stringify(values_to_store)
		}, function(callback){
			
		});
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
	
    $.fn.roishopCalculator.Constructor = RoiShopCalculator;
    $.fn.roishopCalculator.defaults = RoiShopCalculator.DEFAULTS;
    $.fn.roishopCalculator.locales = RoiShopCalculator.LOCALES;
    $.fn.roishopCalculator.methods = allowedMethods;

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