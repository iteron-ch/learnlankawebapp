<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            <li class="sidebar-toggler-wrapper">
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                <div class="sidebar-toggler">
                </div>
                <!-- END SIDEBAR TOGGLER BUTTON -->
            </li>
            <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->

            <li {!! Request::segment(1) == ('dashboard') ? 'class="start active"' : 'class="start"' !!} >
                <a href="{!! url('dashboard') !!}">
                    <i class="icon-home"></i>
                    <span class="title">{{ trans('admin/admin.dashboard') }}</span>
                    @if(Request::segment(1) == ('dashboard'))
                    <span class="selected"></span>
                    @endif
                </a>

            </li>
            <!-- BEGIN ADMIN USER TYPE MENU -->
            @if(session()->get('user')['user_type'] == ADMIN)
            <li {!! (Request::segment(1) == 'school' || Request::segment(1) == 'teacher' || Request::segment(1) == 'tutor' || Request::segment(1) == 'student') ? 'class="active open"' : '' !!}>
                <a href="javascript:;">
                    <i class="icon-users"></i>
                    <span class="title">{{ trans('admin/admin.users') }}</span>
                    @if(Request::segment(1) == ('school') || Request::segment(1) == ('teacher') || Request::segment(1) == ('tutor') || Request::segment(1) == ('student'))
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                    @else
                    <span class="arrow"></span>
                    @endif
                </a>
                <ul class="sub-menu">
                    <li {!! (Request::segment(1) == 'school' || Request::segment(1) == 'teacher' ) ? 'class="active"' : '' !!}>
                        <a href="{{ route('school.index') }}">
                            <i class="icon-user"></i>
                            {{ trans('admin/admin.manage_schools') }}</a>
                    </li>
                    <li {!! Request::segment(1) == 'tutor' ? 'class="active"' : '' !!}>
                        <a href="{{ route('tutor.index') }}">
                            <i class="icon-user"></i>
                            {{ trans('admin/admin.manage_tutorsparents') }}</a>
                    </li>
                </ul>
            </li> 

            <li {!! Request::segment(1) == 'messages' || Request::segment(1) == 'emailtemplate' || Request::segment(1) == 'notification' ? 'class="active open"' : '' !!}>
                <a href="javascript:;">
                    <i class="icon-envelope"></i>
                    <span class="title">{{ trans('admin/admin.message_center') }}</span><span>&nbsp;@include('admin.messages.unread-count')</span>
                    @if(Request::segment(1) == 'messages' || Request::segment(1) == 'emailtemplate' || Request::segment(1) == 'notification')
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                    @else
                    <span class="arrow"></span>
                    @endif
                </a>
                <ul class="sub-menu">
                    <li {!! Request::segment(1) == 'messages' && Request::segment(2) == 'create' ? 'class="active"' : '' !!}>
                        <a href="{{ route('messages.create') }}">
                            <i class="icon-notebook"></i>
                            {{ trans('admin/admin.compose_message') }}</a>
                    </li>
                    <li {!! Request::segment(1) == 'messages' && Request::segment(2) == 'inbox' ? 'class="active"' : '' !!}>
                        <a href="{{ route('messages.inbox') }}">
                            <i class="icon-envelope"></i>{{ trans('admin/admin.inbox') }}</a>
                    </li>
                    <li {!! Request::segment(1) == 'messages' && Request::segment(2) == 'sent' ? 'class="active"' : '' !!}>
                        <a href="{{ route('messages.sent') }}">
                            <i class="icon-envelope"></i>
                            {{ trans('admin/admin.sent_messages') }}</a>
                    </li>
                    <li {!! Request::segment(1) == 'emailtemplate' ? 'class="active"' : '' !!}>
                        <a href={{  route('emailtemplate.index') }}>
                       <i class="icon-settings"></i>
                            {{ trans('admin/admin.email_manager') }}</a>
                    </li>
                    <li {!! Request::segment(1) == 'notification' ? 'class="active"' : '' !!}>
                        <a href="{{ route('notification.index') }}">
                            <i class="icon-user"></i>
                            <span class="title">{{ trans('admin/admin.notification_center') }}</span>
                        </a>
                    </li>
                </ul>
            </li> 



            <li {!! Request::segment(1) == 'studentaward' || Request::segment(1) == 'rewards'  ? 'class="active"' : '' !!}>
                <a href="javascript:;">
                    <i class="glyphicon glyphicon-asterisk"></i>
                    <span class="title">{{ trans('admin/admin.student_award_center') }}</span>
                    @if(Request::segment(1) == 'rewards')
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                    @else
                    <span class="arrow"></span>
                    @endif
                </a>
                <ul class="sub-menu">
                    <li {!! Request::segment(1) == 'studentaward' ? 'class="active"' : '' !!}>
                        <a href="{{ route('studentaward.index') }}">

                            <i class="glyphicon glyphicon-certificate"></i>
                            <span class="title">{{ trans('admin/admin.student_award') }}</span>
                        </a>
                    </li> 
                    <li {!! Request::segment(1) == 'rewards' && Request::segment(2) == 'test' ? 'class="active"' : '' !!}>
                        <a href="{{ route('rewards.index','test') }}">
                            <i class="icon-notebook"></i>
                            {{ trans('admin/rewards.test_rewards') }}</a>
                    </li>
                    <li {!! Request::segment(1) == 'rewards' && Request::segment(2) == 'revision' ? 'class="active"' : '' !!}>
                        <a href="{{ route('rewards.index','revision') }}">
                            <i class="icon-notebook"></i>
                            {{ trans('admin/rewards.revision_rewards') }}</a>
                    </li>
                </ul>
            </li>  
            <li {!! (Request::segment(1) == 'voucher' || Request::segment(1) =='fees' || Request::segment(1) =='payment' || Request::segment(1) =='invoice') ? 'class="active open"' : '' !!}>
                <a href="javascript:;">
                    <i class="icon-settings"></i>
                    <span class="title">{{ trans('admin/admin.payment_center') }}</span>
                    @if(Request::segment(1) == ('message'))
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                    @else
                    <span class="arrow"></span>
                    @endif
                </a>
                <ul class="sub-menu">
                    <li {!! (Request::segment(1) == 'voucher') ? 'class="active"' : '' !!}>
                        <a href="{{  route('voucher.index') }}">
                            <i class="icon-notebook"></i>
                            Manage Vouchers</a>
                    </li>
                    <li {!! Request::segment(1) == 'payment' ? 'class="active"' : '' !!}>
                        <a href="{{ route('payment.index') }}">
                            <i class="icon-user"></i>
                            <span class="title">Pending Payments</span>
                        </a>
                    </li>
                    <li {!! Request::segment(1) == 'fees' ? 'class="active"' : '' !!}>
                        <a href="{{ route('fees.index') }}">
                            <i class="icon-credit-card"></i>
                            <span class="title">{{ trans('admin/admin.manage_payment') }}</span>
                        </a>
                    </li>
                    <li {!! Request::segment(1) == 'invoice' ? 'class="active"' : '' !!}>
                        <a href="{{ route('invoice.index') }}">
                            <i class="glyphicon glyphicon-list"></i>
                            <span class="title">{{ trans('admin/admin.invoice') }}</span>
                        </a>
                    </li>
                </ul>
            </li> 
            <li {!! (Request::segment(1) == 'questionbuilder' || Request::segment(1) == 'questionset' || Request::segment(1) == 'strand') ? 'class="active open"' : '' !!}>
                <a href="javascript:;">
                    <i class="icon-book-open"></i>
                    <span class="title">{{ trans('admin/admin.question_builder') }}</span>
                    @if(Request::segment(1) == ('questionbuilder') || Request::segment(1) == ('questionset') || Request::segment(1) == ('strand'))
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                    @else
                    <span class="arrow"></span>
                    @endif
                </a>
                <ul class="sub-menu">
                    <li {!! Request::segment(1) == 'questionbuilder' ? 'class="active"' : '' !!}>
                        <a href="{{ route('questionbuilder.index') }}">
                            {{ trans('admin/admin.manage_question') }}</a>
                    </li>
                    <li {!! Request::segment(1) == 'questionset' ? 'class="active"' : '' !!}>
                        <a href="{{ route('questionset.index') }}">
                            {{ trans('admin/admin.manage_question_set') }}</a>
                    </li>
                    <li {!! Request::segment(1) == 'strand'  ? 'class="active"' : '' !!}>
                        <a href="{{ route('strand.index') }}">
                            {{ trans('admin/admin.manage_strands') }}</a>
                    </li>


                </ul>
            </li>  
            <li {!! Request::segment(1) == 'enquiry' ? 'class="active"' : '' !!}>
                <a href="{{ route('enquiry.index') }}">
                    <i class="fa fa-newspaper-o"></i>
                    <span class="title">{{ trans('admin/admin.demo_requests') }}</span>
                    @if(Request::segment(1) == 'enquiry')
                    <span class="selected"></span>
                    @endif
                </a>
            </li>  

            <!--<li {!! Request::segment(1) == 'report' ? 'class="active"' : '' !!}>
                <a href="{!! url('comingsoon') !!}">
                    <i class="icon-user"></i>
                    <span class="title">{{ trans('admin/admin.reporting_center') }}</span>
                </a>
            </li>-->  
            <li {!! Request::segment(1) == 'helpcentre' ? 'class="active"' : '' !!}>
                <a href="{{ route('helpcentre.index') }}">
                    <i class="icon-question"></i>
                    <span class="title">{{ trans('admin/admin.help_center') }}</span>
                    @if(Request::segment(1) == 'helpcentre')
                    <span class="selected"></span>
                    @endif
                </a>
            </li>    

            <li {!! Request::segment(1) == 'myprofile' ? 'class="active"' : '' !!}>
                <a href="{{ route('myprofile') }}">
                    <i class="fa fa-cogs"></i>
                    <span class="title">{{ trans('admin/admin.manage_account') }}</span>
                    @if(Request::segment(1) == 'myprofile')
                    <span class="selected"></span>
                    @endif
                </a>
            </li> 
            <li {!! Request::segment(1) == 'questionadmin' ? 'class="active"' : '' !!}>
                <a href="{{ route('questionadmin.index') }}">
                    <i class="icon-user"></i>
                    <span class="title">{{ trans('admin/admin.manage_question_builder_account') }}</span>
                    @if(Request::segment(1) == 'questionadmin')
                    <span class="selected"></span>
                    @endif
                </a>
            </li>

            <li {!! Request::segment(1) == 'questionvalidator' ? 'class="active"' : '' !!}>
                <a href="{{ route('questionvalidator.index') }}">
                    <i class="fa fa-users"></i>
                    <span class="title">{{ trans('admin/admin.manage_question_validator_account') }}</span>
                    @if(Request::segment(1) == 'questionvalidator')
                    <span class="selected"></span>
                    @endif
                </a>
            </li>
            <li {!! (Request::segment(1) == 'report') ? 'class="active open"' : '' !!}>
                <a href="javascript:;">
                    <i class="icon-map"></i>
                    <span class="title">Report</span>
                    @if(Request::segment(1) == ('report') || Request::segment(1) == ('school') || Request::segment(1) == ('revenue') || Request::segment(1) == ('reportDashboard'))
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                    @else
                    <span class="arrow"></span>
                    @endif
                </a>
                <ul class="sub-menu">
                    <li {!! (Request::segment(1) == 'report' && Request::segment(2) == 'reportDashboard') ? 'class="active"' : '' !!}>
                        <a href="{{ route('report.reportDashboard') }}">
                            <i class="icon-user"></i>Report Dashboard</a>
                    </li>
                    <li {!! (Request::segment(1) == 'report' && Request::segment(2) == 'school') ? 'class="active"' : '' !!}>
                        <a href="{{ route('report.school') }}">
                            <i class="icon-user"></i>School Report</a>
                    </li>
                    <!--<li {!! (Request::segment(1) == 'report' && Request::segment(2) == 'student') ? 'class="active"' : '' !!}>
                        <a href="{{ route('report.student') }}">
                            <i class="icon-user"></i>Studen Report</a>
                    </li>-->
                    <li {!! (Request::segment(1) == 'report' && Request::segment(2) == 'parent') ? 'class="active"' : '' !!}>
                        <a href="{{ route('report.parent') }}">
                            <i class="icon-user"></i>Parent/Tutor Report</a>
                    </li>
                    <li {!! (Request::segment(1) == 'report' && Request::segment(2) == 'revenue') ? 'class="active"' : '' !!}>
                        <a href="{{ route('report.revenue') }}">
                            <i class="icon-user"></i>Revenue Report</a>
                    </li>

                    <li {!! (Request::segment(1) == 'report' && Request::segment(2) == 'admin') ? 'class="active"' : '' !!}>
                        <a href="{{ route('report.admin') }}">
                            <i class="icon-bar-chart"></i>
                            <span class="title">Track Progress/Report</span>
                            @if(Request::segment(1) == 'report')
                            <span class="selected"></span>
                            @endif
                        </a>
                    </li>
                </ul>
            </li>          
            @endif 
            <!-- END ADMIN USER TYPE MENU -->

            <!-- BEGIN Question Validator USER TYPE MENU -->
            @if(session()->get('user')['user_type'] == QUESTIONVALIDATOR)
            <li {!! (Request::segment(1) == 'questionbuilder' || Request::segment(1) == 'questionset' || Request::segment(1) == 'strand') ? 'class="active open"' : '' !!}>
                <a href="javascript:;">
                    <i class="icon-envelope"></i>
                    <span class="title">{{ trans('admin/admin.question_builder') }}</span>
                    @if(Request::segment(1) == ('questionbuilder') || Request::segment(1) == ('questionset') || Request::segment(1) == ('strand'))
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                    @else
                    <span class="arrow"></span>
                    @endif
                </a>
                <ul class="sub-menu">
                    <li {!! Request::segment(1) == 'questionbuilder' ? 'class="active"' : '' !!}>
                        <a href="{{ route('questionbuilder.index') }}">
                            {{ trans('admin/admin.manage_question') }}</a>
                    </li>
                </ul>
            </li> 
            <li {!! Request::segment(1) == 'myprofile' ? 'class="active"' : '' !!}>
                <a href="{{ route('myprofile') }}">
                    <i class="fa fa-cogs"></i>
                    <span class="title">{{ trans('admin/admin.manage_account') }}</span>
                    @if(Request::segment(1) == 'myprofile')
                    <span class="selected"></span>
                    @endif
                </a>
            </li>
            @endif 
            <!-- END ADMIN USER TYPE MENU -->

            <!-- BEGIN SCHOOL USER TYPE MENU -->
            @if(session()->get('user')['user_type'] == SCHOOL)

            <li {!! (Request::segment(1) == 'teacher' || Request::segment(1) == 'manageclass' || Request::segment(1) == 'managegroup') ? 'class="active"' : '' !!}>
                <a href="javascript:;">
                    <i class="icon-users"></i>
                    <span class="title">{{ trans('admin/admin.users') }}</span>
                    @if(Request::segment(1) == 'teacher' || Request::segment(1) == 'manageclass' || Request::segment(1) == 'managegroup')
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                    @else
                    <span class="arrow"></span>
                    @endif
                </a>
                <ul class="sub-menu">
                    <li {!! (Request::segment(1) == 'teacher') ? 'class="active open"' : '' !!}>
                        <a href="{{ route('teacher.index') }}">
                            <i class="icon-users"></i>
                            <span class="title">{{ trans('admin/admin.manage_teachers') }}</span>
                        </a>
                    </li>
                    <li {!! (Request::segment(1) == 'manageclass') ? 'class="active"' : '' !!}>
                        <a href="{{ route('manageclass.index') }}">
                            <i class="icon-users"></i>
                            {{ trans('admin/admin.classes') }}</a>
                    </li>
                    <li {!! (Request::segment(1) == 'managegroup') ? 'class="active"' : '' !!}>
                        <a href="{{ route('managegroup.index') }}">
                            <i class="icon-users"></i>
                            {{ trans('admin/admin.group') }}</a>
                    </li>
                </ul>
            </li>

            <li {!! Request::segment(1) == 'messages' || Request::segment(1) == 'emailtemplate' || Request::segment(1) == 'notification' ? 'class="active open"' : '' !!}>
                <a href="javascript:;">
                    <i class="icon-envelope"></i>
                    <span class="title">{{ trans('admin/admin.message_center') }}</span><span>&nbsp;@include('admin.messages.unread-count')</span>
                    @if(Request::segment(1) == ('message'))
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                    @else
                    <span class="arrow"></span>
                    @endif
                </a>
                <ul class="sub-menu">
                    <li {!! Request::segment(1) == 'messages' && Request::segment(2) == 'create' ? 'class="active"' : '' !!}>
                        <a href="{{ route('messages.create') }}">
                            <i class="icon-notebook"></i>
                            {{ trans('admin/admin.compose_message') }}</a>
                    </li>
                    <li {!! Request::segment(1) == 'messages' && Request::segment(2) == 'inbox' ? 'class="active"' : '' !!}>
                        <a href="{{ route('messages.inbox') }}">
                            <i class="icon-envelope"></i>
                            {{ trans('admin/admin.inbox') }}</a>
                    </li>
                    <li {!! Request::segment(1) == 'messages' && Request::segment(2) == 'sent' ? 'class="active"' : '' !!}>
                        <a href="{{ route('messages.sent') }}">
                            <i class="icon-envelope"></i>
                            {{ trans('admin/admin.sent_messages') }}</a>
                    </li>
                </ul>
            </li> 


            <li {!! Request::segment(1) == 'rewards' ? 'class="active"' : '' !!}>
                <a href="javascript:;">
                    <i class="glyphicon glyphicon-asterisk"></i>
                    <span class="title">{{ trans('admin/admin.rewards') }}</span>
                    @if(Request::segment(1) == 'rewards')
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                    @else
                    <span class="arrow"></span>
                    @endif
                </a>
                <ul class="sub-menu">
                    <li {!! Request::segment(1) == 'rewards' && Request::segment(2) == 'test' ? 'class="active"' : '' !!}>
                        <a href="{{ route('rewards.index','test') }}">
                            <i class="icon-notebook"></i>
                            {{ trans('admin/rewards.test_rewards') }}</a>
                    </li>
                    <li {!! Request::segment(1) == 'rewards' && Request::segment(2) == 'revision' ? 'class="active"' : '' !!}>
                        <a href="{{ route('rewards.index','revision') }}">
                            <i class="icon-notebook"></i>
                            {{ trans('admin/rewards.revision_rewards') }}</a>
                    </li>
                </ul>
            </li> 




            <li {!! Request::segment(1) == 'manageaccount' ? 'class="active"' : '' !!}>
                <a href="{{ route('manageaccount') }}">
                    <i class="fa fa-cogs"></i>
                    <span class="title">{{ trans('admin/admin.manage_account') }}</span>
                    @if(Request::segment(1) == 'manageaccount')
                    <span class="selected"></span>
                    @endif
                </a>
            </li>
            <li {!! Request::segment(1) == 'helpcentre' ? 'class="active"' : '' !!}>
                <a href="{{ route('helpcentre.index') }}">
                    <i class="icon-question"></i>
                    <span class="title">{{ trans('admin/admin.help_center') }}</span>
                    @if(Request::segment(1) == 'helpcentre')
                    <span class="selected"></span>
                    @endif
                </a>
            </li>
            <li {!! Request::segment(1) == 'upgradeaccount' ? 'class="active"' : '' !!}>
                <a href="{{ route('user.upgradeaccount',encryptParam(session()->get('user')['id'])) }}">
                    <i class="glyphicon glyphicon-cog"></i>
                    <span class="title">Upgrade Account</span>
                </a>
            </li>    
            <li {!! Request::segment(1) == 'renewaccount' ? 'class="active"' : '' !!}>
                <a href="{{ route('user.renewaccount',encryptParam(session()->get('user')['id'])) }}">
                    <i class="glyphicon glyphicon-gift"></i>
                    <span class="title">Renew Subscription</span>
                </a>
            </li>             
            <li {!! Request::segment(1) == 'report' ? 'class="active"' : '' !!}>
                <a href="{{ route('report.admin') }}">
                    <i class="icon-bar-chart"></i>
                    <span class="title">Track Progress/Report</span>
                    @if(Request::segment(1) == 'report')
                    <span class="selected"></span>
                    @endif
                </a>
            </li>
            @endif 
            <!-- END SCHOOL USER TYPE MENU -->

            @if(session()->get('user')['user_type'] == TEACHER)
            <!-- BEGIN TEACHER USER TYPE MENU -->
            <!-- <li {!! Request::segment(1) == 'student' ? 'class="active"' : '' !!}>
                 <a href="{{ route('student.index') }}">
                     <i class="icon-user"></i>
                     <span class="title">{{ trans('admin/admin.manage_students') }}</span>
                 </a>
             </li> -->


            <li {!! (Request::segment(1) == 'student') ? 'class="active"' : '' !!}>
                <a href="javascript:;">
                    <i class="icon-users"></i>
                    <span class="title">{{ trans('admin/admin.users') }}</span>
                    @if(Request::segment(1) == 'student')
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                    @else
                    <span class="arrow"></span>
                    @endif
                </a>
                <ul class="sub-menu">
                    <li {!! (Request::segment(1) == 'student') ? 'class="active open"' : '' !!}>
                        <a href="{{ route('student.index') }}">
                            <i class="icon-users"></i>
                            <span class="title">{{ trans('admin/admin.manage_students') }}</span>
                        </a>
                    </li>
                </ul>
            </li> 

            <li {!! Request::segment(1) == 'managetest' ? 'class="active"' : '' !!}>
                <a href="{{ route('managetest.index') }}">
                    <i class="fa fa-newspaper-o"></i>
                    <span class="title">{{ trans('admin/admin.manage_test') }}</span>
                    @if(Request::segment(1) == 'managetest')
                    <span class="selected"></span>
                    @endif
                </a>
            </li>

            <li {!! (Request::segment(1) == 'managerevision') ? 'class="active"' : '' !!}>
                <a href="{{ route('managerevision.index') }}">
                    <i class="fa fa-cogs"></i>
                    <span class="title">{{ trans('admin/admin.manage_revision') }}</span>
                    @if(Request::segment(1) == 'managerevision')
                    <span class="selected"></span>
                    @endif
                </a>
            </li> 


            <li {!! Request::segment(1) == 'messages' || Request::segment(1) == 'emailtemplate' || Request::segment(1) == 'notification' ? 'class="active open"' : '' !!}>
                <a href="javascript:;">
                    <i class="icon-envelope"></i>
                    <span class="title">{{ trans('admin/admin.message_center') }}</span><span>&nbsp;@include('admin.messages.unread-count')</span>
                    @if(Request::segment(1) == ('message'))
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                    @else
                    <span class="arrow"></span>
                    @endif
                </a>
                <ul class="sub-menu">
                    <li {!! Request::segment(1) == 'messages' && Request::segment(2) == 'create' ? 'class="active"' : '' !!}>
                        <a href="{{ route('messages.create') }}">
                            <i class="icon-notebook"></i>
                            {{ trans('admin/admin.compose_message') }}</a>
                    </li>
                    <li {!! Request::segment(1) == 'messages' && Request::segment(2) == 'inbox' ? 'class="active"' : '' !!}>
                        <a href="{{ route('messages.inbox') }}">
                            <i class="icon-envelope"></i>
                            {{ trans('admin/admin.inbox') }}</a>
                    </li>
                    <li {!! Request::segment(1) == 'messages' && Request::segment(2) == 'sent' ? 'class="active"' : '' !!}>
                        <a href="{{ route('messages.sent') }}">
                            <i class="icon-envelope"></i>
                            {{ trans('admin/admin.sent_messages') }}</a>
                    </li>
                </ul>
            </li> 


            <li {!! Request::segment(1) == 'rewards' ? 'class="active"' : '' !!}>
                <a href="javascript:;">
                    <i class="glyphicon glyphicon-asterisk"></i>
                    <span class="title">{{ trans('admin/admin.rewards') }}</span>
                    @if(Request::segment(1) == 'rewards')
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                    @else
                    <span class="arrow"></span>
                    @endif
                </a>
                <ul class="sub-menu">
                    <li {!! Request::segment(1) == 'rewards' && Request::segment(2) == 'test' ? 'class="active"' : '' !!}>
                        <a href="{{ route('rewards.index','test') }}">
                            <i class="icon-notebook"></i>
                            {{ trans('admin/rewards.test_rewards') }}</a>
                    </li>
                    <li {!! Request::segment(1) == 'rewards' && Request::segment(2) == 'revision' ? 'class="active"' : '' !!}>
                        <a href="{{ route('rewards.index','revision') }}">
                            <i class="icon-notebook"></i>
                            {{ trans('admin/rewards.revision_rewards') }}</a>
                    </li>
                </ul>
            </li>



            <li {!! Request::segment(1) == 'manageprofile' ? 'class="active"' : '' !!}>
                <a href="{{ route('manageprofile') }}">
                    <i class="fa fa-cogs"></i>
                    <span class="title">{{ trans('admin/admin.manage_account') }}</span>
                    @if(Request::segment(1) == 'manageprofile')
                    <span class="selected"></span>
                    @endif
                </a>
            </li> 
            <li {!! Request::segment(1) == 'helpcentre' ? 'class="active"' : '' !!}>
                <a href="{{ route('helpcentre.index') }}">
                    <i class="icon-question"></i>
                    <span class="title">{{ trans('admin/admin.help_center') }}</span>
                    @if(Request::segment(1) == 'helpcentre')
                    <span class="selected"></span>
                    @endif
                </a>
            </li>    
            <li {!! (Request::segment(1) == 'report' && Request::segment(2) == 'admin') ? 'class="active"' : '' !!}>
                <a href="{{ route('report.admin') }}">
                    <i class="icon-bar-chart"></i>
                    <span class="title">Track Progress/Report</span>
                    @if(Request::segment(1) == 'report')
                    <span class="selected"></span>
                    @endif
                </a>
            </li>
            @endif
            <!-- END TEACHER USER TYPE MENU -->

            <!-- BEGIN PARENT/TUTOR USER TYPE MENU -->
            @if(session()->get('user')['user_type'] == TUTOR)
            <li {!! Request::segment(1) == 'student' ? 'class="active"' : '' !!}>
                <a href="{{ route('student.index') }}">
                    <i class="icon-user"></i>
                    <span class="title">{{ trans('admin/admin.manage_students') }}</span>
                </a>
            </li>  
            <li {!! Request::segment(1) == 'messages' || Request::segment(1) == 'emailtemplate' || Request::segment(1) == 'notification' ? 'class="active open"' : '' !!}>
                <a href="javascript:;">
                    <i class="icon-envelope"></i>
                    <span class="title">{{ trans('admin/admin.message_center') }}</span><span>&nbsp;@include('admin.messages.unread-count')</span>
                    @if(Request::segment(1) == ('message'))
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                    @else
                    <span class="arrow"></span>
                    @endif
                </a>
                <ul class="sub-menu">
                    <li {!! Request::segment(1) == 'messages' && Request::segment(2) == 'sent' ? 'class="active"' : '' !!}>
                        <a href="{{ route('messages.create') }}">
                            <i class="icon-notebook"></i>
                            {{ trans('admin/admin.compose_message') }}</a>
                    </li>
                    <li {!! Request::segment(1) == 'messages' && Request::segment(2) == 'inbox' ? 'class="active"' : '' !!}>
                        <a href="{{ route('messages.inbox') }}">
                            <i class="icon-envelope"></i>
                            {{ trans('admin/admin.inbox') }}</a>
                    </li>
                    <li {!! Request::segment(1) == 'messages' && Request::segment(2) == 'sent' ? 'class="active"' : '' !!}>
                        <a href="{{ route('messages.sent') }}">
                            <i class="icon-envelope"></i>
                            {{ trans('admin/admin.sent_messages') }}</a>
                    </li>
                </ul>
            </li> 


            <li {!! Request::segment(1) == 'rewards' ? 'class="active"' : '' !!}>
                <a href="javascript:;">
                    <i class="glyphicon glyphicon-asterisk"></i>
                    <span class="title">{{ trans('admin/admin.rewards') }}</span>
                    @if(Request::segment(1) == 'rewards')
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                    @else
                    <span class="arrow"></span>
                    @endif
                </a>
                <ul class="sub-menu">
                    <li {!! Request::segment(1) == 'rewards' && Request::segment(2) == 'test' ? 'class="active"' : '' !!}>
                        <a href="{{ route('rewards.index','test') }}">
                            <i class="icon-notebook"></i>
                            {{ trans('admin/rewards.test_rewards') }}</a>
                    </li>
                    <li {!! Request::segment(1) == 'rewards' && Request::segment(2) == 'revision' ? 'class="active"' : '' !!}>
                        <a href="{{ route('rewards.index','revision') }}">
                            <i class="icon-notebook"></i>
                            {{ trans('admin/rewards.revision_rewards') }}</a>
                    </li>
                </ul>
            </li>

            <!--<li {!! Request::segment(1) == 'report' ? 'class="active"' : '' !!}>
                <a href="{!! url('comingsoon') !!}">
                    <i class="icon-user"></i>
                    <span class="title">{{ trans('admin/admin.reporting_center') }}</span>
                </a>
            </li> -->
            <li {!! Request::segment(1) == 'myaccount' ? 'class="active"' : '' !!}>
                <a href="{{ route('myaccount') }}">
                    <i class="fa fa-cogs"></i>
                    <span class="title">{{ trans('admin/admin.manage_account') }}</span>
                    @if(Request::segment(1) == 'myaccount')
                    <span class="selected"></span>
                    @endif
                </a>
            </li> 
            <li {!! Request::segment(1) == 'helpcentre' ? 'class="active"' : '' !!}>
                <a href="{{ route('helpcentre.index') }}">
                    <i class="icon-question"></i>
                    <span class="title">{{ trans('admin/admin.help_center') }}</span>
                    @if(Request::segment(1) == 'helpcentre')
                    <span class="selected"></span>
                    @endif
                </a>
            </li>  
            <li {!! Request::segment(1) == 'upgradeaccount' ? 'class="active"' : '' !!}>
                <a href="{{ route('user.upgradeaccount',encryptParam(session()->get('user')['id'])) }}">
                    <i class="glyphicon glyphicon-cog"></i>
                    <span class="title">Upgrade Account</span>
                </a>
            </li>    
            <li {!! Request::segment(1) == 'renewaccount' ? 'class="active"' : '' !!}>
                <a href="{{ route('user.renewaccount',encryptParam(session()->get('user')['id'])) }}">
                    <i class="glyphicon glyphicon-gift"></i>
                    <span class="title">Renew Subscription</span>
                </a>
            </li>             
            <li {!! (Request::segment(1) == 'report' && Request::segment(2) == 'admin') ? 'class="active"' : '' !!}>
                <a href="{{ route('report.admin') }}">
                    <i class="icon-bar-chart"></i>
                    <span class="title">Track Progress/Report</span>
                    @if(Request::segment(1) == 'report')
                    <span class="selected"></span>
                    @endif
                </a>
            </li>
            <!--            <li {!! Request::segment(1) == 'helpcentre' ? 'class="active"' : '' !!}>
                            <a href="{{ route('invoice.index') }}">
                                <i class="glyphicon glyphicon-list"></i>
                                <span class="title">{{ trans('admin/admin.invoice') }}</span>
                            </a>
                        </li> -->
            @endif 
            <!-- END PARENT/TUTOR USER TYPE MENU -->
            @if(session()->get('user')['user_type'] == QUESTIONADMIN)
            <li {!! (Request::segment(1) == 'questionbuilder' || Request::segment(1) == 'questionset' || Request::segment(1) == 'strand') ? 'class="active open"' : '' !!}>
                <a href="javascript:;">
                    <i class="icon-envelope"></i>
                    <span class="title">{{ trans('admin/admin.question_builder') }}</span>
                    @if(Request::segment(1) == ('questionbuilder') || Request::segment(1) == ('questionset') || Request::segment(1) == ('strand'))
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                    @else
                    <span class="arrow"></span>
                    @endif
                </a>
                <ul class="sub-menu">
                    <li {!! Request::segment(1) == 'questionbuilder' ? 'class="active"' : '' !!}>
                        <a href="{{ route('questionbuilder.index') }}">
                            {{ trans('admin/admin.manage_question') }}</a>
                    </li>
                </ul>
            </li> 
            <li {!! Request::segment(1) == 'myprofile' ? 'class="active"' : '' !!}>
                <a href="{{ route('myprofile') }}">
                    <i class="fa fa-cogs"></i>
                    <span class="title">{{ trans('admin/admin.manage_account') }}</span>
                    @if(Request::segment(1) == 'myprofile')
                    <span class="selected"></span>
                    @endif
                </a>
            </li> 
            @endif

        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
</div>  
