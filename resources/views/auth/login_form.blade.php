<div class="login_box ">
    <div class="content align_center">
        <a href="/" class="logo">&nbsp;</a>
        {!! Form::open(['url' => 'auth/login', 'method' => 'post', 'id' => 'frmLogin']) !!}
        <ul>
            <li><strong>{{ trans('auth/login.login_title') }}</strong></li>
            <li>{!! Form::text('log', $value = null , $attributes = ['class' => 'input_text', 'placeholder' => trans('auth/login.username')]) !!}</li>
            <li>{!! Form::password('password', array('class' => 'input_text password','autocomplete' => 'off', 'placeholder' => trans('auth/login.password'))) !!}</li>
            <li>{!! Form::button(trans('auth/login.login'), array('type' => 'submit', 'class' => 'button')) !!}</li>
        </ul>
        <div class="form-actions">
            <label class="rememberme check">
                <input type="checkbox" name="memory" value="1"/>{{ trans('admin/auth/login.remind') }} 
            </label>
            <br><br>
            {!! HTML::link(route('student.register'),'Need account?' , array('id' => 'forget-password','class' => 'forget-password')) !!} <br><br>
            {!! HTML::link(route('forgotpassword'),trans('admin/auth/login.forget_password') , array('id' => 'forget-password','class' => 'forget-password')) !!}
            {!! HTML::link(route('forgotusername'),trans('admin/auth/login.forgot_username') , array('id' => 'forget-password','class' => 'forget-password')) !!}
        </div>  
        {!! Form::close() !!}

        <div style=" border-top: 2px dotted #FFA500;  color: #999; font-size:13px;  margin:18px -35px; text-align:center;" class="form-actions">
            <span style="display: block; color: #ff0000; font-size:35px; margin-bottom: 10px; margin-top: 10px;"><em><img src="../images/beta.png"></em></span>
            <span style="display: block; margin: 0px 0px 11px;">Please note: this is a beta version of Sats Companion. Every effort has been made to ensure the system is fully functional to help you prepare for the 2016 KS2 SATs.</span>
            However, as this is the first version, there maybe some slight technical issues. <br>
            If you do come across any, please get in touch with our team at <span style="color: rgb(90, 194, 239); font-weight: bold; text-decoration: underline;">info@iteron.ch</span> and we will resolve them promptly. 

        </div>
    </div>
    <div class="content second"> </div>
</div>

