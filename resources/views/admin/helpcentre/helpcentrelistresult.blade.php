<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="dataTables_length" id="testset-table_length">
            <label> <strong>Results</strong> @if($resultCount) [ {{ $resultCount }} results found ] @endif</label>
        </div>
    </div>
</div>
@if(!$resultCount)
<div class="table-scrollable">
     <table class="table table-striped table-bordered table-hover dataTable no-footer"><tbody><tr class="odd"><td valign="top" colspan="9" class="dataTables_empty">No matching records found</td></tr></tbody></table>
</div>
@endif
    @if(count($helpResultSystem))
    @foreach($helpResultSystem as $catRow)
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa"></i>{{ $catRow['catname'] }}
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                @foreach($catRow['data'] as $catRowfiledataFile)
                @include('admin.helpcentre.html-helpdata', (array) $catRowfiledataFile)
                @endforeach
            </div>
        </div>
    </div>
    @endforeach
    @endif

    @if(count($helpResultMath))
    @foreach($helpResultMath as $catRow)
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa"></i>{{ 'Math &gt; '.$catRow['catname'] }}
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                @foreach($catRow['data'] as $catRowfiledataFile)
                @include('admin.helpcentre.html-helpdata', (array) $catRowfiledataFile)
                @endforeach
            </div>
        </div>
    </div>
    @endforeach
    @endif


    @if(count($helpResultEnglsih))
    @foreach($helpResultEnglsih as $catRow)
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa"></i>{{ 'English &gt; '.$catRow['catname'] }}
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                @foreach($catRow['data'] as $catRowfiledataFile)
                @include('admin.helpcentre.html-helpdata', (array) $catRowfiledataFile)
                @endforeach
            </div>
        </div>
    </div>
    @endforeach
    @endif
    <script>
        jsMain = new Main();
        $('.view_row').on('click', function(e) {
            e.preventDefault();
            var eleObj = $(this);
            jsMain.showModelIframe(eleObj);
        });
    </script>
    <style>
        .portlet.light .portlet-body.help_result{padding:0px;}
        .help_result .row > div{padding:0 2% !important}
        .help_result .row > div.col-md-1{width: 10%}
        .help_result .row > div.col-md-1 i{margin-top: 10px; font-size: 32px;}
        .help_result .row > div.col-md-9{width: 80%}
        .help_result .row > div.col-md-1.pull-left {margin-top: 3px}
        .help_result .row > div.col-md-1.pull-left i{ font-size: 23px;}
        .help_result .row > div.col-md-9 p{margin-bottom:3px; line-height: 14px;}
    </style>


