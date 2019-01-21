
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->


<div class="portlet box red">
    <div class="portlet-title">
        <div class="caption">
            <input type="button" class="btn default" name="export" value="Export To Excel" onclick="exportdata('classgap', 'classgapreport')"/>
        </div>
    </div>
    <div class="portlet-body">
        <div class="table-scrollable">
            <table class="table" id="classgap">
                <tr>
                    <th>Q No</th>
                    <th class="col-md-4">Question(Strands/Substrands)</th>
                    <?php
                    if (count($schoolClassStudents) > 0) {
                        foreach ($schoolClassStudents as $sRow) {
                            ?>
                            <th class="ys"><?php echo $sRow['last_name'] . " " . $sRow['first_name']; ?></th>
                            <?php
                        }
                        ?>
                    </tr>
                    <?php
                    if (count($setQuestions) > 0) {
                        $qNum = 1;
                        foreach ($setQuestions as $qRow) {
                            ?>
                            <tr>
                                <td><?php echo $qNum ?></td>
                                <td><?php echo $arrStrands[$qRow['strands_id']] . '<br/>' . $arrSubStrands[$qRow['strands_id']][$qRow['substrands_id']]; ?></td>
                                <?php
                                if (!count($studentQuestionAttempt)) {
                                    foreach ($schoolClassStudents as $sRow) {
                                        echo '<td align="center" style="font-weight:bold;">&nbsp;</td>';
                                    }
                                } else {
                                    foreach ($schoolClassStudents as $sRow) {
                                        if (isset($studentQuestionAttempt[$sRow['id']])) {
                                            if(isset($studentQuestionAttempt[$sRow['id']][$qRow['id']])){
                                                if($studentQuestionAttempt[$sRow['id']][$qRow['id']] > 0){
                                                    echo '<td align="center" style="color:green; font-weight:bold;">Y</td>';
                                                }else{
                                                    echo '<td align="center" style="color:red; font-weight:bold;">N</td>';
                                                }
                                            }else{
                                                echo '<td align="center" style="font-weight:bold;">&nbsp;</td>';
                                            }
                                        } else {
                                            echo '<td align="center" style="font-weight:bold;">&nbsp;</td>';
                                        }
                                    }
                                }
                                ?>
                            </tr>
                            <?php
                            $qNum++;
                        }
                    }
                }
                ?>
            </table>
        </div>
    </div>
</div>
<style>
    .table th.ys {
   text-align: center;   
}
    </style>