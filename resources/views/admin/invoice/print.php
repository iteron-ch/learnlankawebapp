<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <meta content="" name="description"/>
        <meta content="" name="author"/>
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
        <link href="../../assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="../../assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
        <link href="../../assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="../../assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
        <link href="../../assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="../../assets/admin/pages/css/invoice.css" rel="stylesheet" type="text/css"/>
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME STYLES -->
        <link href="../../assets/global/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
        <link href="../../assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
        <link href="../../assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
        <link id="style_color" href="../../assets/admin/layout/css/themes/darkblue.css" rel="stylesheet" type="text/css"/>
        <link href="../../assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
        <!-- END THEME STYLES -->
        <link rel="shortcut icon" href="favicon.ico"/>
    </head>
    <div class="clearfix">
    </div>
    <!-- BEGIN CONTAINER -->
    <div class="page-container">

        <div class="page-content-wrapper">
            <div class="page-content">

                <div class="invoice">
                    <div class="row invoice-logo">
                        <div class="col-xs-6 invoice-logo-space">
                            <img src="../../images/logo.png" class="img-responsive" alt=""/>
                        </div>
                        <div class="col-xs-6">
                            <h4> <?php echo $invoiceDetails['address'] . ',' . $invoiceDetails['postal_code'] . ',' . $invoiceDetails['postal_code']; ?></h4>
                            <h4> <?php echo $invoiceDetails['city'] ?></h4>
                            <h4> <?php echo $invoiceDetails['email'] ?></h4>
                        </div>
                    </div>
                    <h1 style="font-family:Sans-serif"><font color="#6495ED"><?php
                            if ($invoiceDetails['payment_type'] == 'Creditcard') {
                                echo RECEIPT;
                            } else {
                                echo INVOICE;
                            }
                            ?></font></h1>
                    <h3> <strong><?php
                            if (isset($invoiceDetails['school_name'])) {
                                echo $invoiceDetails['school_name'];
                            } else {
                                echo $invoiceDetails['first_name'] . ' ' . $invoiceDetails['last_name'];
                            }
                            ?> </strong></h3>
                    <div style="width: 100%">
                        <div style="width: 45%; float: left">
                            <strong>INVOICE NO: SC-<?php echo date("Y") . '-' . $invoiceDetails['transaction_id']; ?> <br>
                                </div>
                                <div style="width: 45%; float: right">
                                    Date: <?php echo outputDateFormat($invoiceDetails['payment_date']) ?> <br></strong>
                                </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <style type="text/css">
                                    .tg  {border-collapse:collapse;border-spacing:0;width: 100%}
                                    .tg td{font-family:Arial, sans-serif;font-size:14px;padding:9px 20px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
                                    .tg th{text-align: center;font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:9px 20px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
                                    .tg .tg-yw4l{vertical-align:top;font-weight:bold;text-align: left}
                                    .tg .tg-yw42{ text-align: right }
                                    .tg .tg-yw43{ text-align: left; }
                                </style>
                                <table class="tg">
                                    <tr>
                                        <th class="tg-yw4l">Description</th>
                                        <th class="tg-yw4l">Subscriptions</th>
                                        <th class="tg-yw4l">Total</th>
                                    </tr>
                                    <?php if ($invoiceDetails['plan_students'] != '') { ?>
                                        <tr>
                                            <td class="tg-yw43">Standard Package</td>
                                            <td class="tg-yw43"><?php echo $invoiceDetails['plan_students']; ?></td>
                                            <td class="tg-yw42"><?php echo CURRENCY . '{' . $invoiceDetails['plan_amount'] . '}'; ?></td>
                                        </tr>
                                        <?php
                                    }
                                    if ($invoiceDetails['additional_students'] != '') {
                                        ?>
                                        <tr>
                                            <td class="tg-yw43">Additional Subscriptions</td>
                                            <td class="tg-yw43"><?php echo $invoiceDetails['additional_students']; ?></td>
                                            <td class="tg-yw42"><?php echo CURRENCY . '{' . $invoiceDetails['additional_amount'] . '}'; ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td class="tg-yw42" colspan="2">Voucher Applied</td>
                                        <td class="tg-yw42"><b><?php
                                                if ($invoiceDetails['discount_amount'] == '0.00') {
                                                    echo CURRENCY . "{0.00}";
                                                } else {
                                                    echo CURRENCY . '{' . $invoiceDetails['discount_amount'] . '}';
                                                }
                                                ?></b></td>
                                    </tr>
                                    <tr>
                                        <td class="tg-yw42" colspan="2">Total</td>
                                        <td class="tg-yw42"><b><?php echo CURRENCY . '{' . $invoiceDetails['amount'] . '}'; ?></b></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row" >
                            <div class="col-xs-12">
                                <div>
                                    <?php
                                    if ($invoiceDetails['payment_type'] == 'Creditcard') {
                                        ?>
                                        <p style=" padding: 2%;width: 100%"><?php echo INVOICE_FOOTER_PAID_PAYMENT_1; ?></p> 
                                        <?php
                                    } else {
                                        ?>
                                        <p style=" padding: 2%;width: 100%"><?php echo INVOICE_FOOTER_1; ?></p> 
                                        <p style="border:1px solid black; padding: 2%"> <?php echo INVOICE_FOOTER_2; ?></p> 
                                        <?php
                                    }
                                    ?>

                                    
                                </div>
                            </div>
                        </div><hr>
                        <div >
                            <p style="text-align:center;"><?php echo INVOICE_FOOTER_3; ?></p> 
                        </div>
                    </div>
                    <a style="margin-left: 45%;margin-top: 5%" class="btn btn-lg blue hidden-print margin-bottom-5" onclick="javascript:window.print();">
                        Print <i class="fa fa-print"></i>
                    </a>
                    <!-- END PAGE CONTENT-->
                </div>
            </div>
            <!-- END CONTENT -->
            <!-- BEGIN QUICK SIDEBAR -->
            <a href="javascript:;" class="page-quick-sidebar-toggler"><i class="icon-close"></i></a>
        </div>
        <!-- END CONTAINER -->
        <!-- BEGIN FOOTER -->
        <div class="page-footer">
            <div class="scroll-to-top">
                <i class="icon-arrow-up"></i>
            </div>
        </div>
        <script src="../../assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
        <!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
        <script src="../../assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <script src="../../assets/global/scripts/metronic.js" type="text/javascript"></script>
        <script src="../../assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
        <script src="../../assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
        <script src="../../assets/admin/layout/scripts/demo.js" type="text/javascript"></script>
        <script>
                        jQuery(document).ready(function () {
                            Metronic.init(); // init metronic core components
                            Layout.init(); // init current layout
                            QuickSidebar.init(); // init quick sidebar
                            Demo.init(); // init demo features

                        });
        </script>
        <!-- END JAVASCRIPTS -->
    </body>
    <!-- END BODY -->
</html>