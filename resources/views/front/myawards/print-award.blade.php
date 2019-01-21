@extends('front.layout._iframe')
@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container" style="text-align:center;margin-top: 40px;">
    <div class="row button_row" id="print_btn">
        <div class="col3">
            <input type="button" onClick="printAward();" class="button" value="Print Certificate">
        </div>
        <br>
    </div>
    <img src="/uploads/myawards/{{ $filename}}" border="0">
</div>
<style>
@media print {
        @page {
            margin: 0mm;
        }
    }    
</style>
<script>
  function printAward(){
        $("#print_btn").hide();
        window.print();
        $("#print_btn").show();
  }  
</script>
@stop