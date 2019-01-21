@extends('admin.layout._iframe')

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa"></i>Assigned Students
                </div>
            </div>
            <div class="portlet-body"> 
                  
                    <table id="testset-table" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr class="heading">
                                <th>{!! trans('admin/admin.first_name') !!}</th>
                                <th>{!! trans('admin/admin.last_name') !!}</th>
                                <th>{!! trans('admin/admin.completed') !!}</th>

                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
        <!-- END PAGE CONTENT-->
    </div>

    <!-- END EXAMPLE TABLE PORTLET-->
</div>
@stop

@section('pagescripts')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script>
    jsMain = new Main("{{ trans('admin/admin.select_option') }}");
    var vars = {
        dataTable: "#testset-table",
        listUrl: "{{ route('managetask.assignedstudentlist',[$assignedId,$classGroupId]) }}",
        confirmMsg: "{!! trans('admin/admin.delete-confirm') !!}",
        successMsg: "{!! trans('admin/admin.delete-success') !!}"
    };
    console.log(vars);
</script>
{!! HTML::style('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') !!}
{!! HTML::script('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') !!}
{!! HTML::script('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') !!}
{!! HTML::script('js/taskassigned.js') !!}
@stop