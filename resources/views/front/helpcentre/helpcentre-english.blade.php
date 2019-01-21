@extends('front.layout._default')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
     @include('front.helpcentre.leftbar-helpcentre', ['subject' => $subject]) 
    <div class="right_section">
        <div class="rtop">&nbsp;</div>
        <div class="rmid">
            <div class="help_center">
                    {!! Form::select('strand', array('' => 'select', $strand ), null,['id' => 'strand', 'class' => 'e1']) !!} 
                    <br>
                    {!! Form::select('substrand', array(), null,['id' => 'substrand', 'class' => 'e1']) !!} 
               
            </div>
        </div>
        <div class="rbot">&nbsp;</div>
    </div>
    <div class="clear_fix"></div>
</div>

@stop
@section('pagescripts')
<script>
    jsMain = new Main("{{ trans('admin/admin.select_option') }}");
    /*var strands = <?php //echo $strand ?>;
    var substrands = <?php //echo $strandAtt ?>;
    var subject = '<?php //echo $subject ?>';
    $(window).load(function() {
        jsMain.makeDropDownJsonData(strands, $("#strand"), subject, '');
         $("#strand").change(function() {
            jsMain.makeDropDownJsonData(substrands, $("#substrand"), $(this).val(), '');
        });
    });*/
</script>
@stop