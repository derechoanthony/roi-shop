;(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
    typeof define === 'function' && define.amd ? define(factory) :
    global.rsTable = factory();
}(this, (function () {

	var RoiShopTable = function(element, options) {
        this.options = options;
        this.el = $(element);
        this.el_ = this.el.clone();
        this.timeoutId_ = 0;
        this.timeoutFooter_ = 0;

        this.init();	
	};

	RoiShopTable.DEFAULTS = {
        customFilters: [],
        addRows: false,
        inlineEditing: false,
        pagination: true,
        pageList: [10, 25, 50, 100],
        paginationHAlign: 'right',
        paginationDetailHAlign: 'left',
        paginationPreText: '&lsaquo;',
        paginationNextText: '&rsaquo;',
        pageNumber: 1,
        pageSize: 10,
        buttonsClass: 'default',
        search: true,
        searchAlign: 'right',
        onSort: function(){

        }
	};

	RoiShopTable.LOCALES = {};
	
	RoiShopTable.LOCALES['en-US'] = RoiShopTable.LOCALES.en = {
		formatWelcomeMessage: function(dashboardUser) {
			return sprintf('Welcome %s', dashboardUser);
		},
		formatWelcomeSubtext: function(roiCount, activeCount) {
			return sprintf('You have %s current ROIs and %s active ROIs', roiCount, activeCount);
        },
        formatSearch: function () {
            return 'Search';
        },
        formatAllRows: function() {
            return 'All';
        },
        formatShowingRows: function (pageFrom, pageTo, totalRows) {
            return sprintf('Showing %s to %s of %s ROIs', pageFrom, pageTo, totalRows);
        },
        formatRecordsPerPage: function (pageNumber) {
            return sprintf('%s ROIs per page', pageNumber);
        },
    };

	$.extend(RoiShopTable.DEFAULTS, RoiShopTable.LOCALES['en-US']);
	
    RoiShopTable.EVENTS = {
        'all.rs.dashboard': 'onAll',
        'roi-created.rs.dashboard' : 'onRoiCreated'
    };

    RoiShopTable.prototype = {
        init: function(){
            var tbl = this;

            tbl.initLocale();
            tbl.initContainer();
            tbl.initTable();
            tbl.initToolbar();
            tbl.initEditing();
        },

        initLocale: function(){
            this.options = $.extend(true, {}, RoiShopTable.DEFAULTS, this.options);
        },

        initContainer: function(){
            var tbl = this;

            tbl.$container = $([
                '<div class="roi-table">',
                    '<div class="fixed-table-toolbar"></div>',
                    '<div class="fixed-table-container">',
                        '<div class="fixed-table-header"><table></table></div>',
                        '<div class="fixed-table-body"></div>',
                        '<div class="fixed-table-footer"><table><tr></tr></table></div>',
                        '<div class="fixed-table-pagination"></div>',
                    '</div>',
                '</div>'
            ].join(''));
    
            tbl.$tableContainer = tbl.$container.find('.fixed-table-container');
            tbl.$tableHeader = tbl.$container.find('.fixed-table-header');
            tbl.$tableBody = tbl.$container.find('.fixed-table-body');
            tbl.$tableFooter = tbl.$container.find('.fixed-table-footer');
            tbl.$toolbar = tbl.$container.find('.fixed-table-toolbar');
            tbl.$pagination = tbl.$container.find('.fixed-table-pagination');
    
            tbl.el.append(tbl.$container);
        },

        initTable: function(){
            var tbl = this;

            tbl.$roiTable = $([
                '<table class="table table-hover">',
                    '<thead></thead>',
                    '<tbody id="table-body"></tbody>',
                '</table>'
            ].join(''));

            tbl.$tableBody.append(tbl.$roiTable);
    
            tbl.initTableHeader();
            tbl.initData();
            tbl.initPagination();
            tbl.initBody();
        },

        initTableHeader: function(){
            var tbl = this,
                $headerRow = $('<tr/>'),
                $header;

            if(tbl.options.selector) $headerRow.append($('<th/>'));

            if(tbl.options.headers){
                $.each(tbl.options.headers, function(count, header){
                    $header = $(sprintf('<th><div class="th-inner%s%s">%s</div></th>', header.sortable ? ' sortable both' : '', header.column ? header.column.class ? ' ' + header.column.class : '' : '', header.title ? header.title : ''));
                    $headerRow.append($header);

                    if(header.width){
                        tbl.$roiTable.append(sprintf('<col width="%s"/>', header.width));
                    }
                });
            };

            tbl.$roiTable.find('thead').append($headerRow);

            tbl.$roiTable.find('.sortable').off('click').on('click', function (event) {
                tbl.onSort(event);
            });	
        },

        initData: function(){
            var tbl = this,
                series = tbl.options.series;

            tbl.data = [];

            $.each(series, function(i, s){
                $.each(this.data, function(count, data){
                    if(!tbl.data[count]) {
                        tbl.data[count] = {
                            order: count,
                            data: []
                        };
                    }
                    this.columnCount = i;
                    
                    tbl.data[count].data.push(this);
                });
            });

            tbl.sortedData = tbl.data;
        },

        initPagination: function(){
            if (!this.options.pagination) {
                this.$pagination.hide();
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

            this.options.totalRows = this.sortedData ? this.sortedData.length : 0;
   
            this.totalPages = 0;
            if (this.options.totalRows) {
                if (this.options.pageSize === this.options.formatAllRows()) {
                    this.options.pageSize = this.options.totalRows;
                    $allSelected = true;
                } else if (this.options.pageSize === this.options.totalRows) {
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
            if (!this.options.pagination) this.pageTo = this.options.totalRows;

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
        },

        initBody: function(){
            var tbl = this,
                $roiTableBody = tbl.$roiTable.find('#table-body');

            $roiTableBody.empty();

            if(tbl.sortedData){
                $.each(tbl.sortedData, function(row, datum){
                    if(row + 1 <= tbl.pageTo && row + 1 >= tbl.pageFrom){
                        $row = $('<tr/>');
                        $roiTableBody.append($row);
        
                        $.each(datum.data, function(count, cell){		
                            if(tbl.options.headers[count]){
                                if(tbl.options.headers[count].body) cell = $.extend({}, tbl.options.headers[count].body, cell);
                            };
                            tbl.buildCell($row, cell);
                        });
                    }
                });               
            }              
        },

        initToolbar: function(){
            var tbl = this,
                html = [],
                $filter = [];

            $btnGrp = $('<div class="columns btn-group"></div>');
            this.$toolbar.append($btnGrp);

            if(tbl.options.filters){
                $.each(tbl.options.filters, function(count, filter){
                    tbl.options.customFilters[count] = {
                            column: filter.column, 
                            attribute: filter.attribute, 
                            filter: null
                    };
    
                    $filter = $(sprintf('<select class="selectpicker" data-width="100px" title="%s"%s></select>', filter.title, (tbl.options.multipleSelect ? ' multiple' : '')));
                    $filter.attr('data-live-search', 'true');
                    $filter.attr('data-size', tbl.options.maxSearchSize);
                    $filter.attr('data-selected-text-format', sprintf('count > %s', tbl.options.maxSearchCount));
    
                    $.each(filter.options, function(i, option){
                        $filter.append($(sprintf('<option>%s</option>', this)));
                    });
    
                    $filter.on('changed.bs.select', function(e){
                        tbl.options.customFilters[count].filter = $(e.currentTarget).val();
                        tbl.updatePagination();
                    });
    
                    $btnGrp.append($filter);
    
                    $filter.selectpicker();
                });
            }

            if(tbl.options.addRows){
                $btnGroup = $('<div class="pull-' + tbl.options.searchAlign + ' bs-bars btn-group"></div>');
                $addRow = $('<a class="btn btn-success"> <i class="fa fa-plus"></i> Add</a>');
                    
                $addRow.off('click').on('click', $.proxy(tbl.addRow, this));

                tbl.$toolbar.append($btnGroup.append($addRow));
            }

            if (tbl.options.search) {
                html = [];
                html.push(
                    '<div class="pull-' + tbl.options.searchAlign + ' search">',
                        sprintf('<input class="form-control' + sprintf(' input-%s', tbl.options.iconSize) + '" type="text" placeholder="%s">', tbl.options.formatSearch()),
                    '</div>');

                tbl.$toolbar.append(html.join(''));
                
                var $search = tbl.$toolbar.find('.search input');
                $search.off('keyup drop blur').on('keyup drop blur', function (event) {
                    if (tbl.options.searchOnEnterKey && event.keyCode !== 13) {
                        return;
                    }

                    if ($.inArray(event.keyCode, [37, 38, 39, 40]) > -1) {
                        return;
                    }

                    tbl.onSearch(event);
                });
                
            }
        },

        addRow: function(){
            var tbl = this;
            //if(!tbl.options.)
        },

        buildCell: function($row, cell){
            var tbl = this, $cell = $('<td/>'), html;
            if(cell.class) $cell.addClass(cell.class);

            html = cell.value;
            if(cell.text){
               html = cell.text.replace('{{value}}', cell.value);
            }
            $cell.append(html).data('cell', cell);

            if(tbl.options.inlineEditing){
                if(cell.inlineEditing && cell.inlineEditing.enabled){
                    $cell.addClass('editable');
                }
            }
            
            if(cell.actions){
                $.each(cell.actions, function(){
                    switch(this.element){
                        case 'text': $trigger = $element; break;
                        case 'identifier': $trigger = $cell.find(this.identifier); break;
                        default: $trigger = $cell;
                    }

                    $trigger.off(this.trigger).on(this.trigger, this.action);
                });
            }

            if(cell.data){
                $.each(cell.data, function(attribute, id){
                    $cell.attr(attribute, id);
                });
            }

            $row.append($cell);
        },

        onSearch: function(event) {
            this.searchByTerm = $(event.currentTarget).val();
            
            this.updatePagination();
        },

        onSort: function(event){
            var index = $(event.currentTarget).closest('th').index();
            if (this.options.sortName === index) {
                this.options.sortOrder = this.options.sortOrder === 'asc' ? 'desc' : 'asc';
            } else {
                this.options.sortName = index;
                this.options.sortOrder = 'asc';
            }
            
            this.getCaret();
            this.updatePagination();
    
            this.options.onSort();
        },

        getCaret: function(){
            var that = this;

            $.each(this.$roiTable.find('th'), function (i, th) {
                $(th).find('.sortable').removeClass('desc asc').addClass(i === that.options.sortName ? that.options.sortOrder : 'both');
            });
        },

        initSort: function(){
            var tbl = this,
                order = this.options.sortOrder === 'desc' ? -1 : 1,
                filteredData = tbl.data,
                option;

            if(tbl.searchByTerm){
                filteredData = $.grep(filteredData, function(data){
                    include = false;
                    $.each(data.data, function(){
                        valueToSearch = this.value ? this.value : this.text;
                        if(valueToSearch.toLowerCase().indexOf(tbl.searchByTerm.toLowerCase()) > -1) include = true;
                    });

                    if(include) return true;
                });
            }

            $.each(tbl.options.customFilters, function(count, filter){
                if(filter.filter){
                    filteredData = $.grep(filteredData, function(data){
                        include = false;
                        $.each(filter.filter, function(){
                            if(data.data[filter.column][filter.attribute] == this) include = true;
                        });
                        if(include) return true;
                    });
                }
            });

            tbl.sortedData = filteredData;

            if (!this.options.sortName && this.options.sortName != 0){
                return;
            }

            var sortData = [];
            $.each(tbl.sortedData, function(count, datum){
                option = {
                    index: count,
                    value: datum.data[tbl.options.sortName].value ? datum.data[tbl.options.sortName].value : datum.data[tbl.options.sortName].text
                }
                sortData.push(option);
            });

            sortData.sort(function(a, b) {
                var aa = a.value ? a.value : a.text;
                var bb = b.value ? b.value : b.text;
    
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

            filteredData = [];
            $.each(sortData, function(index, data){
                filteredData.push(tbl.sortedData[data.index]);
            });

            tbl.sortedData = filteredData;
        },

        onPageFirst: function (event) {
            this.options.pageNumber = 1;
            this.updatePagination(event);
            return false;
        },

        onPagePre: function (event) {
            if ((this.options.pageNumber - 1) === 0) {
                this.options.pageNumber = this.options.totalPages;
            } else {
                this.options.pageNumber--;
            }
            this.updatePagination(event);
            return false;
        },

        onPageLast: function (event) {
            this.options.pageNumber = this.totalPages;
            this.updatePagination(event);
            return false;
        },

        onPageNumber: function (event) {console.log(event);
            if (this.options.pageNumber === +$(event.currentTarget).text()) {
                return;
            }
            this.options.pageNumber = +$(event.currentTarget).text();
            this.updatePagination(event);
            return false;
        },

        onPageNext: function (event) {
            if ((this.options.pageNumber + 1) > this.options.totalPages) {
                this.options.pageNumber = 1;
            } else {
                this.options.pageNumber++;
            }
            this.updatePagination(event);
            return false;
        },

        onPageListChange: function (event) {
            var $this = $(event.currentTarget);
    
            $this.parent().addClass('active').siblings().removeClass('active');
            this.options.pageSize = $this.text().toUpperCase() === this.options.formatAllRows().toUpperCase() ?
                this.options.formatAllRows() : +$this.text();
            this.$toolbar.find('.page-size').text(this.options.pageSize);
    
            this.updatePagination(event);
            return false;
        },

        updatePagination: function (event) {
            this.initSort();
            this.initPagination();
            this.initBody();
        },

		initEditing: function(){
            var tbl = this;
 
            var Mode = {
                edit: function(td){
                    $input = $('<input class="editing">').val($(td).text());
                    $(td).html($input);
                    $input.select();
                    $(td).addClass('editing').removeClass('editable');
                },
                view: function(td){
                    $(td).each(function() {
                        var value = $(this).find('input').val(),
                            cell = $(this).data('cell');

                        value = cell.text ? cell.text.replace('{{value}}', value) : value;
                        
                        cell.value = value;
                        
                        if(cell.data['calc-id']){
                            id = cell.data['calc-id'];
                            $('#wrapper').roishopCalculator('setValue', id, value);
                        } else {
                            $(this).html(value);
                        }

                        $(this).addClass('editable').removeClass('editing');
                    });
                }
            }

			tbl.el.on('click', 'td.editable', function(event){
				if (event.handled !== true){
					event.preventDefault();
                    
                    var editors = tbl.el.find('td.editing');
					Mode.view(editors);
					Mode.edit(this);
					
					event.handled = true;
				}
            });
                       
            var documentFunction = function(event){
                if(!tbl.el.find('table > tbody').is(event.target) && tbl.el.find('table > tbody').has(event.target).length === 0 || !$(event.target).hasClass('editing') || $(event.target).hasClass('editable')){
                    var editors = tbl.el.find('td.editing');
                    if(editors.length) Mode.view(editors);
                }
            }

            $(document).on('click', documentFunction);
		}
    }
	
	$.fn.rsTable = function(params) {
        var elements  = this,
            retval = this,
			args = arguments;

        elements.each(function() {
			
            var plugin = $(this).data("rs-admin-table");

            if (!plugin) {
                $(this).data("rs-admin-table", new RoiShopTable(this, params));
            } else {
                if (typeof params === 'string' && typeof plugin[params] === 'function') {
                    retval = plugin[params]( Array.prototype.slice.call( args, 1 ) );
                }
            }
        });

        return retval || inputs;
    };

})));