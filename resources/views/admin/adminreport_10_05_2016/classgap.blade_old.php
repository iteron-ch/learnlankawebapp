@extends(($layout == 'iframe') ? 'admin.layout._reports' : 'admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
<?php if($layout != 'iframe'){ ?>
@include('admin.layout._page_header', ['title' => trans('admin/admin.manageaccount'), 'trait_1' => $trait['trait_1'], 'trait_1_link' => $trait['trait_1_link'],'trait_2' => $trait['trait_2']])
<?php } ?>
<style>
.select2-container{width:200px}
</style>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->

<div class="row">
<div class="col-md-12 col-sm-12">
    <div class="dataTables_length select_box_row" style="margin:10px;">
        <div style="float:right;margin:15px 16px 0 0 ;"><a href="#" onclick="window.print()">Print Report</a></div>
		<label style="margin-right:20px;">
			<select name="class" class="select2me" id="class" onchange="selectQuestion(this.value)" class="form-control" >
                                <option value="">Select Question</option>
                                <?php
                                    if(count($questionResultsAll)>0){
                                        foreach($questionResultsAll as $k=>$v){
                                ?>
                                <option value="<?php echo $v->id ?>" <?php if(!empty($questionId) && $questionId == $v->id ){ ?> selected="selected"<?php }?>><?php echo $v->strand.', '.$v->substrands.', '.$v->question; ?></option>
                                <?php }
                                    } 
                                ?>
                            </select>
		</label>
		<label><select name="student" class="select2me" id="student" onchange="selectStudent(this.value)" class="form-control">
                                <option value="">Select Pupil</option>
                                <?php
                                    if(count($studentList)>0){
                                        foreach($studentList as $ke=>$ve){
                                ?>
                                 <option value="<?php echo $ve->id ?>" <?php if(!empty($studentId) && $studentId == $ve->id ){ ?> selected="selected"<?php }?> ><?php echo $ve->last_name." ".$ve->first_name; ?></option>
                                <?php }
                                    } 
                                ?>
                            </select>
		</label>
        <a href="/adminreport/classgap?schoolId=<?php echo $schoolId; ?>&layout=<?php echo $layout ?>" style="margin-left:15px;"> <input type="button" name="reset" id="reset" value="Reset Filter" class="btn btn-primary grey"></a>
	</div>
</div>
		<div class="col-md-12">
			<div class="portlet box red">
				<div class="portlet-title">
					<div class="caption">
						 <input type="button" class="btn default" name="export" value="Export To Excel" onclick="exportdata('classgap','classgapreport')"/>
					</div>
				</div>
				<div class="portlet-body">
					<div class="table-scrollable">
						<table class="table" id="classgap">
                    <tr>
                        <th>Question(Strands/Substrands)</th>
                        <?php
                            if(count($studentList)>0){
                                foreach($studentList as $ke=>$ve){
                        ?>
                            <th><?php echo $ve->last_name." ".$ve->first_name; ?></th>
                         <?php }
                            } 
                        ?>
                    </tr>
                    <?php
                        if(count($questionList)>0){
                            foreach($questionList as $k=>$v){
                    ?>
                    <tr>
                        <td><?php echo $v->strand.'<br/>'.$v->substrands.'<br/>'.$v->question; ?></td>
                        <?php
                            if(count($studentList)>0){
                                foreach($studentList as $ke=>$ve){
                        ?>
                            <td>
                               <?php 
                                    if(!empty($studentAns[$ve->id][$v->id])){
                                        if($studentAns[$ve->id][$v->id] == 'complete'){
                                            echo 'Y';
                                        }else{
                                            echo 'N';
                                        }
                                    }else{
                                        echo "-";
                                    }
                               
                               ?>
                            
                            </td>
                        <?php }
                            } 
                        ?>
                    </tr>
                    <?php 
                            }
                        } 
                    ?>
                </table>
					</div>
				</div>
			</div>
		</div>
	</div>

<!-- END PAGE CONTENT-->
@stop
@section('pagescripts')
{!! HTML::script('js/jquery.table2excel.js') !!}
<script>
function exportdata(id,name){
    $("#"+id).table2excel({
        exclude: ".noExl",
        name: "Excel Document Name",
        filename: name,
    }); 
}
    function selectQuestion(queId){
        var id = '<?php echo $schoolId;?>';
         var layout = '<?php echo $layout ?>'; 
        <?php if(!empty($studentId)){?>
            var sid = '<?php echo $studentId;?>';
            window.location.href = "/adminreport/classgap?schoolId="+id+"&queId="+queId+"&stuId="+sid+"&layout="+layout;;  
        <?php }else{?>
            window.location.href = "/adminreport/classgap?schoolId="+id+"&queId="+queId+"&layout="+layout;;  
        <?php } ?> 
    }
    
    function selectStudent(stuId){
        var id = '<?php echo $schoolId;?>';
        var layout = '<?php echo $layout ?>'; 
        <?php if(!empty($questionId)){?>
            var qid = '<?php echo $questionId;?>';
            window.location.href = "/adminreport/classgap?schoolId="+id+"&stuId="+stuId+"&queId="+qid+"&layout="+layout;;
        <?php }else{?>
            window.location.href = "/adminreport/classgap?schoolId="+id+"&stuId="+stuId+"&layout="+layout;;  
        <?php } ?>    
          
    }

</script>
@stop