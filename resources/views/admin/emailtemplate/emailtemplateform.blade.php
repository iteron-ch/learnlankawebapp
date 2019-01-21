@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => $page_heading, 'trait_1' => $trait['trait_1'], 'trait_1_link' => $trait['trait_1_link'], 'trait_2' => $trait['trait_2']])
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
                        {!! Form::labelControl('title',trans('admin/emailtemplate.title_label'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        </label>
                        <div class="col-md-4">

                            {!! Form::text('title',null,['class'=>'form-control']  )  !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('subject',trans('admin/emailtemplate.subject_label'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">

                            {!! Form::text('subject',null,['class'=>'form-control']  )  !!}
                        </div>
                    </div>


                    <div class="form-group">
                        {!! Form::labelControl('message',trans('admin/emailtemplate.message_label'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-9">

                            {!! Form::textarea('message',null,['class'=>'ckeditor form-control','rows'=>'6']  )!!}

                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('message',trans('admin/emailtemplate.status'),['class'=>'control-label col-md-3'], FALSE )  !!}
                        <div class="col-md-4">

                            {!! Form::select('status', $status, null, ['id'=>'status','class'=>'form-control select2me']) !!}

                        </div>
                    </div>

                    <!--<div class="form-group last">
                        {!! Form::labelControl('helper',trans('admin/emailtemplate.helper'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        </label>
                        <div class="col-md-4">

                            {!! Form::textarea('helper',null,['class'=>'form-control'] ) !!}
                        </div>
                    </div>-->





                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">

                            {!! Form::button('Submit', array('type' => 'submit', 'class' => 'btn green')) !!}
                            <a href="{{  action('EmailTemplatesController@index')   }}" class="btn default">Cancel</a>

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
{!! JsValidator::formRequest($JsValidator, '#emailtemplatefrm'); !!}   
<script>
    CKEDITOR.replace('message', {
        // Define the toolbar groups as it is a more accessible solution.
        toolbarGroups: [
            {name: 'clipboard', groups: ['clipboard', 'undo']},
            {name: 'editing', groups: ['find', 'selection', 'spellchecker']},
            {name: 'links'},
            {name: 'insert'},
            {name: 'document', groups: ['mode', 'document', 'doctools']},
            {name: 'others'},
            '/',
            {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
            {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi']},
            {name: 'styles'},
            {name: 'colors'},
            
        ],
        // Remove the redundant buttons from toolbar groups defined above.
        removeButtons: 'Image,Flash,Strike,Subscript,Superscript,Anchor,Styles,Save,NewPage,Print,Language,About,Iframe,CreateDiv, Blockquote'
    });

</script>>
@stop