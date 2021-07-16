;(function($, window, document, undefined) {

    'use strict';
	
    var containsAnyValues = function (source, target) {
		if (!source || !target){ return false; }
		var result = source.split(',').filter(function(item){ return target.indexOf(item) > -1});
		return (result.length > 0); 
    };	
	
	var RoiShopDashboard = function(element, options) {
        this.options = options;
        this.el = $(element);
        this.el_ = this.el.clone();
        this.timeoutId_ = 0;
        this.timeoutFooter_ = 0;

        this.init();	
	};
	
	RoiShopDashboard.DEFAULTS = {
		filter: {
			status: true,
			tag: false,
			created: true,
			viewed: false,
			template: 'single'
		},
		sort: true,
		templateSearchThreshhold: 10,
		maxSearchSize: 10,
		maxSearchCount: 1,
		multipleSelect: true,
		buttonsClass: 'default',
        search: true,
        searchOnEnterKey: false,
        strictSearch: false,
        searchAlign: 'right',
        selectItemName: 'btSelectItem',
        showHeader: true,
        showFooter: false,
        showColumns: true,
        showTemplateFilter: true,
		showStatusFilter: false,
		showTagFilter: false,
		showIndustryFilter: false,
		toggleUserStatistics: true,
		statuses: ['Active','Won','Lost','No Decision'],
		tags: ['Favorite'],
		industries: ['Tech Company','Software Support','Industrial Warehouse'],
		userStatisticsToggles: ['Viewed','Unique','Last Created'],
        showRefresh: true,
        showToggle: true,
        buttonsAlign: 'right',
        smartDisplay: true,
        escape: false,
        minimumCountColumns: 1,
        idField: undefined,
        uniqueId: undefined,
        trimOnSearch: true,
        clickToSelect: false,
        singleSelect: false,
        toolbar: undefined,
        toolbarAlign: 'left',
		searchOn: 'keyup',
		sortCaseSensitive: false,
		topNavbarClass: 'navbar-fixed-top',
		navbarMinimalize: true,
		navbarSearch: true,
		frstHeaderColumn: 'col-lg-3',
		scndHeaderColumn: 'col-lg-6',
		thrdHeaderColumn: 'col-lg-3',
		welcomeMessage: true,
		welcomeSubMessage: true,
		userCurrentStatistics: true,
		allowRoiCreation: true,
        pagination: true,
        onlyInfoPagination: false,
        paginationLoop: true,
        sidePagination: 'client',
        totalRows: 0,
        pageNumber: 1,
        pageSize: 10,
        pageList: [10, 25, 50, 100],
        paginationHAlign: 'right',
        paginationDetailHAlign: 'left',
        paginationPreText: '&lsaquo;',
        paginationNextText: '&rsaquo;',		
		createRoiIcon: '<i class="fa fa-plus-square"></i>',
		companyRankTbl: {
			sortCriteria: 'createdRois'
		},
		iconsPrefix: 'fa',
        icons: {
            templateFilter: 'fa-file alt',
            paginationSwitchUp: 'glyphicon-collapse-up icon-chevron-up',
            refresh: 'glyphicon-refresh icon-refresh',
            toggle: 'glyphicon-list-alt icon-list-alt',
            columns: 'glyphicon-th icon-th',
            detailOpen: 'glyphicon-plus icon-plus',
            detailClose: 'glyphicon-minus icon-minus'
        },
		onAll: function (name, args) {
			return false;
		},
        onRoiCreated: function (id, name) {
            return false;
        },
	};
	
	RoiShopDashboard.LOCALES = {};
	
	RoiShopDashboard.LOCALES['en-US'] = RoiShopDashboard.LOCALES.en = {
		formatWelcomeMessage: function(dashboardUser) {
			return sprintf('Welcome %s', dashboardUser);
		},
		formatWelcomeSubtext: function(roiCount, activeCount) {
			return sprintf('You have %s current ROIs and %s active ROIs', roiCount, activeCount);
		},
		formatSeachPlaceholder: function() {
			return '';
		},
		formatCreateRoiBtn: function() {
			return 'Create a New ROI';
		},
		formatProjectListHeader: function() {
			return 'My ROIs';
		},
        formatAllRows: function() {
            return 'All';
        },
        formatRecordsPerPage: function (pageNumber) {
            return sprintf('%s ROIs per page', pageNumber);
        },
        formatShowingRows: function (pageFrom, pageTo, totalRows) {
            return sprintf('Showing %s to %s of %s ROIs', pageFrom, pageTo, totalRows);
        },
        formatSearch: function () {
            return 'Search for ROI';
        },
        formatTemplateFilter: function () {
            return 'Templates';
        },
		formatStatusFilter: function () {
			return 'Status';
		},
		formatTagFilter: function () {
			return 'Tag';
		},
		formatIndustryFilter: function (){
			return 'Industry';
		},
        formatRefresh: function () {
            return 'Refresh';
        },
        formatToggle: function () {
            return 'Toggle';
        },
        formatColumns: function () {
            return 'Columns';
        },
		formatUserStatistics: function (){
			return 'Viewed';
		}
    };

	$.extend(RoiShopDashboard.DEFAULTS, RoiShopDashboard.LOCALES['en-US']);
	
    RoiShopDashboard.EVENTS = {
        'all.rs.dashboard': 'onAll',
        'roi-created.rs.dashboard' : 'onRoiCreated'
    };

	RoiShopDashboard.prototype.init = function () {
		this.initLocale();
		this.initContainer();
		this.initTopNav();
		this.initHeader();
		this.initRois();
    };
	
    RoiShopDashboard.prototype.initLocale = function () {
        if (this.options.locale) {
            var parts = this.options.locale.split(/-|_/);
            parts[0].toLowerCase();
            if (parts[1]) parts[1].toUpperCase();
            if ($.fn.roishopDashboard.locales[this.options.locale]) {
                // locale as requested
                $.extend(this.options, $.fn.roishopDashboard.locales[this.options.locale]);
            } else if ($.fn.roishopDashboard.locales[parts.join('-')]) {
                // locale with sep set to - (in case original was specified with _)
                $.extend(this.options, $.fn.roishopDashboard.locales[parts.join('-')]);
            } else if ($.fn.roishopDashboard.locales[parts[0]]) {
                // short locale language code (i.e. 'en')
                $.extend(this.options, $.fn.roishopDashboard.locales[parts[0]]);
            }
        }
    };
	
    RoiShopDashboard.prototype.initContainer = function () {
        this.$container = $([
            '<nav class="navbar-default navbar-static-side" role="navigation">',
				'<div class="sidebar-collapse">',
					'<ul class="nav" id="side-manu"></ul>',
				'</div>',
			'</nav>',
			'<div id="page-wrapper" class="gray-bg dashboard-1">',
				'<div class="row top-nav"></div>',
				'<div class="row white-bg dashboard-header"></div>',
				'<div class="wrapper wrapper-content animated fadeInRight roi-holder"></div>',
			'</div>'
        ].join(''));

        this.el.append(this.$container);
		this.$dashboardSidebar = this.$container.find('#side-menu'); 
		this.$dashboardTopNav = this.$container.find('.top-nav');
		this.$dashboardHeader = this.$container.find('.dashboard-header');
		this.$dashboardRois = this.$container.find('.roi-holder');
    };
	
	RoiShopDashboard.prototype.initTopNav = function() {
		var that = this,
			html = [];
		
		html.push(
			sprintf('<nav class="navbar %s" role="navigation" style="margin-bottom: 0">', this.options.topNavbarClass));
			
		
		html.push('<div class="navbar-header">');
		
		if (this.options.navbarSearch) {
			html.push(
				'<form class="navbar-form-custom">',
				'<div class="form-group">',
				'</div></form>');
		}
		
		html.push('</div>');

		var admins = [];
		$.each(this.options.permissions, function(){
			if(this.permission >= 49){
				admins.push(this);
			}
		});
		
		html.push('<ul class="nav navbar-top-links navbar-right">');
			
		if(admins.length === 1){
			html.push(sprintf('<li style="padding-right: 15px;"><button class="btn btn-primary share-calculator" type="button"><i class="fa fa-user-plus"></i><a style="color: white;" href="../company/admin/?companyid=%s">%s Admin</a></button></li>' , admins[0]['company_id'], admins[0]['company_name']));
		} else if(admins.length > 1){
			html.push(
				sprintf('<li style="padding-right: 15px;"><select onchange="location = this.value;" data-style="btn btn-primary btn-admins" class="selectpicker" title="%s" name="availableAdmins"', 'Select Admin'));
				
			html.push(' data-size="10" data-width="200px" data-live-search="true">');
				
			$.each(admins, function (i, status) {
				html.push(sprintf('<option value="../company/admin/?companyid=%s">%s</option>', status.company_id, status.company_name));
			});
			
			html.push('</select></li>');
		}

		html.push(
			'<li><span class="m-r-sm text-muted welcome-message">Powered by <a href="https:\\www.theroishop.com" style="padding-left: 0;">The ROI Shop</a></span></li>',
			'<li class="dropdown"><a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">My Actions <i class="fa fa-caret-down"></i></a>',
			'<ul class="dropdown-menu dropdown-alerts">',
			'<li><a href="account.php"><i class="fa fa-user"></i> View Your Profile</a></li>',
			'<li><a href="../assets/logout.php"><i class="fa fa-sign-out"></i> Log out</a></li>',
			'</ul>',
			'</li>',
			'</ul>');
		
		html.push('</nav>');

		this.$dashboardTopNav.append($(html.join('')));
		var $search = this.$dashboardTopNav.find('#top-search');
		$search.off('keyup drop blur').on('keyup drop blur', function (event) {
			if (that.options.searchOnEnterKey && event.keyCode !== 13) {
				return;
			}

			if ($.inArray(event.keyCode, [37, 38, 39, 40]) > -1) {
				return;
			}

			that.onSearch(event);
		});	

		var $admins = this.$dashboardTopNav.find('select[name="availableAdmins"]').selectpicker();
			
		$admins.on('changed.bs.select', function(e){
			console.log($(e.currentTarget).val());
		});
	},
	
	RoiShopDashboard.prototype.initHeader = function() {
		var that = this,
			html = [],
			order = -1;
		
		html.push(
			sprintf('<div class="%s active-rois">', this.options.frstHeaderColumn));
			
		if (this.options.welcomeMessage) {
			html.push(
				sprintf('<h2>%s</h2>', this.options.formatWelcomeMessage(this.options.userinfo.first_name)),
				this.options.welcomeSubMessage ? 
					this.options.formatWelcomeSubtext(this.options.rois.length, this.options.rois.length)
					: ''
			);
		}
		
		html.push('<hr/>');
		
		if (this.options.userCurrentStatistics) {
			if (this.options.toggleUserStatistics) {
				html.push(
					sprintf('<select class="selectpicker" title="%s" data-style="btn-success" name="userStatistics"', this.options.formatUserStatistics()));
				
				html.push(
					sprintf(' data-size="%s" data-width="100%" data-selected-text-format="count > %s">', this.options.maxSearchSize, this.options.maxSearchCount));
				
				$.each(this.options.userStatisticsToggles, function (i, status) {
					html.push(sprintf('<option>%s</option>', status));
				});
			
				html.push('</select>');
			}			
			
			html.push('<ul class="list-group clear-list m-t" userStatistics></ul>');
		}
			
		html.push('</div>');
			
		html.push(
			sprintf('<div class="%s user-roi-graph"></div>', this.options.scndHeaderColumn));
			
		html.push(
			sprintf('<div class="%s roi-creation">', this.options.thrdHeaderColumn));
			
		if (this.options.allowRoiCreation) {
			html.push(
				sprintf('<a class="btn btn-success btn-newroi">%s %s</a>', this.options.createRoiIcon, this.options.formatCreateRoiBtn()));
		}
		
		if (this.options.companyRankTbl) {
			html.push(
				'<table class="table table-hover margin bottom">',
					'<thead>',
						'<tr><th style="width: 1%" class="text-center">Ranking</th><th>Name</th><th class="text-center">ROIs</th></tr>',
					'</thead>',
					'<tbody>');
					
					var users = this.options.compUsers;
					
					users.sort(function(a, b) {
						var aa = a.created_rois;
						var bb = b.created_rois;

						if (aa === undefined || aa === null) {
							aa = '';
						}
						if (bb === undefined || bb === null) {
							bb = '';
						}
						
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

					for(var i=0; i<5; i++) {
						if(users[i]){
							html.push(
								sprintf('<tr><td class="text-center"><span class="label label-primary">%s</span></td><td> %s %s</td><td class="text-center small">%s</td></tr>', i + 1, users[i].first_name, users[i].last_name, users[i].created_rois));
						}
					};
					
					html.push('</tbody>');
		}
			
		this.$dashboardHeader.append($(html.join('')));
		this.$dashboardHeader.find('.btn-newroi')
				.off('click').on('click', $.proxy(this.initNewRoiModal, this));
				
		this.$userStatistics = this.$dashboardHeader.find('ul[userStatistics]');

		if (this.options.toggleUserStatistics) {
			var $toolbar = this.$dashboardHeader.find('select[name="userStatistics"]').selectpicker();
			
			$toolbar.on('changed.bs.select', function(e){
				that.roiRank = $(e.currentTarget).val();
				that.initUserStatisticTable();
			});
		}
		
		this.initUserStatisticTable();
		this.initCreatedRoisGraph();
	}
	
	RoiShopDashboard.prototype.initCreatedRoisGraph = function() {
		var today = new Date(),
			month = today.getMonth(),
			year = today.getYear(),
			month_offset = 11 - month,
			roi_month,
			rois_by_month = [0,0,0,0,0,0,0,0,0,0,0,0],
			last_year = new Date(year + 1899, month + 1, 1),
			dt,
			months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
			past_months = [];
			
		for (var i=1; i<13; i++){
			if(month + i < 12){
				past_months.push(months[month + i]);
			} else {
				past_months.push(months[month + i - 12])
			}
		};

		$.each(this.options.rois, function(){
			if(this.dt){
				dt = new Date(this.dt);
				if (dt > last_year){
					roi_month = dt.getMonth() + month_offset;
					if(roi_month > 11) roi_month -= 12;
					rois_by_month[roi_month]++;
				}			
			}
		});
		
		var $highchart = this.$dashboardHeader.find('.user-roi-graph');

		var highchart_data = {
				chart: {
					type: 'column',
					renderTo: $highchart[0]
				},
				title: {
					text: 'ROIs Created Past Year'
				},
				xAxis: {
					categories: past_months,
					crosshair: true
				},
				yAxis: {
					min: 0,
					title: {
						text: ''
					}
				},
				plotOptions: {
					column: {
						pointPadding: 0.2,
						borderWidth: 0
					}
				},
				series: [{
					name: 'ROIs Created',
					data: rois_by_month

				}],
				credits: {
					enabled: false
				}
			};
		
		this.$highchart = new Highcharts.Chart(highchart_data);
	}
	
	RoiShopDashboard.prototype.initUserStatisticTable = function() {
		var that = this,
			html = [];

		var that = this,
			rois = $.merge([], this.options.rois),
			order = -1,
			option,
			display;

		switch(this.roiRank){
			case 'Last Created':
				option = 'dt';
				display = 'formatted_date';
				break;
			case 'Viewed':
				option = 'visits';
				break;
			case 'Unique':
				option = 'unique_ip';
				break;
			default:
				option = 'visits';
		};

		rois.sort(function(a, b) {
			var aa = a[option];
			var bb = b[option];

			if (aa === undefined || aa === null) {
				aa = '';
			}
			if (bb === undefined || bb === null) {
				bb = '';
			}
			
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
		
		for(var i=0; i<5; i++){
			if(rois[i]){
				html.push(sprintf('<li class="list-group-item fist-item"><span class="pull-right">%s</span><span class="label label-success">%s</span> %s</li>', ( display ? rois[i][display] : rois[i][option] ? rois[i][option] : 0 ), i+1, rois[i].roi_title));
			}
		}
		
		this.$userStatistics.html('').append(html);
	}
	
	RoiShopDashboard.prototype.initRois = function() {
		var that = this,
			$initRois = $([
					'<div class="row">',
						'<div class="col-lg-12">',
							'<div class="ibox" id="user-roi-table">',
								'<div class="ibox-content">',
								sprintf('<h2>%s</h2>', this.options.formatProjectListHeader()),
								'</div>',
							'</div>',
						'</div>',
					'</div>'
			].join(''));
			
		var $content = $initRois.find('.ibox-content');
		
        this.$roiContainer = $([
            '<div class="roi-table">',
				'<div class="fixed-table-toolbar"></div>',
				'<div class="fixed-table-container">',
					'<div class="fixed-table-header"><table></table></div>',
					'<div id="roiTable" class="fixed-table-body"><h2 id="loadingMsg"><center><div class="loading"></div> Please wait while your ROIs are loaded...</center></h2></div>',
					'<div class="fixed-table-footer"><table><tr></tr></table></div>',
					'<div class="fixed-table-pagination"></div>',
				'</div>',
            '</div>'
        ].join(''));

        this.$tableContainer = this.$roiContainer.find('.fixed-table-container');
        this.$tableHeader = this.$roiContainer.find('.fixed-table-header');
        this.$tableBody = this.$roiContainer.find('.fixed-table-body');
        this.$tableFooter = this.$roiContainer.find('.fixed-table-footer');
        this.$toolbar = this.$roiContainer.find('.fixed-table-toolbar');
        this.$pagination = this.$roiContainer.find('.fixed-table-pagination');
		
		$content.append(this.$roiContainer);
		this.$dashboardRois.append($initRois);
	}

    RoiShopDashboard.prototype.initRoiTable = function () {console.log('init roi table')
		var that = this;

		$('#loadingMsg').hide();
		
		this.$roiTable = $([
			'<table class="table table-hover">',
				'<colgroup>',
					'<col width="5%">',
					'<col width="8%">',
					'<col width="8%">',
					'<col width="26%">',
					'<col width="16%">',
					'<col width="10%">',
					'<col width="10%">',
					'<col width="17%">',
				'</colgroup>',
				'<thead>',
					'<tr>',
						this.options.selector ? '<th></th>' : '',
						'<th><div class="th-inner"></div></th>',
						'<th><div class="th-inner" status>Status</div></th>',
						'<th><div class="th-inner" importance>Importance</div></th>',
						'<th><div class="th-inner sortable both" sortName>ROI Name</div></th>',
						'<th><div class="th-inner sortable both" sortDate>Dates</div></th>',
						'<th><div class="th-inner sortable both" sortViews>Views</div></th>',
						'<th><div class="th-inner sortable both" sortUnique>Unique Views</div></th>',
						'<th><div class="th-inner"></div></th>',
					'</tr>',
				'</thead>',
				'<tbody id="user-rois"></tbody>',
			'</table>'
		].join(''));
		
		var $roiTableBody = this.$roiTable.find('#user-rois');
		$.each(this.options.rois, function(roiCount, roi){
			$roiTableBody.append($('<tr/>').roi(roi));
		});

		$('#roiTable').append(this.$roiTable);
		
        this.$roiTable.find('.sortable').off('click').on('click', function (event) {
			that.onSort(event);
		});
		
		this.initToolbar();
		this.initData();	
		this.initPagination();
		this.initBody();
    }
	
    RoiShopDashboard.prototype.initToolbar = function () {
        var that = this,
            html = [];

        html.push('<div class="columns btn-group">');
		
		if (this.options.showTemplateFilter) {
			html.push(
				sprintf('<select class="selectpicker" title="%s" name="templateFilter"', this.options.formatTemplateFilter()));
				
			if (this.options.templates && this.options.templates.length > this.options.templateSearchThreshhold){
				html.push(' data-live-search="true"');
			}
			
			html.push(
				sprintf(' data-size="%s" data-width="100px" data-item-name="template" data-selected-text-format="count > %s" %s>', this.options.maxSearchSize, this.options.maxSearchCount, (this.options.multipleSelect ? 'multiple' : '')));
			
			$.each(this.options.templates, function (i, template) {
				html.push(sprintf('<option>%s</option>', template.version_name));
			});
		
			html.push('</select>');
        }
		
        if (this.options.showStatusFilter) {
			html.push(
				sprintf('<select class="selectpicker" title="%s" name="statusFilter"', this.options.formatStatusFilter()));
			
			html.push(
				sprintf(' data-size="%s" data-width="100px" data-selected-text-format="count > %s" %s>', this.options.maxSearchSize, this.options.maxSearchCount, (this.options.multipleSelect ? 'multiple' : '')));
			
			$.each(this.options.statuses, function (i, status) {
				html.push(sprintf('<option>%s</option>', status));
			});
		
			html.push('</select>');
        }
		
        if (this.options.showTagFilter) {
			html.push(
				sprintf('<select class="selectpicker" title="%s" name="tagFilter"', this.options.formatTagFilter()));
			
			html.push(
				sprintf(' data-size="%s" data-width="100px" data-selected-text-format="count > %s" %s>', this.options.maxSearchSize, this.options.maxSearchCount, (this.options.multipleSelect ? 'multiple' : '')));
			
			$.each(this.options.tags, function (i, tag) {
				html.push(sprintf('<option>%s</option>', tag));
			});
		
			html.push('</select>');
        }
		
        if (this.options.showIndustryFilter) {
			html.push(
				sprintf('<select class="selectpicker" title="%s" name="industryFilter"', this.options.formatIndustryFilter()));
			
			html.push(
				sprintf(' data-size="%s" data-width="100px" data-selected-text-format="count > %s" %s>', this.options.maxSearchSize, this.options.maxSearchCount, (this.options.multipleSelect ? 'multiple' : '')));
			
			$.each(this.options.industries, function (i, tag) {
				html.push(sprintf('<option>%s</option>', tag));
			});
		
			html.push('</select>');
        }
		
		html.push('</div>');
		
		this.$toolbar.append(html.join(''));
		
		if (this.options.showTemplateFilter) {
			var $toolbar = this.$toolbar.find('select[name="templateFilter"]').selectpicker();
			
			$toolbar.on('changed.bs.select', function(e){
				that.filteredTemplates = $(e.currentTarget).val();
				that.updatePagination();
			});
		}
		
		if (this.options.showStatusFilter) {
			var $toolbar = this.$toolbar.find('select[name="statusFilter"]').selectpicker();
			
			$toolbar.on('changed.bs.select', function(e){
				that.filteredStatuses = $(e.currentTarget).val();
				that.updatePagination();
			});
		}
		
		if (this.options.showTagFilter) {
			var $toolbar = this.$toolbar.find('select[name="tagFilter"]').selectpicker();
			
			$toolbar.on('changed.bs.select', function(e){
				that.filteredTags = $(e.currentTarget).val();
				that.updatePagination();
			});
		}
		
		if (this.options.showIndustryFilter) {
			var $toolbar = this.$toolbar.find('select[name="industryFilter"]').selectpicker();
			
			$toolbar.on('changed.bs.select', function(e){
				that.filteredIndustries = $(e.currentTarget).val();
				that.updatePagination();
			});			
		}

        if (this.options.search) {
            html = [];
            html.push(
                '<div class="pull-' + this.options.searchAlign + ' search">',
					sprintf('<input class="form-control' + sprintf(' input-%s', this.options.iconSize) + '" type="text" placeholder="%s">', this.options.formatSearch()),
                '</div>');

            this.$toolbar.append(html.join(''));
            var $search = this.$toolbar.find('.search input');
            $search.off('keyup drop blur').on('keyup drop blur', function (event) {
                if (that.options.searchOnEnterKey && event.keyCode !== 13) {
                    return;
                }

                if ($.inArray(event.keyCode, [37, 38, 39, 40]) > -1) {
                    return;
                }

                that.onSearch(event);
            });
			
        }
    }	
	
    RoiShopDashboard.prototype.initData = function (data, type) {
		var that = this,
			rois = $('#user-rois tr').get(),
			filteredRois = [],
			templatesToSearch = [];
		
		this.filteredRois = [];
		$.each(rois, function(i, roi){
			filteredRois.push(i);
		});
		
		if (this.searchByTerm){
			templatesToSearch = filteredRois;
			filteredRois = [];
			
			$.each(templatesToSearch, function(i, roiIndex){
				if ($(rois[roiIndex]).data('roi-element').options.roi_title.toLowerCase().indexOf(that.searchByTerm.toLowerCase()) > -1){
					filteredRois.push(i);
				}
			});	
		}
		
		if (this.filteredTemplates && this.filteredTemplates.length > 0){
			templatesToSearch = filteredRois;
			filteredRois = [];

			$.each(templatesToSearch, function(i, roiIndex){
				if (that.filteredTemplates.indexOf($(rois[roiIndex]).data('roi-element').options.version_name) > -1){
					filteredRois.push(roiIndex);
				}
			});				
		}

		if (this.filteredStatuses && this.filteredStatuses.length > 0){
			templatesToSearch = filteredRois;
			filteredRois = [];
			
			$.each(templatesToSearch, function(i, roiIndex){
				if (that.filteredStatuses.indexOf($(rois[roiIndex]).data('roi-element').options.status) > -1){
					filteredRois.push(roiIndex);
				}
			});				
		}
		
		if (this.filteredTags && this.filteredTags.length > 0){
			templatesToSearch = filteredRois;
			filteredRois = [];
			
			$.each(templatesToSearch, function(i, roiIndex){
				var haystack = $(rois[roiIndex]).data('roi-element').options.tags,
					arr = that.filteredTags;
				
				if (containsAnyValues(haystack, arr)){
					filteredRois.push(roiIndex);
				}
			});				
		}

		this.filteredRois = filteredRois;
    }
	
    RoiShopDashboard.prototype.onSort = function (event) {
		var $header = $(event.currentTarget);
		if (this.options.sortName === $header.html()) {
            this.options.sortOrder = this.options.sortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            this.options.sortName = $header.html();
            this.options.sortOrder = 'asc';
        }
		
		this.getCaret();
		this.updatePagination();
    };
	
    RoiShopDashboard.prototype.getCaret = function () {
        var that = this;

        $.each(this.$roiTable.find('th'), function (i, th) {
			$(th).find('.sortable').removeClass('desc asc').addClass($(th).find('.sortable').html() === that.options.sortName ? that.options.sortOrder : 'both');
        });
    };	
	
	RoiShopDashboard.prototype.initSort = function() {
		if (!this.options.sortName){
			return;
		}
		
		var that = this,
			rois = $('#user-rois tr').get(),
			order = this.options.sortOrder === 'desc' ? -1 : 1,
			option;

		switch(this.options.sortName){
			case 'ROI Name':
				option = 'roi_title';
				break;
			case 'Dates':
				option = 'dt';
				break;
			case 'Views':
				option = 'visits';
				break;
			case 'Unique Views':
				option = 'unique_ip';
				break;
			default:
				option = 'roi_title';
		};

		rois.sort(function(a, b) {
			var aa = $(a).data('roi-element').options[option];
			var bb = $(b).data('roi-element').options[option];

			if (aa === undefined || aa === null) {
				aa = '';
			}
			if (bb === undefined || bb === null) {
				bb = '';
			}

			if (that.options.sortStable && aa === bb) {
				aa = a._position;
				bb = b._position;
			}
			
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
			
		$.each(rois, function(index, roi){
			$('#user-rois').append(roi);
		});		
	}
	
    RoiShopDashboard.prototype.initPagination = function () {
        if (!this.options.pagination) {
            this.$pagination.hide();
            return;
        } else {
            this.$pagination.show();
        }

        var that = this,
            html = [],
            $allSelected = false,
            i, from, to,
            $pageList,
            $first, $pre,
            $next, $last,
            $number,
            pageList = this.options.pageList;

		this.options.totalRows = this.filteredRois.length;

        this.totalPages = 0;
        if (this.options.totalRows) {
            if (this.options.pageSize === this.options.formatAllRows()) {
                this.options.pageSize = this.options.totalRows;
                $allSelected = true;
            } else if (this.options.pageSize === this.options.totalRows) {
                // Fix #667 Table with pagination,
                // multiple pages and a search that matches to one page throws exception
                var pageLst = typeof this.options.pageList === 'string' ?
                    this.options.pageList.replace('[', '').replace(']', '')
                        .replace(/ /g, '').toLowerCase().split(',') : this.options.pageList;
                if ($.inArray(this.options.formatAllRows().toLowerCase(), pageLst)  > -1) {
                    $allSelected = true;
                }
            }

            this.totalPages = ~~((this.options.totalRows - 1) / this.options.pageSize) + 1;

            this.options.totalPages = this.totalPages;
        }
        if (this.totalPages > 0 && this.options.pageNumber > this.totalPages) {
            this.options.pageNumber = this.totalPages;
        }

        this.pageFrom = (this.options.pageNumber - 1) * this.options.pageSize + 1;
        this.pageTo = this.options.pageNumber * this.options.pageSize;
        if (this.pageTo > this.options.totalRows) {
            this.pageTo = this.options.totalRows;
        }

        html.push(
            '<div class="pull-' + this.options.paginationDetailHAlign + ' pagination-detail">',
            '<span class="pagination-info">',
            this.options.onlyInfoPagination ? this.options.formatDetailPagination(this.options.totalRows) :
            this.options.formatShowingRows(this.pageFrom, this.pageTo, this.options.totalRows),
            '</span>');

        if (!this.options.onlyInfoPagination) {
            html.push('<span class="page-list">');

            var pageNumber = [
                    sprintf('<span class="btn-group %s">', 'dropdown'),
                    '<button type="button" class="btn' +
                    sprintf(' btn-%s', this.options.buttonsClass) +
                    sprintf(' btn-%s', this.options.iconSize) +
                    ' dropdown-toggle" data-toggle="dropdown">',
                    '<span class="page-size">',
                    $allSelected ? this.options.formatAllRows() : this.options.pageSize,
                    '</span>',
                    ' <span class="caret"></span>',
                    '</button>',
                    '<ul class="dropdown-menu" role="menu">'
                ];

            if (typeof this.options.pageList === 'string') {
                var list = this.options.pageList.replace('[', '').replace(']', '')
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

            html.push(this.options.formatRecordsPerPage(pageNumber.join('')));
            html.push('</span>');

            html.push('</div>',
                '<div class="pull-' + this.options.paginationHAlign + ' pagination">',
                '<ul class="pagination' + sprintf(' pagination-%s', this.options.iconSize) + '">',
                '<li class="page-pre"><a href="#">' + this.options.paginationPreText + '</a></li>');

            if (this.totalPages < 5) {
                from = 1;
                to = this.totalPages;
            } else {
                from = this.options.pageNumber - 2;
                to = from + 4;
                if (from < 1) {
                    from = 1;
                    to = 5;
                }
                if (to > this.totalPages) {
                    to = this.totalPages;
                    from = to - 4;
                }
            }

            if (this.totalPages >= 6) {
                if (this.options.pageNumber >= 3) {
                    html.push('<li class="page-first' + (1 === this.options.pageNumber ? ' active' : '') + '">',
                        '<a href="#">', 1, '</a>',
                        '</li>');

                    from++;
                }

                if (this.options.pageNumber >= 4) {
                    if (this.options.pageNumber == 4 || this.totalPages == 6 || this.totalPages == 7) {
                        from--;
                    } else {
                        html.push('<li class="page-first-separator disabled">',
                            '<a href="#">...</a>',
                            '</li>');
                    }

                    to--;
                }
            }

            if (this.totalPages >= 7) {
                if (this.options.pageNumber >= (this.totalPages - 2)) {
                    from--;
                }
            }

            if (this.totalPages == 6) {
                if (this.options.pageNumber >= (this.totalPages - 2)) {
                    to++;
                }
            } else if (this.totalPages >= 7) {
                if (this.totalPages == 7 || this.options.pageNumber >= (this.totalPages - 3)) {
                    to++;
                }
            }

            for (i = from; i <= to; i++) {
                html.push('<li class="page-number' + (i === this.options.pageNumber ? ' active' : '') + '">',
                    '<a href="#">', i, '</a>',
                    '</li>');
            }

            if (this.totalPages >= 8) {
                if (this.options.pageNumber <= (this.totalPages - 4)) {
                    html.push('<li class="page-last-separator disabled">',
                        '<a href="#">...</a>',
                        '</li>');
                }
            }

            if (this.totalPages >= 6) {
                if (this.options.pageNumber <= (this.totalPages - 3)) {
                    html.push('<li class="page-last' + (this.totalPages === this.options.pageNumber ? ' active' : '') + '">',
                        '<a href="#">', this.totalPages, '</a>',
                        '</li>');
                }
            }

            html.push(
                '<li class="page-next"><a href="#">' + this.options.paginationNextText + '</a></li>',
                '</ul>',
                '</div>');
        }
        this.$pagination.html(html.join(''));

        if (!this.options.onlyInfoPagination) {
            $pageList = this.$pagination.find('.page-list a');
            $first = this.$pagination.find('.page-first');
            $pre = this.$pagination.find('.page-pre');
            $next = this.$pagination.find('.page-next');
            $last = this.$pagination.find('.page-last');
            $number = this.$pagination.find('.page-number');

            if (this.options.smartDisplay) {
                if (this.totalPages <= 1) {
                    this.$pagination.find('div.pagination').hide();
                }
                if (pageList.length < 2 || this.options.totalRows <= pageList[0]) {
                    this.$pagination.find('span.page-list').hide();
                }

                // when data is empty, hide the pagination
                this.$pagination[this.options.rois.length ? 'show' : 'hide']();
            }

            if (!this.options.paginationLoop) {
                if (this.options.pageNumber === 1) {
                    $pre.addClass('disabled');
                }
                if (this.options.pageNumber === this.totalPages) {
                    $next.addClass('disabled');
                }
            }

            if ($allSelected) {
                this.options.pageSize = this.options.formatAllRows();
            }
            $pageList.off('click').on('click', $.proxy(this.onPageListChange, this));
            $first.off('click').on('click', $.proxy(this.onPageFirst, this));
            $pre.off('click').on('click', $.proxy(this.onPagePre, this));
            $next.off('click').on('click', $.proxy(this.onPageNext, this));
            $last.off('click').on('click', $.proxy(this.onPageLast, this));
            $number.off('click').on('click', $.proxy(this.onPageNumber, this));
        }
	}
	
    RoiShopDashboard.prototype.onPageFirst = function (event) {
        this.options.pageNumber = 1;
        this.updatePagination(event);
        return false;
    }
	
    RoiShopDashboard.prototype.onPagePre = function (event) {
        if ((this.options.pageNumber - 1) === 0) {
            this.options.pageNumber = this.options.totalPages;
        } else {
            this.options.pageNumber--;
        }
        this.updatePagination(event);
        return false;
    }
	
    RoiShopDashboard.prototype.onPageLast = function (event) {
        this.options.pageNumber = this.totalPages;
        this.updatePagination(event);
        return false;
    }

    RoiShopDashboard.prototype.onPageNumber = function (event) {
        if (this.options.pageNumber === +$(event.currentTarget).text()) {
            return;
        }
        this.options.pageNumber = +$(event.currentTarget).text();
        this.updatePagination(event);
        return false;
    }

    RoiShopDashboard.prototype.onPageNext = function (event) {
        if ((this.options.pageNumber + 1) > this.options.totalPages) {
            this.options.pageNumber = 1;
        } else {
            this.options.pageNumber++;
        }
        this.updatePagination(event);
        return false;
    };
	
    RoiShopDashboard.prototype.onPageListChange = function (event) {
        var $this = $(event.currentTarget);

        $this.parent().addClass('active').siblings().removeClass('active');
        this.options.pageSize = $this.text().toUpperCase() === this.options.formatAllRows().toUpperCase() ?
            this.options.formatAllRows() : +$this.text();
        this.$toolbar.find('.page-size').text(this.options.pageSize);

        this.updatePagination(event);
        return false;
    }
	
    RoiShopDashboard.prototype.updatePagination = function (event) {
		this.initSort();
		this.initData();		
		this.initPagination();
		this.initBody();
    }

    RoiShopDashboard.prototype.initBody = function (fixedScroll) {
		var that = this,
			rois = $('#user-rois tr').get(),
			option;
		
		$.each(rois, function(i, roi){
			$(roi).hide();
		});
		
		for (var i = this.pageFrom - 1; i < this.pageTo; i++) {
			if (this.filteredRois && this.filteredRois.length){
				$(rois[this.filteredRois[i]]).show();
			} else {
				$(rois[i]).show();
			}
        }
    }	

	RoiShopDashboard.prototype.initNewRoiModal = function(){
		var that = this;
			
		this.$modal = $('<div class="modal inmodal fade in"></div>');
		
		var $dialog = $([
			'<div class="modal-dialog modal-lg">',
				'<div class="modal-content animated fadeIn">',
				'</div>',
			'</div>'		
		].join(''));
		
		var $body = $([
			'<div class="modal-body rsmodal">',
				'<div class="row" style="margin-left: 25px; margin-right: 25px; margin-top: 10px;">',
					'<h2><strong>Create A New ROI Calculation</strong></h2><hr/>',
					'<div class="form-group">',
						'<label class="col-lg-3 control-label">Name</label>',
						'<div class="col-lg-9">',
							'<input placeholder="Name" class="form-control" roiName>',
						'</div>',
					'</div>',
					'<div class="form-group">',
						'<label class="col-lg-3 control-label">Template</label>',
						'<div class="col-lg-9">',
							'<select class="form-control" templates></select>',
						'</div>',
					'</div>',
					'<div class="form-group pull-right" style="margin-bottom: 0px;">',
						'<div class="col-lg-12 rs-check" style="margin-bottom: 0px;" roiOpen data-style="check" data-text="true">',
							'<input type="checkbox" checked/>',
							'<label><i></i> Open the Created ROI</label>',
						'</div>',
					'</div>',
					'<div class="clearfix"></div>',
					'<hr/>',
				'</div>',
			'</div>'		
		].join(''));
		
		var $footer = $([
			'<div class="modal-footer">',
				'<div class="rsmodal-buttonwrapper">',
					'<button type="button" class="btn rsmodal-button rsmodal-confirm" create>Create ROI</button>',
					'<button type="button" class="btn rsmodal-button rsmodal-cancel" data-dismiss="modal">Close</button>',
				'</div>',
			'</div>'		
		].join(''));
		
		var $input = $body.find('input[roiName]');
		var $select = $body.find('select[templates]');
		var $checkbox = $body.find('div[roiOpen]').find('label');
		var $create = $footer.find('button[create]');
		var $tagsinput = $body.find('input[tagsInput]');
		
		$.each(this.options.templates, function(optCount, option){
			
			var $option = $(sprintf('<option>%s</option>',option.version_name)).data('template', option);
			$select.append($option);
		});
		
		$select.chosen({width: '100%', disable_search_threshold: 10});
		$create.off('click').on('click', $.proxy(this.createNewRoi, this));
		
		$checkbox.off('click').on('click', function(){ $(this).parent().find('input').prop('checked', !$(this).parent().find('input').prop('checked')) })
		
		$dialog.find('.modal-content').append($body).append($footer);
		$tagsinput.tagsinput();
		this.$modal.append($dialog).modal('show');
	}
		
	RoiShopDashboard.prototype.createNewRoi = function() {
		var new_roi_name = this.$modal.find('input[roiName]').val(),
			template = this.$modal.find('select[templates]').find(':selected').data('template'),
			open_roi = this.$modal.find('.modal-body').find('div[roiOpen]').find('input').prop('checked'),
			that = this;

		if(new_roi_name.replace(/^\s+|\s+$/g, "").length !== 0){
				
			$.ajax({
				type: 'POST',
				url: '/php/ajax/dashboard/dashboard.post.php',
				data: {
					action: 'createroi',
					roiName: new_roi_name,
					template: template.version_id,
					currency: this.options.userinfo.currency
				},
				success: function(createdRoi) {
					if(open_roi) {
						window.location.href = sprintf('../%s?roi=%s', template.template_path, createdRoi);
					}
					that.trigger('roi-created', createdRoi, new_roi_name)
				}
			});
		} else {
			this.$modal.find('input[roiName]').css('border-color','red');
		}
	}
		
	RoiShopDashboard.prototype.filterStatus = function() {
		var status = $(this).html().toLowerCase(),
			rois = $('#user-rois tr').get();

		$.each(rois, function(){
			$(this).hide();

			if($(this).data('roi-element').options.status.toLowerCase() == status){
				$(this).show();
			}
		});
	}
		
	RoiShopDashboard.prototype.filterTag = function() {
			
	}
		
	RoiShopDashboard.prototype.filterCreated = function() {
		var date_range = $(this).html(),
			rois = $('#user-rois tr').get(),
			now = new Date(),
			diff;
				
		switch(date_range){
			case 'Last Day':
				diff = 24 * 3600 * 1000;
				break;
			case 'Last Week':
				diff = 7 * 24 * 3600 * 1000;
				break;
			case 'Last 30 Days':
				diff = 30 * 24 * 3600 * 1000;
				break;
			case 'Last 180 Days':
				diff = 180 * 24 * 3600 * 1000;
				break;
		};

		$.each(rois, function(){
			$(this).hide();
				
			if( now - new Date($(this).data('roi-element').options.dt) < diff ){
				$(this).show();
			};
		});			
	}
		
	RoiShopDashboard.prototype.filterViewed = function() {
			
	}
		
	RoiShopDashboard.prototype.filterTemplate = function() {
		var templates = $(this).val(),
			rois = $('#user-rois tr').get();

		if(!templates){
			$.each(rois, function(){
				$(this).show();
			});
			return;
		};
			
		if(!$.isArray(templates)) {
			templates = templates.split();
		}
			
		$.each(rois, function(){
			$(this).hide();
			
			if($.inArray($(this).data('roi-element').options.version_name, templates) !== -1){
				$(this).show();
			}
		});			
	}
		
	RoiShopDashboard.prototype.onSearch = function(event) {
		this.searchByTerm = $(event.currentTarget).val();
		
		this.updatePagination();
	}
	
	RoiShopDashboard.prototype.trigger = function(name) {
		var args = Array.prototype.slice.call(arguments, 1);

		name += '.rs.dashboard';
		this.options[RoiShopDashboard.EVENTS[name]].apply(this.options, args);
		this.el.trigger($.Event(name), args);

		this.options.onAll(name, args);
		this.el.trigger($.Event('all.rs.dashboard'), [name, args]);			
	}
	
	var allowedMethods = ['initRoiTable'];
	 
	$.fn.roishopDashboard = function(option) {

        var value,
            args = Array.prototype.slice.call(arguments, 1);

        this.each(function () {
            var $this = $(this),
                data = $this.data('roishop.dashboard'),
                options = $.extend({}, RoiShopDashboard.DEFAULTS, $this.data(),
                    typeof option === 'object' && option);

            if (typeof option === 'string') {
                console.log(option);
				if ($.inArray(option, allowedMethods) < 0) {
                    throw new Error("Unknown method: " + option);
                }

                if (!data) {
                    return;
                }

                value = data[option].apply(data, args);

                if (option === 'destroy') {
                    $this.removeData('roishop.dashboard');
                }
            }

            if (!data) {
                $this.data('roishop.dashboard', (data = new RoiShopDashboard(this, options)));
            }
        });

        return typeof value === 'undefined' ? this : value;
    };
	
    $.fn.roishopDashboard.Constructor = RoiShopDashboard;
    $.fn.roishopDashboard.defaults = RoiShopDashboard.DEFAULTS;
    $.fn.roishopDashboard.locales = RoiShopDashboard.LOCALES;
    $.fn.roishopDashboard.methods = allowedMethods;

})(window.jQuery || window.Zepto, window, document);

;(function($, window, document, undefined) {

    var defaults = {
		selector: false,
		share: false,
		showfavorite: false,
		viewed: false,
		completion: false
    };

	function Roi(element, options) {
        this.w  = $(document);
        this.el = $(element);
        this.options = $.extend({}, defaults, options);
		this.elements = {};
        this.init();
    };

    Roi.prototype = {
		
		init: function() {
			this.$container = this.el;	
			this.opening();
			this.status();
			this.rating();
			this.roiTitle();
			this.created();
			if (this.options.completion) {
				this.completion();
			}
			this.viewed();
			this.unique();
			this.actions();
        },
		
        status: function(){
			var options = this.options,
				id = this.options.id,
				roi = this.options.roi_id,
				importance = this.options.importance || 0;

			var row = this.el;

            this.$status = $([
                '<td class="status">',
                    '<select data-width="100%" class="selectpicker">',
                        '<option value="0">Active</option>',
                        '<option value="1">Closed - Won</option>',
                        '<option value="2">Closed - Lost</option>',
                    '</select>',
                '</td>'
            ].join(''));

            this.el.append(this.$status);

            var $status = this.$status.find('select');
            $status.select2()
                .val(this.options.status || 0).trigger('change')
                .on('change', function(e){
					$.ajax({
						type: 'POST',
						url: '/php/ajax/dashboard/dashboard.post.php',
						data: {
							action: 'updatestatus',
							roi: roi,
							status: $(e.target).val()
						}
					});

					if($(e.target).val() == 1){
						this.$modal = $([
							'<div class="modal inmodal fade in">',
							'</div>'
						].join(''));
						this.$container = $([
							'<div class="modal-dialog">',
							'<div class="modal-content animated fadeIn">',
							'</div>',
							'</div>'
						].join(''));
						this.$modalContent = this.$container.find('.modal-content');
						this.$modalHeader = $([
							'<div class="modal-header">',
							'<h2 class="modal-title"><strong>Congrats on winning this opportunity!</strong><br/>Please rate how useful this tool was in the process</h2>',
							'</div>'
						].join(''));
						this.$modalContent.append(this.$modalHeader);
						this.$modalBody = $([
							'<div class="starrr-modal modal-body">',
							'<td class="status">',
							'<div class="starrr"></div>',
							'</td>',
							'</div>'
						].join(''));
						this.$modalInput = this.$modalBody.find('.changed-roi-name');
						this.$modalContent.append(this.$modalBody);
						this.$modal.append(this.$container);
						
						this.$modal.modal('show');

						this.$modal.find('.starrr').starrr({
							rating: importance,
							emptyClass: 'fa fa-star-o large-star',
							fullClass: 'fa fa-star large-star',
							change: function(e, value){
								$.ajax({
									type: 'POST',
									url: '/php/ajax/dashboard/dashboard.post.php',
									data: {
										action: 'updateimport',
										roi: roi,
										importance: value
									}
								});

								row.find('.rating').starrr('setRating', value);
								importance = value;
							}
						});
					}
                });
		},
		
        rating: function(){
			var id = this.options.id,
				roi = this.options.roi_id,
                importance = this.options.importance || 0;
            
            this.$rating = $([
                '<td class="rating">',
                    '<div class="starrr"></div>',
                '</td>'
            ].join(''));

            this.el.append(this.$rating);
            this.el.find('.starrr').starrr({
				rating: importance,
				readOnly: true,
                change: function(e, value){
					$.ajax({
						type: 'POST',
						url: '/php/ajax/dashboard/dashboard.post.php',
						data: {
							action: 'updateimport',
							roi: roi,
							importance: value
						}
					});
                }
            });
        },
		
		opening: function() {
			this.$opening = $([
				'<td class="project-status vert-aligned">',
				'<a class="btn btn-success btn-sm" open><i class="fa fa-folder-open"></i> Open </a>',
				'</td>'
			].join(''));
			
			if (this.options.share) {
				this.$share = $([
					'<div class="btn-group">',
					'<button data-toggle="dropdown" class="btn btn-white dropdown-toggle prop_btn btn-sm" aria-expanded="false"> <i class="fa fa-share-alt"></i> <span class="caret"></span></button>',
					'<ul class="dropdown-menu">',
					'<li><a data-toggle="modal" href="#modal-sendlink-email">Send Via Email</a></li>',
					'<li><a data-toggle="modal" href="#modal-roilink">ROI Link</a></li>',
					'<li class="divider"></li><li><a href="#">Share Log</a></li>',
					'</ul>',
					'</div>'				
				].join(''));
				
				this.$opening.append(this.$share);
			}
			
			if (this.options.showfavorite) {
				this.$favorite = $([
					sprintf('<i class="fa %s favorite"></i>', (this.options.favorite == 1 ? 'fa-star' : 'fa-star-o'))
				].join(''));
				
				this.$opening.append(this.$favorite);
				this.$favorite.off('click').on('click', this.toggleFavorite);
			}
			
			this.$openbtn = this.$opening.find('[open]');
			this.$openbtn.off('click').on('click', $.proxy(this.openRoi, this));
			
			this.el.append(this.$opening);
		},
		
		toggleFavorite: function() {
			$(this).toggleClass('fa-star').toggleClass('fa-star-o')
		},
		
		openRoi: function() {
			window.location.href = this.options.roi_full_path;
		},
		
		roiTitle: function() {
			this.$title = $([
				'<td class="project-title">',
				sprintf('<a class="roi-title">%s</a>',(this.options.roi_title ? this.options.roi_title : 'unnamed')),
				'<br/>',
				sprintf('<small>%s</small>',this.options.version_name),
				'</td>'
			].join(''));
			
			this.$openbtn = this.$title.find('.roi-title');
			this.$openbtn.off('click').on('click', $.proxy(this.changeRoiTitle, this));			
			
			this.el.append(this.$title);
		},

		cloneRoi: function() {
			var $modal = $('<div></div>'),
				original = this,
				clone_name;

			$modal.rsmodal({
				header: {
					content: [{
						html: sprintf("<h2>Create a clone of %s <small class=\"font-bold\"><br/><span style=\"color: white;\">Cloning this calculator will create an identical copy using the last saved set of values.</small>", this.options.roi_title)
					}]
				},
				body: {
					content: [{
						attributes: {
							class: 'form-group'
						},
						children: [{
							html: '<label class="col-lg-5">Name</label>'
						},{
							attributes:{
								class: 'col-lg-7 input-holder'
							},
							children:[{
								tag: 'input',
								attributes: {
									class: 'form-control'
								},
								actions: {
									change: function(){
										clone_name = $(this).val();
									}
								}
							}]
						}]
					},{
						attributes: {
							style: "float:right;"
						},
						children:[{
							html: '<a type="button" class="btn btn-primary">Create Clone</a>',
							jqElement: true,
							actions: {
								click: function(){
									var roi_id = original.options.roi_id;

									rsAjax({
										type: "POST",
										data: {
											action: "createClone",
											roi_id: roi_id,
											title: clone_name
										},
										success: function(path){
											window.location.href = path;
										}
									});
								}
							}
						},{
							html: '<a type="button" class="btn btn-white">Close</a>',
							jqElement: true,
							actions: {
								click: function(){
									$modal.rsmodal('close');
								}
							}
						}]
					},{
						attributes: {
							"style": "clear:both;"
						}
					}]
				}
			})
		},
		
		changeRoiTitle: function() {
			this.$modal = $([
				'<div class="modal inmodal fade in">',
				'</div>'
			].join(''));
			this.$container = $([
				'<div class="modal-dialog">',
				'<div class="modal-content animated fadeIn">',
				'</div>',
				'</div>'
			].join(''));
			this.$modalContent = this.$container.find('.modal-content');
			this.$modalHeader = $([
				'<div class="modal-header">',
				'<button type="button" class="close" data-dismiss="modal">',
				'<span aria-hidden="true">&times;</span>',
				'<span class="sr-only">Close</span>',
				'</button>',
				sprintf('<h4 class="modal-title">%s</h4>',this.options.roi_title),
				'</div>'
			].join(''));
			this.$modalContent.append(this.$modalHeader);
			this.$modalBody = $([
				'<div class="modal-body">',
				'<form class="form-horizontal">',
				'<div class="form-group">',
				'<label class="control-label col-lg-5 col-md-5 col-sm-12">Change ROI Name to: </label>',
				'<div class="col-lg-7 col-md-7 col-sm-12">',
				'<input class="form-control changed-roi-name"/>',
				'</div>',
				'</div>',
				'</form>',
				'</div>'
			].join(''));
			this.$modalInput = this.$modalBody.find('.changed-roi-name');
			this.$modalContent.append(this.$modalBody);
			this.$modalFooter = $([
				'<div class="modal-footer">',
				'<button type="button" class="btn btn-primary change-name">Change ROI Name</button>',
				'<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>',
				'</div>'
			].join(''));
			this.$modalContent.append(this.$modalFooter);
			this.$modal.append(this.$container);
			
			this.$change = this.$modalFooter.find('.change-name');
			this.$change.off('click').on('click', $.proxy(this.updateRoiTitle, this));
			
			this.$modal.modal('show');
		},
		
		updateRoiTitle: function() {
			var $this = this,
				newTitle = this.$modalInput.val();
				
			$.ajax({
				type: 'POST',
				url: '/php/ajax/dashboard/dashboard.post.php',
				data: {
					action: 'renameroi',
					roi: this.options.roi_id,
					name: newTitle
				},
				success	:	function() {
					$this.options.roi_title = newTitle
					$this.$title.find('.roi-title').hide().html($this.options.roi_title).fadeIn(1000);
					$this.$modal.modal('hide');
				}
			});
		},
		
		created: function() {
			this.$created = $([
				'<td class="project-title">',
				sprintf('<small>Date Created: %s</small>',this.options.formatted_date),
				'<br/>',
				(this.options.viewed ? sprintf('<small>Last Viewed: %s</small>','TBD') : ''),
				'</td>'
			].join(''));
			
			this.el.append(this.$created);
		},
		
		completion: function() {
			this.$completion = $([
				'<td class="project-completion">',
				sprintf('<small>Completed: %s</small>','48%'),
				'<div class="progress progress-mini">',
				'<div style="width: 48%;" class="progress-bar darkblue"></div>',
				'</div>',
				'</td>'
			].join(''));
			
			this.el.append(this.$completion);			
		},
		
		viewed: function() {
			this.$viewed = $([
				'<td class="project-title">',
				sprintf('<span class="visitors">%s</span>',(this.options.visits ? this.options.visits : 0)),
				'<br/>',
				'<small>Total Views</small>',
				'</td>'
			].join(''));
			
			this.el.append(this.$viewed);			
		},
		
		unique: function() {
			this.$unique = $([
				'<td class="project-title">',
				sprintf('<span class="visitors">%s</span>',(this.options.unique_ip ? this.options.unique_ip : 0)),
				'<br/>',
				'<small>Unique Visitors</small>',
				'</td>'
			].join(''));
			
			this.el.append(this.$unique);			
		},
		
		actions: function() {
			var clone_available = ['14', '15'];

			this.$actions = $([
				'<td class="project-actions">',
				'<a class="btn btn-white btn-sm" edit><i class="fa fa-pencil"></i> Edit </a>',
				sprintf('%s', clone_available.indexOf(this.options.ep_version_level) > -1 ? '<a class="btn btn-primary btn-sm" clone><i class="fa fa-clone"></i> Clone </a>' : ''),
				'<a class="btn btn-danger btn-sm" delete><i class="fa fa-trash"></i> Delete </a>',
				'</td>'
			].join(''));
			
			this.$editroi = this.$actions.find('[edit]');
			this.$editroi.off('click').on('click', $.proxy(this.changeRoiTitle, this));

			this.$cloneroi = this.$actions.find('[clone]');
			this.$cloneroi.off('click').on('click', $.proxy(this.cloneRoi, this));
			
			this.$deleteroi = this.$actions.find('[delete]');
			this.$deleteroi.off('click').on('click', $.proxy(this.confirmDelete, this));			
			
			this.el.append(this.$actions);				
		},
		
		editRoi: function() {
			console.log(this);
		},
		
		confirmDelete: function() {
			this.$modal = $([
				'<div class="modal inmodal fade in">',
				'</div>'
			].join(''));
			this.$container = $([
				'<div class="modal-dialog">',
				'<div class="modal-content animated fadeIn">',
				'</div>',
				'</div>'
			].join(''));
			this.$modalContent = this.$container.find('.modal-content');
			this.$modalBody = $([
				'<div class="modal-body rsmodal">',
				'<div class="rsmodal-icon rsmodal-warning" style="display: block;border-top-width: 4px;">!</div>',
				'<h2 class="rsmodal-title">Are you sure?</h2>',
				sprintf('<div class="rsmodal-content" style="display: block;">You will not be able to recover <strong>%s</strong>!</div>', this.options.roi_title),
				'</div>'
			].join(''));
			this.$modalContent.append(this.$modalBody);
			this.$modalFooter = $([
				'<div class="modal-footer">',
				'<div class="rsmodal-buttonwrapper">',
				'<button type="button" class="btn rsmodal-button rsmodal-confirm" delete>Delete</button>',
				'<button type="button" class="btn rsmodal-button rsmodal-cancel" data-dismiss="modal">Close</button>',
				'</div>',
				'</div>'
			].join(''));
			this.$modalContent.append(this.$modalFooter);
			this.$modal.append(this.$container);
			
			this.$delete = this.$modalFooter.find('[delete]');
			this.$delete.off('click').on('click', $.proxy(this.deleteRoi, this));
			
			this.$modal.modal('show');			
		},
		
		deleteRoi: function() {
			var $this = this;

			$.ajax({
				type: 'POST',
				url: '/php/ajax/dashboard/dashboard.post.php',
				data: {
					action: 'deleteroi',
					roi: this.options.roi_id
				},
				success	:	function() {
					$this.el.fadeOut(1000);
					$this.$modal.modal('hide');
				}
			});			
		}
	}

    $.fn.roi = function(params) {
        var inputs  = this,
            retval = this,
			args = arguments;

        inputs.each(function() {
			
            var plugin = $(this).data("roi-element");

            if (!plugin) {
                $(this).data("roi-element", new Roi(this, params));
                $(this).data("roi-element-id", params.el_id);
				$(this).attr('element-id',params.el_id);
            } else {
                if (typeof params === 'string' && typeof plugin[params] === 'function') {
                    retval = plugin[params]( Array.prototype.slice.call( args, 1 ) );
                }
            }
        });

        return retval || inputs;
    };

})(window.jQuery || window.Zepto, window, document);

;(function($, window, document, undefined) {

    var defaults = {};
	
    $.fn.rsModal = function(params) {
        var inputs  = this,
            retval = this,
			args = arguments;

        inputs.each(function() {
			
            var plugin = $(this).data("roi-element");

            if (!plugin) {
                $(this).data("roi-element", new Roi(this, params));
                $(this).data("roi-element-id", params.el_id);
				$(this).attr('element-id',params.el_id);
            } else {
                if (typeof params === 'string' && typeof plugin[params] === 'function') {
                    retval = plugin[params]( Array.prototype.slice.call( args, 1 ) );
                }
            }
        });

        return retval || inputs;
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
