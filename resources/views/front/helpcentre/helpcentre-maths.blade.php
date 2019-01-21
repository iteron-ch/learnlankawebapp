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
                {!! Form::select('substrand', array('' => 'select'), null,['id' => 'substrand', 'class' => 'e1']) !!} 
                <div id="help_center">
                </div>
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

    var strands = <?php echo $strand ?>;

    var substrands = <?php echo $strandAtt ?>;
    var subject = '<?php echo $subject ?>';

    $(window).load(function () {
        $("#substrand").select2();
        jsMain.makeDropDownJsonData(strands, $("#strand"), subject, '');
        $("#strand").change(function () {
            jsMain.makeDropDownJsonData(substrands, $("#substrand"), $(this).val(), '');
        });
        $('#substrand').on('change', function () {
            $.ajax({
                url: '{{ route('helpcentre.details') }}',
                method: 'GET',
                data: {"strand": $("#strand").val(), "substrand": $("#substrand").val()},
                cache: false,
                beforeSend: function () {
                },
                success: function (response) {
                    
                    $("#help_center").html(response);
                },
                error: function (xhr, textStatus, errorThrown) {
                },
                complete: function () {
                }
            });
        });
    });
</script>
@stop