/**
 * Created by sunny.rana
 */
'use strict';

/**
 * @ngdoc function
 * @name questionBuilder.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the questionBuilder
 */
var app = angular.module('questionBuilder', ['ngAnimate', 'ngRoute', 'ui.bootstrap', 'flash', 'easypiechart', 'myDirectivesApplication']);

app.config(function ($routeProvider, $modalProvider, $interpolateProvider) {
    $interpolateProvider.startSymbol('[%');
    $interpolateProvider.endSymbol('%]');
    $routeProvider
            .when('/singlechoice', {
                templateUrl: '/qb_singlechoice'
            }).when('/fillintheblanks', {
                templateUrl: '/qb_fillintheblanks'
            }).when('/multiplechoice', {
                templateUrl: '/qb_multiplechoice'
            }).when('/matchdragdrop', {
                templateUrl: '/qb_matchdragdrop'
            }).when('/measurement', {
                templateUrl: '/qb_measurement'
            }).when('/simplequestion', {
                templateUrl: '/qb_simplequestion'
            }).when('/selectliteracy', {
                templateUrl: '/qb_selectliteracy'
            }).when('/labelliteracy', {
                templateUrl: '/qb_labelliteracy'
            }).when('/insertliteracy', {
                templateUrl: '/qb_insertliteracy'
            }).when('/wordonimage', {
                templateUrl: '/qb_wordonimage'
            }).when('/underlineliteracy', {
                templateUrl: '/qb_underlineliteracy'
            }).when('/numberwordselect', {
                templateUrl: '/qb_numberwordselect'
            }).when('/singlemultipleentry', {
                templateUrl: '/qb_singlemultipleentry'
            }).when('/dragdrop', {
                templateUrl: '/qb_dragdrop'
            }).when('/drawinggraph', {
                templateUrl: '/qb_drawinggraph'
            }).when('/boolean', {
                templateUrl: '/qb_boolean'
            }).when('/spellingaudio', {
                templateUrl: '/qb_spellingaudio'
            }).when('/drawlineonimage', {
                templateUrl: '/qb_drawlineonimage'
            }).when('/joiningdots', {
                templateUrl: '/qb_joiningdots'
            })/*.when('/reflection', {
                templateUrl: '/qb_reflection'
            })*/.when('/shadingshape', {
                templateUrl: '/qb_shadingshape'
            }).when('/reflectionrightleft', {
                templateUrl: '/qb_reflectionrightleft'
            }).when('/reflectionleftright', {
                templateUrl: '/qb_reflectionleftright'
            }).when('/reflectionbottomtop', {
                templateUrl: '/qb_reflectionbottomtop'
            }).when('/reflectiontopbottom', {
                templateUrl: '/qb_reflectiontopbottom'
             }).when('/reflectionleftdiagonal', {
                templateUrl: '/qb_reflectionleftdiagonal'
            }).when('/reflectionrightdiagonal', {
                templateUrl: '/qb_reflectionrightdiagonal'
            }).when('/measurementlineangle', {
                templateUrl: '/qb_measurementlineangle'
            }).when('/piechart', {
                templateUrl: '/qb_piechart'
            }).when('/symmetric', {
                templateUrl: '/qb_symmetric'
            }).when('/inputonimage', {
                templateUrl: '/qb_inputonimage'
            }).when('/tableinputentry', {
                templateUrl: '/qb_tableinputentry'
            });

    //modal popup turn it off globally here!.
    $modalProvider.options.animation = true;
});
app.controller('MainCtrl', function ( $scope, $rootScope, $location, $filter, $sce, $timeout, $route, $modal ) {
    $scope.isCriteriaOpen = true;
    $scope.locationUrlPath = '';
    $scope.selected = {
        'keystage' : '',
        'yeargroup' : '',
        'subject' : '',
        'setgroup' : '',
        'setname' :'',
        'paperset' : '',
        'difficulty' : '',
        'strand' : '',
        'substrand' : '',
        'questiontype' : '',
        'populatedID' : '',
        'dynamicId1' : '',
        'dynamicId2' : '',
        'quesnote' : ''
    };
    
    $scope.selectedreason = '';
    $scope.isvalidatevisisble = '';
    $scope.questionData = '';

    $rootScope.MASTERS = [];
    $scope.setDefaultValue = function( valObj, editQuestionObj ){
        if( editQuestionObj.questionparam ){
            $scope.selected = editQuestionObj.questionparam;
            $scope.selected.quesnote = $scope.selected.quesnote ? $scope.selected.quesnote : '';
            $scope.selected.questiontype = parseInt( $scope.selected.questiontype, 10 );
            $scope.changeView(editQuestionObj.questionparam.questiontype);
            $scope.questionData = editQuestionObj;
        }
        
        $rootScope.MASTERS.keystage = valObj.keystage;
        $rootScope.MASTERS.yeargroup = valObj.yeargroup;
        $rootScope.MASTERS.subject = valObj.subject;
        $rootScope.MASTERS.setgroup = valObj.setgroup;
        $rootScope.MASTERS.questionset = valObj.questionSet;
        $rootScope.MASTERS.difficulty = valObj.difficulty;
        $rootScope.MASTERS.paperset = valObj.paper;
        $rootScope.MASTERS.questiontype = valObj.questionType;
        $rootScope.MASTERS.strand = valObj.strand;
        $rootScope.MASTERS.substrand = valObj.substrands;
        $rootScope.MASTERS.validateReason = valObj.validateReason;
        $scope.isvalidatevisisble = valObj.isvalidatevisisble;
        
        $rootScope.MASTERS.tempquestiontype = {
            'Math': [
                {'id': 13, 'name': "Boolean Type Question"},
                {'id': 11, 'name': "Drag Drop & Re-ordering"},
                {'id': 19, 'name': "Draw line on Image"},
                {'id': 3, 'name': "Fill in the blanks"},
                {'id': 31, 'name': "Input on Image"},
                {'id': 18, 'name': "Joining Dots on Diagram/Grid"},
                {'id': 30, 'name': "Line of Symmetry Question"},
                {'id': 4, 'name': "Matching with drag & drop question"},
                {'id': 20, 'name': "Measurement (image)"},
                {'id': 28, 'name': "Measure Line and Angle with text box (image)"},
                {'id': 16, 'name': "Missing Words on Image"},
                {'id': 22, 'name': "Missing Number on Image"},
                {'id': 1, 'name': "Multiple Choice with Single Answer"},
                {'id': 2, 'name': "Multiple Choice with Multiple Answers"},                
                {'id': 12, 'name': "Number /Word Selection (Circle)"},
                {'id': 6, 'name': "Numerical/Text Box Single or Multiple Question (image) with Keywords"},
                {'id': 29, 'name': "Pie Chart"},
                {'id': 24, 'name': "Reflection (Bottom - Top)"},
                {'id': 26, 'name': "Reflection (Left - Diagonal)"},
                {'id': 23, 'name': "Reflection (Left - Right)"},
                {'id': 27, 'name': "Reflection (Right - Diagonal)"},
                {'id': 17, 'name': "Reflection (Right - Left)"},
                {'id': 25, 'name': "Reflection (Top - Bottom)"},
                {'id': 8, 'name': "Spelling with Audio"},
                {'id': 21, 'name': "Shading Shapes"},
                {'id': 10, 'name': "Table - Single/Multiple entry"},
                {'id': 32, 'name': "Table - Fill Blanks"},
                
                
            ],'English': [
                {'id': 13, 'name': "Boolean Type Question"},
                {'id': 11, 'name': "Drag Drop & Re-ordering"},
                {'id': 19, 'name': "Draw line on Image"},
                {'id': 3, 'name': "Fill in the blanks"},
                {'id': 31, 'name': "Input on Image"},
                {'id': 7, 'name': "Insert Literacy Feature"},
                {'id': 15, 'name': "Label Literacy Feature"},
                {'id': 30, 'name': "Line of Symmetry Question"},
                {'id': 4, 'name': "Matching with drag & drop question"},
                {'id': 16, 'name': "Missing Words on Image"},
                {'id': 1, 'name': "Multiple Choice with Single Answer"},
                {'id': 2, 'name': "Multiple Choice with Multiple Answers"},
                {'id': 12, 'name': "Number /Word Selection (Circle)"},
                {'id': 6, 'name': "Numerical/Text Box Single or Multiple Question (image) with Keywords"},
                {'id': 8, 'name': "Spelling with Audio"},
                {'id': 14, 'name': "Select Literacy Feature"},
                {'id': 10, 'name': "Table - Single/Multiple entry"},
                {'id': 32, 'name': "Table - Fill Blanks"},
                {'id': 9, 'name': "Underline Literacy Feature"}
                
            ]
        };
        
    };

    $rootScope.MASTERS.api = {
        'questionbuilder' : '/questionbuilder',
        'uploadimage' : '/questionbuilder/uploadimage',
        'uploadaudio' : '/questionbuilder/uploadaudio'
    };
    
    $rootScope.MASTERS.keywords = {
        'singlequestionexpand' : 'Single question expand',
        'description' : 'Description',
        'marks' : 'Marks',
        'markstooltip' : 'Marks',
        'add' : 'ADD',
        'addtooltip' : 'Add Question',
        'edit' : 'EDIT',
        'edittooltip' : 'Edit',
        'delete' : 'DELETE',
        'deletetooltip' : 'Delete',
        'preview' : 'PREVIEW',
        'previewtooltip' : 'Preview',
        'setanswer' : 'SET ANSWER',
        'setanswertooltip' : 'Set Answer',
        'setyouranswerhere' : 'Set Your Answer Here',
        'save&next' : 'Save & Next',
        'save' : 'Save',
        'cancel' : 'Cancel',
        'correctanswers' : 'Correct Answers',
        'displaychoice' : 'Display Choice:',
        'horizontal' : 'Horizontal',
        'vertical' : 'Vertical',
        'drag&drop' : 'Drag & Drop',
        'dragoptions' : 'Drag Options',
        'tick(yes/no)' : 'Tick (Yes/No)',
        'draw' : 'Draw:',
        'circle' : 'Circle',
        'underline' : 'Underline',
        'addcolumn' : 'Add Column',
        'addrow' : 'Add Row',
        'singlechoice' : 'Single Choice',
        'multiplechoice' : 'Multiple Choice',
        'inputchoice' : 'Input Choice',
        'optiontype' : 'Option Type:',
        'noofcharacters' : 'No. of Characters:',
        'label' : 'Label',
        'previewquestion' : 'Preview Question',
        'close' : 'CLOSE',
        'questionsuccess' : 'Question has been successfully updated.',
        'alertsetanswer' : 'Please enter all information before set answer.',
        'alertsubmitanswer' : 'Please enter remaining information in question ',
        'alertpreview' : 'Please enter all information before preview.',
        'typeerror' : 'Error!',
        'typesuccess' : 'Success!',
        'labelboolean1' : 'Shows multiple questions on selection of multiple correct answers',
        'submitaction1' : 'list',
        'submitaction2' : 'reload',
        'submitaction3' : 'cancel',
        'submitaction4' : 'save',
        'container' : "Add 'Drop' Container",
        'containertooltip' : "Add 'Drop' Container",
        'deletecontainer' : "Remove 'Drop' Container",
        'deletecontainertooltip' : "Remove 'Drop' Container"
    };
    
    $rootScope.MASTERS.editorConfig = [
            {name: 'forms', items: []},
            {name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat', '-', 'TextField']},
            {name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'CreateDiv']},
            {name: 'insert', items: ['Image', 'Mathjax', 'Table', 'SpecialChar']},
            '/',
            {name: 'styles', items: ['Styles', 'FontSize']},
            {name: 'colors', items: ['TextColor', 'BGColor']},
        ];
    
    $scope.updateDropdownValues = function( json ){
      
       switch (json.type) {
            case 'keystage':
                $scope.changeView('');
                $scope.selected.yeargroup = '';
                $scope.selected.subject = '';
                $scope.selected.setgroup = '';
                $scope.selected.setname = '';
                $scope.selected.paperset = '';
                $scope.selected.difficulty = '';
                $scope.selected.strand = '';
                $scope.selected.substrand = '';
                $scope.selected.questiontype = '';
                $scope.selected.populatedID = '';
                $scope.selected.dynamicId1 = '';
                $scope.selected.dynamicId2 = '';
                break;
            case 'yeargroup':
                $scope.changeView('');
                $scope.selected.subject = '';
                $scope.selected.setgroup = '';
                $scope.selected.setname = '';
                $scope.selected.paperset = '';
                $scope.selected.difficulty = '';
                $scope.selected.strand = '';
                $scope.selected.substrand = '';
                $scope.selected.questiontype = '';
                $scope.selected.populatedID = '';
                $scope.selected.dynamicId1 = '';
                $scope.selected.dynamicId2 = '';
                break;
            case 'subject':
                $scope.changeView('');
                $scope.selected.setgroup = '';
                $scope.selected.setname = '';
                $scope.selected.paperset = '';
                $scope.selected.difficulty = '';
                $scope.selected.strand = '';
                $scope.selected.substrand = '';
                $scope.selected.questiontype = '';
                $scope.selected.populatedID = '';
                $scope.selected.dynamicId1 = '';
                $scope.selected.dynamicId2 = '';
                break;
             case 'setgroup':
                $scope.changeView('');
                $scope.selected.setname = '';
                $scope.selected.paperset = '';
                $scope.selected.difficulty = '';
                $scope.selected.strand = '';
                $scope.selected.substrand = '';
                $scope.selected.questiontype = '';
                $scope.selected.populatedID = '';
                $scope.selected.dynamicId1 = '';
                $scope.selected.dynamicId2 = '';
                break;
             case 'setname':
                $scope.changeView('');
                $scope.selected.paperset = '';
                $scope.selected.difficulty = '';
                $scope.selected.strand = '';
                $scope.selected.substrand = '';
                $scope.selected.questiontype = '';
                $scope.selected.populatedID = '';
                $scope.selected.dynamicId1 = '';
                $scope.selected.dynamicId2 = '';
                break;
             case 'paperset':
                $scope.changeView('');
                $scope.selected.difficulty = '';
                $scope.selected.strand = '';
                $scope.selected.substrand = '';
                $scope.selected.questiontype = '';
                $scope.selected.populatedID = '';
                $scope.selected.dynamicId1 = '';
                $scope.selected.dynamicId2 = '';
                break;
            case 'difficulty':
                $scope.changeView('');
                $scope.selected.strand = '';
                $scope.selected.substrand = '';
                $scope.selected.questiontype = '';
                $scope.selected.populatedID = '';
                $scope.selected.dynamicId1 = '';
                $scope.selected.dynamicId2 = '';
                var tempDifficulty = $scope.selected.difficulty ? $scope.selected.difficulty : '';
                $scope.selected.populatedID = tempDifficulty+'.';
                break;
            
            case 'strand':
                var filterStrandCode = $filter('filter')($scope.MASTERS.strand[$scope.selected.subject], {id:$scope.selected.strand})[0];
                var filterSubStrandCode = $filter('filter')($scope.MASTERS.substrand[$scope.selected.strand], {id:$scope.selected.substrand})[0];
                $scope.changeView('');
                $scope.selected.substrand = '';
                $scope.selected.questiontype = '';
                $scope.selected.populatedID = '';
                $scope.selected.dynamicId1 = '';
                $scope.selected.dynamicId2 = '';
                var tempDifficulty = $scope.selected.difficulty ? $scope.selected.difficulty : '';
                var tempStrand = filterStrandCode.code ? filterStrandCode.code : '';
                var tempSubStrand = '';
                $scope.selected.populatedID = tempDifficulty+'.'+tempStrand+tempSubStrand;
                break;
                
            case 'substrand':
                var filterStrandCode = $filter('filter')($scope.MASTERS.strand[$scope.selected.subject], {id:$scope.selected.strand})[0];
                var filterSubStrandCode = $filter('filter')($scope.MASTERS.substrand[$scope.selected.strand], {id:$scope.selected.substrand})[0];
                $scope.changeView('');
                $scope.selected.questiontype = '';
                $scope.selected.populatedID = '';
                $scope.selected.dynamicId1 = '';
                $scope.selected.dynamicId2 = '';
                var tempDifficulty = $scope.selected.difficulty ? $scope.selected.difficulty : '';
                var tempStrand = filterStrandCode.code ? filterStrandCode.code : '';
                var tempSubStrand = filterSubStrandCode.code ? filterSubStrandCode.code : '';
                $scope.selected.populatedID = tempDifficulty+'.'+tempStrand+tempSubStrand;
                break;
        };
        
    };
    
    $scope.refreshQuestionSection  = function ( navigateTo, changeFrom ){
        console.log( navigateTo, changeFrom );        
        if( changeFrom === $scope.MASTERS.keywords['submitaction3'] && $scope.selected.keystage !== '' ){
            var modalInstanceConfirm = $modal.open({
                templateUrl: 'confirmPopUpBack',
                controller: 'confirmPopUpBackCtrl',
                size: 'sm',
                scope: $scope
            });
            
            modalInstanceConfirm.result.then(function () {
                $timeout(function () {
                    if( navigateTo === $scope.MASTERS.keywords['submitaction1'] ){
                        //Forcelly redirect to question list view.
                        window.location.href = window.location.href.split('questionbuilder')[0]+'questionbuilder';
                    } else {
                        $scope.changeView($scope.selected.questiontype);  
                        $route.reload();
                    }
                }, 1000)
                console.log('Modal close at: ' + new Date());
            }, function () {
                console.log('Modal dismissed at: ' + new Date());
            });
            
        } else {
            $timeout(function () {
                if( navigateTo === $scope.MASTERS.keywords['submitaction1'] ){
                    //Forcelly redirect to question list view.
                    window.location.href = window.location.href.split('questionbuilder')[0]+'questionbuilder';
                } else {
                    $scope.changeView($scope.selected.questiontype);  
                    $route.reload();
                }
            }, 1000);
        }
        
    };
   
    //console.log( $('question_type_id') );
    $timeout(function () {
        window.parent.$('#question_type_id').change(function () {
            var routeVal = $(this).val();
            window.parent.$('#questionbuilderId').attr('src', '/qusbuilder/#/' + routeVal);
        });
    }, 1000);
    
    $scope.bindSetName = function( arr, tempId ){
        var result = $.grep(arr , function(e){ return e.id === tempId; });         
        if ( result.length === 0 ) {
            $scope.returnSetName = '';
        } else {
            $scope.returnSetName = result[0].set_name;
        }
        return $sce.trustAsHtml( $scope.returnSetName );
    };
    
    $scope.bindStrand = function( arr, tempId ){
        $scope.returnStrand = '';
        if( arr ){
            var result = $.grep(arr , function(e){ return e.id === tempId; });         
            if ( result.length === 0 ) {
                $scope.returnStrand = '';
            } else {
                $scope.returnStrand = result[0].name;
            }
        }
        
        
        return $sce.trustAsHtml( $scope.returnStrand );
    };
    
    $scope.bindSubstrand = function( arr, tempId ){
        $scope.returnSubstrand = '';
        if( arr ){
            var result = $.grep(arr , function(e){ return e.id === tempId; });         
            if ( result.length === 0 ) {
                $scope.returnSubstrand = '';
            } else {
                $scope.returnSubstrand = result[0].name;
            }
        }
        return $sce.trustAsHtml( $scope.returnSubstrand );
    };
    
    $scope.bindSelectQuestion = function( tempId ){
        var result = $.grep($rootScope.MASTERS.questiontype , function(e){ return e.id === tempId; });         
        if ( result.length === 0 ) {
            $scope.returnSelQuesType = '';
        } else {
            $scope.returnSelQuesType = result[0].name;
        }
        return $sce.trustAsHtml( $scope.returnSelQuesType );
    };
    
    $scope.changeView = function (gotoVal) {
        $scope.questionData = '';
        console.log('goto route', gotoVal.toString());
        switch (gotoVal.toString()) {
            case '1':
                $location.path('/singlechoice');
                break;
            case '2':
                $location.path('/multiplechoice');
                break;
            case '3':
                $location.path('/fillintheblanks');
                break;
            case '4':
                $location.path('/matchdragdrop');
                break;
            case '6':
                $location.path('/simplequestion');
                break;
            case '7':
                $location.path('/insertliteracy');
                break;
            case '8':
                $location.path('/spellingaudio');
                break;                
            case '14':
                $location.path('/selectliteracy');
                break;
            case '15':
                $location.path('/labelliteracy');
                break;
            case '20':
                $location.path('/measurement');
                break;
            case '16':
                $location.path('/wordonimage');
                break;
            case '9':
                $location.path('/underlineliteracy');
                break;
            case '12':
                $location.path('/numberwordselect');
                break;
            case '10':
                $location.path('/singlemultipleentry');
                break;
            case '11':
                $location.path('/dragdrop');
                break;
            case '13':
                $location.path('/boolean');
                break;
            case '17':
                //$location.path('/reflection');
                $location.path('/reflectionrightleft');
                break;
            case '18':
                $location.path('/joiningdots');
                break;                
            case '19':
                $location.path('/drawlineonimage');
                break;
            case '22':
                $location.path('/drawinggraph');
                break;
            case '21':
                $location.path('/shadingshape');
                break;
            case '23':
                $location.path('/reflectionleftright');
                break;
            case '24':
                $location.path('/reflectionbottomtop');
                break;    
            case '25':
                $location.path('/reflectiontopbottom');
                break;
            case '26':
                $location.path('/reflectionleftdiagonal');
                break; 
            case '27':
                $location.path('/reflectionrightdiagonal');
                break; 
            case '28':
                $location.path('/measurementlineangle');
                break; 
            case '29':
                $location.path('/piechart');
                break;
            case '30':
                $location.path('/symmetric');
                break;
            case '31':
                $location.path('/inputonimage');
                break;
            case '32':
                $location.path('/tableinputentry');
                break;
            default :
                $location.path('/');
                break;
        }
    };
    $scope.changeView('');
    
    $timeout(function(){
        $('#startquetionbuilder').css('display','block');
        MathJax.Hub.Config({
            skipStartupTypeset: true,
            messageStyle: "none",
            "HTML-CSS": {
                showMathMenu: false
            }
        });
        MathJax.Hub.Configured();
    });

});

app.controller('confirmPopUpBackCtrl', function ( $scope, $modalInstance ) {

    $scope.ok = function () {
        $modalInstance.close();
    };
    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };

});

app.filter('to_trusted', function ($sce) {
    return function (text) {
        MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
        return $sce.trustAsHtml(text);
    };
});

app.controller( 'confirmCtrl' , function( $scope, $modalInstance ){
    
    $scope.ok = function () {
        $modalInstance.close('confirm');
    };
    $scope.cancel = function () {
        $modalInstance.dismiss('cancel','cancel');
    };
});

app.controller( 'confirmvalidatorCtrl' , function( $scope, $modalInstance ){
    
    $scope.ok = function () {
        $modalInstance.close('confirm');
    };
    $scope.cancel = function () {
        $modalInstance.dismiss('cancel','cancel');
    };
});

app.directive('vldMaxLength', function () {
    return function (scope, element, attr) {
        element.bind('keydown keypress', function (event) {
            if (event.target.value.length >= attr.vldMaxLength && (event.which !== 8 && event.which !== 46)) {
                event.preventDefault();
            }
        });
    };
});

app.directive('myRepeatDirective', function() {
  return function(scope, element, attrs) {
    if (scope.$last){
        setTimeout(function(){
            var quesId= element[0].id;
            var tempheight = '';
            $.each( $($('#setheight'+quesId).find('.person')), function( index, ele ){
                if( tempheight < $(ele).height()){
                    tempheight = $(ele).height();
                    $($('#setheight'+quesId).find('.person')).css('min-height', tempheight);
                }                
            });     
        },200);
    }
  };
});
app.directive('myRepeatDirectiveanswer', function() {
  return function(scope, element, attrs) {
    if (scope.$last){
        setTimeout(function(){
            var quesId= element[0].id;
            var tempheight = '';
            $.each( $($('#setheight'+quesId).find('.person')), function( index, ele ){
                if( tempheight < $(ele).height()){
                    tempheight = $(ele).height();
                    $($('#setheight'+quesId).find('.person')).css('min-height', tempheight);
                }                
            });     
        },200);
    }
  };
});
app.directive('myRepeatDirective2', function() {
  return function(scope, element, attrs) {
    if (scope.$last){
        setTimeout(function(){
            var quesId= element[0].id;
            var tempheight = '';
            $.each( $($('#setheight'+quesId).find('.custom-drag-ele')), function( index, ele ){
                if( tempheight < $(ele).height()){
                    tempheight = $(ele).height();
                    $($('#setheight'+quesId).find('.custom-drag-ele')).css('min-height', tempheight);
                    $($('#setheight'+quesId).find('.sorting-answer')).css('min-height', tempheight);
                }                
            });     
        },200);
    }
  };
});
