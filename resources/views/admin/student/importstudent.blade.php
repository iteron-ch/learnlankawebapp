@extends('admin.layout._iframe')
@section('content')
<!-- BEGIN PAGE HEADER-->


<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN VALIDATION STATES-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    Import Pupils
                </div>
            </div>

            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                {!! Form::open(['route' => ['importstudent.store'], 'method' => 'post', 'files' => true,'class' => 'form-horizontal','id' =>'inportfrm']) !!}
                {!! Form::hidden('school', isset($school_id)?$school_id:'', ['id' => 'school', 'class' => 'form-control']) !!}  
                <div class="form-body">
                    @include('admin.partials.form_error')

                    <div class="form-group">
                        {!! Form::labelControl('file1','Upload file',['class'=>'control-label col-md-3'], FALSE )  !!}
                        <div class="col-md-4">
                            {!! Form::file('file1', ['id'=>'file1','class' => 'form-control']) !!}
                        </div>
                    </div> 

                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                {!! Form::button(trans('admin/admin.submit'), array('type' => 'submit', 'class' => 'btn green')) !!}
                            </div>
                        </div>
                    </div>
                    <!--	</form>-->
                    {!! Form::close() !!}
                    <!-- END FORM-->
                </div>
                <!-- END VALIDATION STATES-->
            </div> 
            @if(!empty($emptyRecords))
            <div class="alert alert-danger display-show"><strong>Following records have some errors.</strong>
                <?php
                if (!empty($emptyRecords)) { 
                    foreach ($emptyRecords as $key => $val) {
                        $fieldArray = array();
                        echo '<li><strong>Row No. ' . ($key + 1) . '</strong> following fields may not be empty. <br>';
                        foreach ($val as $key2 => $val2) {
                            $fieldArray[] = '<strong>' . $val2 . '</strong>';
                        }
                        echo implode(', ', $fieldArray);
                    }
                }
                ?>
            </div>
            @endif    
            
            @if(!empty($duplicateExistsReocrds))
            <div class="alert alert-danger display-show"><strong>Following records have some errors.</strong>
            @foreach($duplicateExistsReocrds as $key=>$val)
                <?php
                        echo '<li>' . $val . '</li>';
                ?>
                @endforeach
                </div>
            @endif      
                
                
            @if(!empty($doesnotExistsReocrds))
            <div class="alert alert-danger display-show"><strong>Following Teacher's Email Address does not exists. Please enter correct email.</strong>
                @foreach($doesnotExistsReocrds as $key=>$val)
                <?php
                echo '<li>' . $val . '</li>';
                ?>
                @endforeach
            </div>
            @endif 
            @if(!empty($insertedRecords))
            <div class="alert alert-danger display-show">
                <li><?php echo count($insertedRecords) ?> records has been imported successfully.</li>
            </div>
            @endif
            @if(!empty($recordsCountError))
            <div class="alert alert-danger display-show">
                <li><?php echo $recordsCountError ?></li>
            </div>
            @endif

        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop
