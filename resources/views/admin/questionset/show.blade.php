@extends('admin.layout._iframe')

@section('content')

<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title tabbable-line">
                <div class="caption caption-md">
                    <i class="icon-globe theme-font hide"></i>
                    <span class="caption-subject font-blue-madison bold">{{ trans('admin/questionset.testsetdetails') }}</span>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-light table-hover">
                    <tr>
                        <td>
                             <strong> {{ trans('admin/questionset.set_name') }} </strong>
                        </td>
                        <td>
                            {{ $questionset->set_name }}
                        </td>
                    </tr>
                    
                    <tr>
                        <td width=30%>
                              <strong>{{ trans('admin/questionset.key_stage') }} </strong>
                        </td>
                        <td>
                            {{ $questionset->ks_id }}
                        </td>
                    </tr>
                     <tr>
                        <td>
                             <strong> {{ trans('admin/questionset.year_group') }} </strong>
                        </td>
                        <td>
                            {{ $questionset->year_group }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                             <strong> {{ trans('admin/questionset.subject') }} </strong>
                        </td>
                        <td>
                            {{ $questionset->subject }}
                        </td>
                    </tr>
                   
                    <tr>
                        <td>
                             <strong> {{ trans('admin/questionset.set_group') }} </strong>
                        </td>
                        <td>
                            {{ $questionset->set_group }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                             <strong> {{ trans('admin/questionset.set_status') }} </strong>
                        </td>
                        <td>
                            {{ $questionset->status }}
                        </td>
                    </tr>                    
                </table>
                <!--end profile-settings-->
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop