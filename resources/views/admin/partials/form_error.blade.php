@if($errors->any())
<div class="alert alert-danger display-show">
    <!--<button class="close" data-close="alert"></button>-->
    @foreach($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
</div>
@endif
<div class="alert alert-danger display-hide">
    <button type="button" class="close" data-dismiss="alert">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    @if(isset($frmErrMsg))
    <li>{{ $frmErrMsg }}</li>
    @else
    <li>{{ trans('admin/admin.formvalidation_error') }}</li>
    @endif
</div>