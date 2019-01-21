@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/questionset.manage_questionset'), 'trait_1' => $trait['trait_1'], 'trait_1_link' => $trait['trait_1_link'], 'trait_2' => $trait['trait_2']])
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN VALIDATION STATES-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    {{ $page_title }}
                </div>
                <div class="actions">
                    <a href="{{ route('questionset.index') }}" class="btn btn-default btn-sm">
                        <i class="glyphicon glyphicon-chevron-left"></i> {!! trans('admin/admin.back') !!} </a>
                </div> 
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                @yield('form')
                <div class="form-body">
                    @include('admin.partials.form_error')
                    <div class="form-group">
                        {!! Form::labelControl('set_name',trans('admin/questionset.set_name'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::text('set_name',null,['class'=>'form-control']  )  !!}
                        </div>
                    </div> 
                    <div class="form-group">
                        {!! Form::labelControl('ks_id',trans('admin/questionset.key_stage'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('ks_id', $keyStage, null,['id' => 'ks_id', 'class' => 'form-control select2me']) !!} 
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('year_group',trans('admin/questionset.year_group'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">

                            {!! Form::select('year_group', array(), null,['id' => 'year_group', 'class' => 'form-control select2me']) !!} 

                        </div>
                    </div> 
                    <div class="form-group">
                        {!! Form::labelControl('subject',trans('admin/questionset.subject'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('subject', $setSubject, null,['id' => 'subject', 'class' => 'form-control select2me']) !!} 
                        </div>
                    </div>
                    <div class="form-group last">
                        {!! Form::labelControl('set_group',trans('admin/questionset.set_group'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::text('set_group','Test',['class'=>'form-control','readonly' => 'readonly']  )  !!}
                        </div>
                    </div>
                    @if(isset($questionset['id']))
                    <div class="form-group">
                        {!! Form::labelControl('status',trans('admin/admin.status'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-2">
                            {!! Form::select('status', $status, null,['id' => 'status', 'class' => 'form-control select2me']) !!} 
                        </div>
                    </div> 
                    @endif
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">

                            {!! Form::button('Submit', array('type' => 'submit', 'class' => 'btn green')) !!}
                            <a href="{{  route('questionset.index')   }}" class="btn default">Cancel</a>

                        </div>
                    </div>
                </div>
                <!--	</form>-->
                {!! Form::close() !!}
                <!-- END FORM-->
            </div>
            <!-- END VALIDATION STATES-->
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop

@section('pagescripts')
<!-- END PAGE LEVEL SCRIPTS -->
{!! JsValidator::formRequest($JsValidator, '#questionsetfrm'); !!}   
<script>
    jsMain = new Main("{{ trans('admin/admin.select_option') }}");
    var yearDD = <?php echo $yearKeysJson ?>;
    var selected = "{{ !empty(Input::old('year_group')) ? Input::old('year_group') : ( !empty($questionset['year_group']) ? $questionset['year_group'] : '') }}";
    $(window).load(function () {
        jsMain.makeDropDownJsonData(yearDD, $("#year_group"), $("#ks_id").val(), selected);
    });
    $(document).ready(function () {
        $("#ks_id").change(function () {
            jsMain.makeDropDownJsonData(yearDD, $("#year_group"), $(this).val(), '');
        });
    });
</script>
@stop
