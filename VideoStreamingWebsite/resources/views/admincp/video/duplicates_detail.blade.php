@extends('admincp.master')
@section('title',"Duplicaties Existing Videos")
@section ('subtitle',"Video Duplicaties")
@section('content')
<link rel="stylesheet" type="text/css" href="{{URL::asset('public/assets/css/bootstrap-table.css')}}">
<script src="{{URL::asset('public/assets/js/bootstrap-table.js')}}"></script>
		<article class="module width_full">
			<div class="add"><a href="{{URL('admincp/duplicates-video')}}"><i class="fa fa-plus-circle"></i> Back </a></div>
            <div class="add" ><a style="margin-right:10px" id="remove" class="remove 2btnDeleteListDuplicates" href="javascript:void(0)"><i class="glyphicon glyphicon-remove"></i> Delete </a></div>

		<header><h3 class="tabs_involved">Existing Video Duplicaties </h3></header>

			 <table id="table" cellspacing="0" cellpadding="0" class="table"
           
           data-search="false"
           data-show-refresh="false"
           data-show-toggle="false"
           data-show-columns="false"
           data-show-export="false"
           data-detail-view="false"
           data-detail-formatter="detailFormatter"
           data-minimum-count-columns="2"
           data-show-pagination-switch="false"
           data-pagination="true"
           data-id-field="string_Id"
           data-page-list="[10, 25, 50, 100]"
           data-show-footer="false"
           data-side-pagination="server"
           data-url="{{URL('admincp/duplicates-list-video-detail/'.$id)}}"
           data-response-handler="responseHandler"
           data-sort-order="desc"
           data-sort-name="created_at"
           >
    </table>

		</article><!-- end of content manager article -->
<div class="spacer"></div>

<script type="text/javascript">

    var $table = $('#table'),
        $remove = $('#remove'),
        selections = [];

    function initTable() {
        $table.bootstrapTable({
            height: getHeight(),
            columns: [
                    {
                        field: 'ID',
                        checkbox: true,
                        align: 'center',
                        valign: 'middle'

                    },
                    {
                        field: 'title_name',
                        title: 'Video Title',
                        sortable: true,
                        editable: true,
                        align: 'left',
                        width:"100"
                    },{
                        field: 'created_at',
                        title: 'Date created',
                        sortable: true,
                        align: 'center',
                        width:"50"
                    },{
                        field: 'operate',
                        title: 'Action',
                        align: 'center',
                        width:"50",
                        events: operateEvents,
                        formatter: operateFormatter
                    }
                ]
        });
        // sometimes footer render error.
        setTimeout(function () {
            $table.bootstrapTable('resetView');
        }, 200);
        $table.on('check.bs.table uncheck.bs.table ' +
                'check-all.bs.table uncheck-all.bs.table', function () {
            $remove.prop('disabled', !$table.bootstrapTable('getSelections').length);

            // save your data, here just save the current page
            selections = getIdSelections();
            // push or splice the selections if you want to save all data selections
        });
        $table.on('expand-row.bs.table', function (e, index, row, $detail) {
            if (index % 2 == 1) {
                $detail.html('Loading from ajax request...');
                $.get('LICENSE', function (res) {
                    $detail.html(res.replace(/\n/g, '<br>'));
                });
            }
        });
        $table.on('all.bs.table', function (e, name, args) {
           //console.log(name, args);
        });
        //delete all row selected
        $remove.click(function () {
            var ids = getIdSelections();
            if(ids.length>0){
               window.location.href='{{URL("admincp/delete-duplicates-video")}}/'+ids;
            }else{
                alert('Please selected rows');
            }
            
        });
        $(window).resize(function () {
            $table.bootstrapTable('resetView', {
                height: getHeight()
            });
        });
    }
    function statusStyle(data){
      if(data==1){
        var html='<span class="label label-success">Active</span>';
      }else{
        var html='<span class="label label-danger">Block</span>';
      }
      return html;
    }
    function featuredStyle(data){
      if(data==1){
        var html='<span class="label label-info">Featured</span>';
      }else{
        var html='-';
      }
      return html;
    }
    function categoryStyle(data){
      if(data!==null){
        var html =  '';
        $.get(base_url+'/admincp/video-cat-list-name/'+data).done(function(res){
            html = res; 
        });
        return html;
      }
    }

    function getIdSelections() {
        return $.map($table.bootstrapTable('getSelections'), function (row) {
            return row.string_Id
        });
    }

    function responseHandler(res) {
        $.each(res.rows, function (i, row) {
            row.state = $.inArray(row.ID, selections) !== -1;
        });
        return res;
    }

    function detailFormatter(index, row) {
        var html = [];
        $.each(row, function (key, value) {
            html.push('<p><b>' + key + ':</b> ' + value + '</p>');
        });
        return html.join('');
    }

    function operateFormatter(value, row, index) {
        return [
            '<a data-toggle="tooltip" data-placement="top"  style="font-size:24px" class="remove"  href="{{URL("admincp/delete-duplicates-video")}}/'+row.string_Id+'" title="Remove Duplicaties Video">',
            '<i class="fa fa-trash-o"></i>',
            '</a>'
        ].join('');
    }
    window.operateEvents = {
        'click .like': function (e, value, row, index) {
            alert('You click like action, row: ' + JSON.stringify(row));
        },
        'click .remove': function (e, value, row, index) {
            $table.bootstrapTable('remove', {
                field: 'id',
                values: [row.ID]
            });
        }
    };

    function totalTextFormatter(data) {
        return 'Total';
    }

    function totalNameFormatter(data) {
        return data.length;
    }

    function totalPriceFormatter(data) {
        var total = 0;
        $.each(data, function (i, row) {
            total += +(row.price.substring(1));
        });
        return '$' + total;
    }

    function getHeight() {
        return $(window).height() - $('h1').outerHeight(true);
    }

    $(function () {
        var scripts = [
                // location.search.substring(1) || 'assets/bootstrap-table/src/bootstrap-table.js',
                // 'assets/bootstrap-table/src/extensions/export/bootstrap-table-export.js',
                // 'http://rawgit.com/hhurz/tableExport.jquery.plugin/master/tableExport.js',
                // 'assets/bootstrap-table/src/extensions/editable/bootstrap-table-editable.js',
                // 'http://rawgit.com/vitalets/x-editable/master/dist/bootstrap3-editable/js/bootstrap-editable.js'
            ],
            eachSeries = function (arr, iterator, callback) {
                callback = callback || function () {};
                if (!arr.length) {
                    return callback();
                }
                var completed = 0;
                var iterate = function () {
                    iterator(arr[completed], function (err) {
                        if (err) {
                            callback(err);
                            callback = function () {};
                        }
                        else {
                            completed += 1;
                            if (completed >= arr.length) {
                                callback(null);
                            }
                            else {
                                iterate();
                            }
                        }
                    });
                };
                iterate();
            };

        eachSeries(scripts, getScript, initTable);
    });

    function getScript(url, callback) {
        var head = document.getElementsByTagName('head')[0];
        var script = document.createElement('script');
        script.src = url;

        var done = false;
        // Attach handlers for all browsers
        script.onload = script.onreadystatechange = function() {
            if (!done && (!this.readyState ||
                    this.readyState == 'loaded' || this.readyState == 'complete')) {
                done = true;
                if (callback)
                    callback();

                // Handle memory leak in IE
                script.onload = script.onreadystatechange = null;
            }
        };

        head.appendChild(script);

        // We handle everything using the script element injection
        return undefined;
    }
    
</script>

@endsection