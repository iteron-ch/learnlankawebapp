@extends('admin.layout._iframe')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title tabbable-line">
                <div class="caption caption-md">
                    <i class="icon-globe theme-font hide"></i>
                    <span class="caption-subject font-grey-madison bold">{{ trans('admin/helpcentre.helpcentre_detail') }}</span>
                </div>
            </div>

            <div class="portlet-body">
                <table class="table table-light table-hover">
                    <tr>
                        <td width=30%> 
                            <strong> {{ trans('admin/helpcentre.title') }}</strong>
                        </td>   
                        <td>
                            {{ $helpcentre['title'] }}
                        </td>
                    </tr> 
                    <tr>
                        <td width=30%> 
                            <strong> {{ trans('admin/helpcentre.strand') }}</strong>
                        </td>   
                        <td>
                            {{ $helpcentre['all_strand'] }}
                        </td>
                    </tr> 
                    <tr>
                        <td width=30%> 
                            <strong> {{ trans('admin/helpcentre.substrand') }}</strong>
                        </td>   
                        <td>
                            {{ $helpcentre['all_sub_strand'] }}
                        </td>
                    </tr> 
                    <tr>
                        <td width=30%> 
                            <strong> {{ trans('admin/helpcentre.visible_to') }}</strong>
                        </td>   
                        <td>
                            {{ $helpcentre['userType'] }}
                        </td>
                    </tr> 
                    <tr>
                        <td width=30%> 
                            <strong> {{ trans('admin/helpcentre.created_at') }}</strong>
                        </td>   
                        <td>
                            {{ outputDateFormat($helpcentre['created_at']) }}

                        </td>
                    </tr> 
                    <tr>
                        <td width=30%> 
                            <strong> {{ trans('admin/helpcentre.status') }}</strong>
                        </td>   
                        <td>
                            {{ $helpcentre['status'] }}
                        </td>
                    </tr> 
                    <tr>
                        <td width=30%> 
                            <strong> {{ trans('admin/helpcentre.currentFiles') }}</strong>
                        </td>   
                        <td>
                            @foreach($helpcentre['helpcentreFiles'] as $keys=>$values)
                            <label>
                                @if($values['media_type'] == 1)
                                <a target="_blank" href="{{ route('helpcentre.helpcentreview',$values['id']) }}"><i class="{{ $mediaTypeIcon[1] }}" style="font-size:20px; color: red;"></i></a>
                                @elseif($values['media_type'] == 2)
                                <a target="_blank" href="{{ route('helpcentre.helpcentreview',$values['id']) }}"><i class="{{ $mediaTypeIcon[2] }}" style="font-size:20px;"></i></a>
                                @elseif($values['media_type'] == 3)
                                @if(getFileExtensionFromFilename($values['file_name']) == 'txt')
                                <a target="_blank" href="{{ '/uploads/helpdocuments/'.$values['file_name'] }}"><i class="{{ $mediaTypeIcon[6] }}" style="font-size:20px; color: red;"></i></a>
                                @elseif(getFileExtensionFromFilename($values['file_name']) == 'pdf')
                                <a target="_blank" href="{{ '/uploads/helpdocuments/'.$values['file_name'] }}"><i class="{{ $mediaTypeIcon[7] }}" style="font-size:20px; color: red;"></i></a>
                                @elseif(getFileExtensionFromFilename($values['file_name']) == 'ppt')
                                <a target="_blank" href="{{ '/uploads/helpdocuments/'.$values['file_name'] }}"><i class="{{ $mediaTypeIcon[8] }}" style="font-size:20px; color: red;"></i></a>
                                @elseif(getFileExtensionFromFilename($values['file_name']) == 'doc' || getFileExtensionFromFilename($values['file_name']) == 'docx')
                                <a target="_blank" href="{{ '/uploads/helpdocuments/'.$values['file_name'] }}"><i class="{{ $mediaTypeIcon[9] }}" style="font-size:20px; color: red;"></i></a>
                                @else
                                <a target="_blank" href="{{ '/uploads/helpdocuments/'.$values['file_name'] }}"><i class="{{ $mediaTypeIcon[3] }}" style="font-size:20px; color: red;"></i></a>
                                @endif
                                @elseif($values['media_type'] == 4)
                                <a target="_blank" href="{{ '/uploads/helpdocuments/'.$values['file_name'] }}"><i class="{{ $mediaTypeIcon[4] }}" style="font-size:20px;"></i></a>
                                @elseif($values['media_type'] == 5)
                                <a target="_blank" href="{{ $values['media_link'] }}"><i class="{{ $mediaTypeIcon[5] }}" style="font-size:20px;"></i></a>
                                <br>
                                @endif
                            </label>
                            @endforeach 
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
@section('pagescripts')
<script>
    jsMain = new Main();
    $(document).ready(function() {
        $('.view_row').on('click', function(e) {
            e.preventDefault();
            var eleObj = $(this);
            jsMain.showModelIframe(eleObj);
        });
    });

</script>
@stop