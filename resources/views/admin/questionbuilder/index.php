<!doctype html>
<html class="no-js">
  <head>
    <meta charset="utf-8">
    <title>Question Builder</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">

    <link rel="stylesheet" href="bower_components/custom/css/main.css">
    <link rel="stylesheet" href="bower_components/ckeditor/samples/sample.css">
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.css">
    <!-- endbuild -->
  </head>
  <body ng-app="questionBuilder" ng-controller="MainCtrl">

    <!-- View's container: Code start -->
    <div ng-view=""></div>
    <!-- View's container: Code end -->
    
    <!-- flash message: Code start -->
    <div flash-message="5000"></div>
    <!-- flash message: Code end -->
    
    <!-- build:js(.) scripts/vendor.js -->
    <!-- bower:js -->
    <script src="bower_components/jquery/dist/jquery.js"></script>
    <script src="bower_components/angular/angular.js"></script>
    <script src="bower_components/bootstrap/dist/js/bootstrap.js"></script>
    <script src="bower_components/angular-animate/angular-animate.js"></script>
    <script src="bower_components/angular-route/angular-route.js"></script>
    <script src="bower_components/ckeditor/ckeditor.js"></script>
    <script src="bower_components/jquery-ui/jquery-ui.js"></script>
    <script src="bower_components/angular-flash-alert/dist/angular-flash.js"></script>
    <script src="bower_components/angular-ui-bootstrap-bower/ui-bootstrap-tpls.js"></script>
    <!-- endbuild -->
    
    <!-- build:js({.tmp,app}) scripts/scripts.js -->
    <script src="bower_components/custom/js/main.js"></script>
    <script src="bower_components/custom/js/singlechoice.js"></script>
    <script src="bower_components/custom/js/multiplechoice.js"></script>
    <script src="bower_components/custom/js/fillintheblanks.js"></script>
    <script src="bower_components/custom/js/matchdragdrop.js"></script>
    <script src="bower_components/custom/js/measurement.js"></script>
    <script src="bower_components/custom/js/simplequestion.js"></script>
    <script src="bower_components/custom/js/selectliteracy.js"></script>
    <script src="bower_components/custom/js/labelliteracy.js"></script>
    <!-- endbuild -->
</body>
</html>
