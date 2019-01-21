@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => $page_title, 'trait_1' => $trait['trait_1'], 'trait_1_link' => $trait['trait_1_link'], 'trait_2' => $trait['trait_2']])
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
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
               @yield('form')
                <div class="form-body">
                    @include('admin.partials.form_error')

                    <div class="form-group">
                        {!! Form::labelControl('title',trans('admin/cmspage.title_label'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        </label>
                        <div class="col-md-4">

                            {!! Form::text('title',null,['class'=>'form-control']  )  !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('subject',trans('admin/cmspage.sub_title_label'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">

                            {!! Form::text('sub_title',null,['class'=>'form-control']  )  !!}
                        </div>
                    </div>


                    <div class="form-group">
                        {!! Form::labelControl('description',trans('admin/cmspage.content_label'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-9">

                            {!!Form::textarea('description',null,['class'=>'ckeditor form-control','rows'=>'6']  )!!}

                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::labelControl('meta_title',trans('admin/cmspage.meta_title_label'),['class'=>'control-label col-md-3'], False )  !!}
                        </label>
                        <div class="col-md-4">

                            {!! Form::text('meta_title',null,['class'=>'form-control']  )  !!}
                        </div>
                    </div>


                    <div class="form-group">
                        {!! Form::labelControl('meta_keywords',trans('admin/cmspage.meta_keyword_label'),['class'=>'control-label col-md-3'], False )  !!}
                        <div class="col-md-9">

                            {!!Form::textarea('meta_keywords',null,['class'=>' form-control','rows'=>'6']  )!!}

                        </div>
                    </div>


                    <div class="form-group">
                        {!! Form::labelControl('meta_description',trans('admin/cmspage.meta_description_label'),['class'=>'control-label col-md-3'], False )  !!}
                        <div class="col-md-9">

                            {!!Form::textarea('meta_description',null,['class'=>' form-control','rows'=>'6']  )!!}

                        </div>
                    </div>



                    <div class="form-group last">
                        {!! Form::labelControl('message',trans('admin/emailtemplate.status'),['class'=>'control-label col-md-3'], FALSE )  !!}
                        <div class="col-md-9">

                            {!! Form::select('status', array('1' => 'Active', '0' => 'In Active'), null, ['id'=>'status','class'=>'= form-control']) !!}

                        </div>
                    </div>


                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">

                            {!! Form::button('Submit', array('type' => 'submit', 'class' => 'btn green')) !!}
                            <a href="{{  action('CmsPagesController@index')   }}" class="btn default">Cancel</a>

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
<!-- BEGIN PAGE LEVEL PLUGINS -->
{!! HTML::script('assets/global/plugins/ckeditor/ckeditor.js') !!}
<!-- END PAGE LEVEL SCRIPTS -->
{!! JsValidator::formRequest($JsValidator, '#cmspagefrm'); !!}
@stop