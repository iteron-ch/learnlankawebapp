<!-- BEGIN FORM-->
{!! Form::open(['route' => ['user.updatepassword'], 'method' => 'post', 'files' => true, 'class' => 'form-horizontal','id' =>'changePassfrm']) !!}
<div class="form-body">
    @include('admin.partials.form_error')
    <div class="form-group">
        {!! Form::labelControl('old_password',trans('admin/admin.old_password'),['class'=>'control-label col-md-3'], isset($user['id'])?FALSE:TRUE )  !!}
        <div class="col-md-4">
            {!! Form::password('old_password',['class'=>'form-control']  )  !!}
        </div>
    </div>                
    <div class="form-group">
        {!! Form::labelControl('password',trans('admin/admin.new_password'),['class'=>'control-label col-md-3'], isset($user['id'])?FALSE:TRUE )  !!}
        <div class="col-md-4">
            {!! Form::password('password',['class'=>'form-control']  )  !!}
        </div>
    </div>                      
    <div class="form-group">
        {!! Form::labelControl('confirm_password',trans('admin/admin.confirm_password'),['class'=>'control-label col-md-3'], isset($user['id'])?FALSE:TRUE )  !!}
        <div class="col-md-4">
            {!! Form::password('confirm_password',['class'=>'form-control']  )  !!}
        </div>
    </div>   
    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">
                {!! Form::button('Submit', array('type' => 'submit', 'class' => 'btn green')) !!}
                {!! HTML::link(route('dashboard'), trans('admin/admin.cancel'), array('class' => 'btn default')) !!}
            </div>
        </div>
    </div>
    <!--	</form>-->
    {!! Form::close() !!}
    <!-- END FORM-->
</div>