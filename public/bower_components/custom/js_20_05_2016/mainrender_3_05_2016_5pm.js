/**
 * Created by sunny.rana on 22-10-2015.
 */
'use strict';

/**
 * @ngdoc function
 * @name questionBuilder.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the questionBuilder
 */
var app = angular.module('quesRender', ['ngAnimate', 'ngRoute', 'ui.bootstrap', 'flash', 'easypiechart']);

/*Config: Here we set routing start*/
app.config(function ($routeProvider, $modalProvider, $interpolateProvider,$httpProvider) {
    $interpolateProvider.startSymbol('[%');
    $interpolateProvider.endSymbol('%]');
    $routeProvider
            .when('/singlechoicerender', {
                templateUrl: '/qb_singlechoicerender'
            }).when('/fillintheblanksrender', {
                templateUrl: '/qb_fillintheblanksrender'
            }).when('/multiplechoicerender', {
                templateUrl: '/qb_multiplechoicerender'
            }).when('/matchdragdroprender', {
                templateUrl: '/qb_matchdragdroprender'
            }).when('/measurementrender', {
                templateUrl: '/qb_measurementrender'
            }).when('/simplequestionrender', {
                templateUrl: '/qb_simplequestionrender'
            }).when('/selectliteracyrender', {
                templateUrl: '/qb_selectliteracyrender'
            }).when('/labelliteracyrender', {
                templateUrl: '/qb_labelliteracyrender'
            }).when('/insertliteracyrender', {
                templateUrl: '/qb_insertliteracyrender'
            }).when('/wordonimagerender', {
                templateUrl: '/qb_wordonimagerender'
            }).when('/underlineliteracyrender', {
                templateUrl: '/qb_underlineliteracyrender'
            }).when('/numberwordselectrender', {
                templateUrl: '/qb_numberwordselectrender'
            }).when('/singlemultipleentryrender', {
                templateUrl: '/qb_singlemultipleentryrender'
            }).when('/dragdroprender', {
                templateUrl: '/qb_dragdroprender'
            }).when('/drawinggraphrender', {
                templateUrl: '/qb_drawinggraphrender'
            }).when('/booleanrender', {
                templateUrl: '/qb_booleanrender'
            }).when('/spellingaudiorender', {
                templateUrl: '/qb_spellingaudiorender'
            }).when('/drawlineonimagerender', {
                templateUrl: '/qb_drawlineonimagerender'
            }).when('/joiningdotsrender', {
                templateUrl: '/qb_joiningdotsrender'
            }).when('/reflectionrightleftrender', {
                templateUrl: '/qb_reflectionrightleftrender'
            }).when('/reflectionleftrightrender', {
                templateUrl: '/qb_reflectionleftrightrender'
            }).when('/reflectionbottomtoprender', {
                templateUrl: '/qb_reflectionbottomtoprender'
            }).when('/reflectiontopbottomrender', {
                templateUrl: '/qb_reflectiontopbottomrender'
            }).when('/shadingshaperender', {
                templateUrl: '/qb_shadingshaperender'
            }).when('/reflectionleftdiagonalrender', {
                templateUrl: '/qb_reflectionleftdiagonalrender'
            }).when('/reflectionrightdiagonalrender', {
                templateUrl: '/qb_reflectionrightdiagonalrender'
            }).when('/measurementlineanglerender', {
                templateUrl: '/qb_measurementlineanglerender'
            }).when('/piechartrender', {
                templateUrl: '/qb_piechartrender'
            }).when('/symmetricrender', {
                templateUrl: '/qb_symmetricrender'
            }).when('/inputonimagerender', {
                templateUrl: '/qb_inputonimagerender'
            }).when('/tableinputentryrender', {
                templateUrl: '/qb_tableinputentryrender'
            });
            $httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            
    //modal popup turn it off globally here!.
    $modalProvider.options.animation = true;
});
/*Config: Here we set routing End*/

app.directive('myRepeatDirective', function() {
  return function(scope, element, attrs) {
    if (scope.$last){
        setTimeout(function(){
            var quesId= element[0].id.split('_')[2];
            var tempheight = '';
            $.each( $($('#setheight'+quesId).find('.person')), function( index, ele ){
                if( tempheight <   $(ele).height()){
                    tempheight = $(ele).height();
                    $($('#setheight'+quesId).find('.person')).css('min-height', tempheight);
                }                
            });            
        });      
    }
  };
});

app.directive('myRepeatDirective2', function() {
  return function(scope, element, attrs) {
    if (scope.$last){
        setTimeout(function(){
            var quesId= element[0].id.split('_')[1];
            var tempheight = '';
            $.each( $($('#setheight'+quesId).find('.custom-drag-ele')), function( index, ele ){
                if( tempheight <   $(ele).height()){
                    tempheight = $(ele).height();
                    $($('#setheight'+quesId).find('.custom-drag-ele')).css('min-height', tempheight);
                    $($('#setheight'+quesId).find('.sorting-answer')).css('min-height', tempheight);
                }                
            });     
        });
    }
  };
});

/*Controller: Question render parent section start*/
app.controller('renderCtrl', function ( $scope, $rootScope, $location, $filter, $sce, $timeout, $route, $modal, $interval, $http ) {
  
    $scope.currentQues = 1; 
    $scope.checkInterpopup = false;
    $scope.showSubmitBtn = false;
 
    $rootScope.api = {
        'userresponse' : '/examination/questionattempt',
        'submitaction' : '/examination/questionattemptcomplete'
    };
    $rootScope.MASTERS = {}
    $rootScope.MASTERS.keywords = {
        'forcellysubmited' : 'Time is over. You will be redirected to result page.',
        'usersubmitted' : 'Are you sure you want to submit this test paper?',
        'forcellysubmitedheader' : 'Note',
        'usersubmittedheader' : 'Please Confirm!'
    };
    
    $rootScope.nicescroll = {
        cursorwidth: "10px"
    };
    
    $scope.gotohome = function(){
        $rootScope.$broadcast("SUBMIT_RESPONSE");    
        window.location.href = window.location.href.split('examination')[0];  
    };
    
    $scope.navigateToQues = function( action, curQues ){ 
        if(action === 'next' || action === 'link'){
            if( action === 'link' &&  $rootScope.renderQuestion[curQues-1].attempstatus === 'pending' ){
            } else {
            $rootScope.$broadcast("SUBMIT_RESPONSE");
            $("#answer_next_question").fadeOut(500);
            $("#answer_next_question").fadeIn(3000);
                if( $scope.currentQues === $rootScope.renderQuestion.length && action !== 'link'){
                    $scope.userSubmitText();
                }
                $scope.currentQues = curQues > $rootScope.renderQuestion.length ? parseInt( curQues, 10 ) - 1 : parseInt( curQues, 10 );
            }
            
        }else{
            $rootScope.$broadcast("SUBMIT_RESPONSE");
            $scope.currentQues = curQues === 0 ? parseInt( curQues, 10 ) + 1 : parseInt( curQues, 10 );        
        }
        $timeout(function(){ 
            $scope.changeView(parseInt( $rootScope.renderQuestion[$scope.currentQues-1].questiontype, 10 ), $scope.currentQues);       
        },1000);
    };
      
    $scope.changeView = function (gotoVal, curQues) {        
        $scope.gotoVal = gotoVal;
        $scope.currentQues = curQues;
        /*Route here.*/
        switch ( parseInt( $scope.gotoVal, 10 )) {
            case 1:
                $location.path('/singlechoicerender');
                $route.reload(); 
                break;
            case 2:
                $location.path('/multiplechoicerender');
                $route.reload(); 
                break;
            case 3:
                $location.path('/fillintheblanksrender');
                $route.reload(); 
                break;
            case 4:
                $location.path('/matchdragdroprender');
                $route.reload(); 
                break;
            case 6:
                $location.path('/simplequestionrender');
                $route.reload(); 
                break;
            case 7:
                $location.path('/insertliteracyrender');
                $route.reload(); 
                break;
            case 8:
                $location.path('/spellingaudiorender');
                $route.reload(); 
                break;                
            case 14:
                $location.path('/selectliteracyrender');
                $route.reload(); 
                break;
            case 15:
                $location.path('/labelliteracyrender');
                $route.reload(); 
                break;
            case 20:
                $location.path('/measurementrender');
                $route.reload(); 
                break;
            case 16:
                $location.path('/wordonimagerender');
                $route.reload(); 
                break;
            case 9:
                $location.path('/underlineliteracyrender');
                $route.reload(); 
                break;
            case 12:
                $location.path('/numberwordselectrender');
                $route.reload(); 
                break;
            case 10:
                $location.path('/singlemultipleentryrender');
                $route.reload(); 
                break;
            case 11:
                $location.path('/dragdroprender');
                $route.reload(); 
                break;
            case 22:
                $location.path('/drawinggraphrender');
                $route.reload(); 
                break;
            case 13:
                $location.path('/booleanrender');
                $route.reload(); 
                break;
            case 17:
                $location.path('/reflectionrightleftrender');
                $route.reload(); 
                break;
            case 18:
                $location.path('/joiningdotsrender');
                $route.reload(); 
                break;
            case 19:
                $location.path('/drawlineonimagerender');
                $route.reload(); 
                break; 
             case 21:
                $location.path('/shadingshaperender');
                $route.reload(); 
                break;  
            case 22:
                $location.path('/drawinggraphrender');
                $route.reload(); 
                break;
            case 23:
                $location.path('/reflectionleftrightrender');
                $route.reload(); 
                break;
            case 24:
                $location.path('/reflectionbottomtoprender');
                $route.reload(); 
                break;
            case 25:
                $location.path('/reflectiontopbottomrender');
                $route.reload(); 
                break;
            case 26:
                $location.path('/reflectionleftdiagonalrender');
                $route.reload(); 
                break; 
            case 27:
                $location.path('/reflectionrightdiagonalrender');
                $route.reload(); 
                break; 
            case 28:
                $location.path('/measurementlineanglerender');
                $route.reload(); 
                break; 
            case 29:
                $location.path('/piechartrender');
                $route.reload(); 
                break; 
            case 30:
                $location.path('/symmetricrender');
                $route.reload(); 
                break; 
            case 31:
                $location.path('/inputonimagerender');
                $route.reload();
                break;
            case 32:
                $location.path('tableinputentryrender');
                $route.reload();
                break;
            default :
                $location.path('/');
                $route.reload(); 
                break;
        }
    };
    
    
    $scope.setDefaultValue = function( valObj ){
        console.log( valObj );
        $rootScope.renderQuestion = valObj.questions;
        $rootScope.quessummary = valObj.questionsummary;
        $rootScope.MASTERS.keywords['usersubmitted'] = valObj.questionsummary.task_type == 'Test' ? 'Are you sure you want to submit this test paper?' : 'Are you sure you want to submit this revision?';
        $timeout(function(){
            $scope.changeView( parseInt( $rootScope.renderQuestion[0].questiontype, 10 ), $scope.currentQues);
        },1000);        
    };
    
    $scope.forcellySubmitTest = function(){
        $rootScope.$broadcast("SUBMIT_RESPONSE");          
        var modalInstanceConfirm = $modal.open({
               templateUrl: 'popUpforcellySubmit',
               controller: 'popUpforcellySubmitCtrl',
               size: 'sm',
               scope: $scope
           });

        modalInstanceConfirm.result.then(function () {
            console.log('Modal close at: ' + new Date());
        }, function () {
            var tempJson = {
                'action' : 'completed',
                'mode' : 'timeout',
                'attemptid' : angular.copy( $rootScope.quessummary.attemptid ),
                'task_type' : angular.copy( $rootScope.quessummary.task_type )
            };
            if( $rootScope.quessummary.is_complete_attempt !== 'undefined' ){
                tempJson.is_complete_attempt = angular.copy( $rootScope.quessummary.is_complete_attempt )
            }
            
            $http.post($rootScope.api['submitaction'], {data: angular.copy(tempJson)}).then(function ( response ) {
                if( response.data.questionReference ){
                    window.location.href = response.data.questionReference;
                }
            }, function (response) {});            
            console.log('Modal dismissed at: ' + new Date());
        });
    };
    
    $scope.userSubmitText = function(){
        //$rootScope.$broadcast("SUBMIT_RESPONSE");          
        var modalInstanceConfirm = $modal.open({
               templateUrl: 'popUpUserSubmit',
               controller: 'popUpUserSubmitCtrl',
               size: 'sm',
               scope: $scope
           });

        modalInstanceConfirm.result.then(function () {
            var tempJson = {
                'action' : 'completed',
                'mode' : 'self',
                'attemptid' : angular.copy( $rootScope.quessummary.attemptid ),
                'task_type' : angular.copy( $rootScope.quessummary.task_type )
            };
            if( $rootScope.quessummary.is_complete_attempt !== 'undefined' ){
                tempJson.is_complete_attempt = angular.copy( $rootScope.quessummary.is_complete_attempt )
            }
            
            $http.post($rootScope.api['submitaction'], {data: angular.copy(tempJson)}).then(function ( response ) {
                if( response.data.questionReference ){
                    window.location.href = response.data.questionReference;
                }
            }, function (response) {});            
            console.log('Modal close at: ' + new Date());
        }, function () {
            console.log('Modal dismissed at: ' + new Date());
        });
    };
    
    $scope.viewInstructionText = function(){ 
        var instructionDivId = 'instructionPopUp'+$rootScope.renderQuestion[$scope.currentQues-1].questiontype
        var modalInstanceConfirm = $modal.open({
               templateUrl: instructionDivId,
               controller: 'instructionPopUpCtrl',
               size: 'lg',
               scope: $scope,
                resolve: {
                    questionPre: function () {
                        return {
                            'assignment': $rootScope.renderQuestion[$scope.currentQues-1],
                            'questionindex': $scope.currentQues-1
                        };
                    }
                }
           });

        modalInstanceConfirm.result.then(function () {
            console.log('Modal close at: ' + new Date());
        }, function () {
            console.log('Modal dismissed at: ' + new Date());
        });
    };
    
    
    $scope.viewPressContinueInstructionText = function(){
        var modalInstanceConfirm = $modal.open({
               templateUrl: 'pressContinueinstructionPopUp',
               controller: 'pressContinueinstructionPopUpCtrl',
               scope: $scope
           });

        modalInstanceConfirm.result.then(function () {
            console.log('Modal close at: ' + new Date());
        }, function () {
            console.log('Modal dismissed at: ' + new Date());
        });
    };
    
    $scope.setintervalTime = function (){
        if( ($rootScope.quessummary.remainingtime > $rootScope.quessummary.quesmaxtime || $rootScope.quessummary.remainingtime === 0) && !$scope.checkInterpopup ){
            $scope.checkInterpopup = true;
            $('.bar_remain').width('100%');    
            $('.bar_remain').css('background','red');
            //$scope.forcellySubmitTest();
            return;
        }
        if( !$scope.checkInterpopup ){
            $rootScope.quessummary.remainingtime = $rootScope.quessummary.remainingtime - 1;
            $('.bar_remain').width(100 - (($rootScope.quessummary.remainingtime / $rootScope.quessummary.quesmaxtime) * 100)+'%');    
        } else {
            $('.bar_remain').css('background','red');
            if($rootScope.quessummary.remainingtime === 0){
                $rootScope.quessummary.remainingtime = $rootScope.quessummary.quesmaxtime;
            }
            $rootScope.quessummary.remainingtime = $rootScope.quessummary.remainingtime + 1;
            
        }        
    };
    $interval($scope.setintervalTime, 1000);
    
    $( window ).resize(function() {
        $("#viewpanel").css( 'height', $(window).height() - ($('.save_cont').height() + $('#topsection').height() + $('.timerBar ').height() + $('.qNumList ').height() +  100));
        $("#viewpanel").css( 'width',  $(".qNumList").width() + 40 );
        if ( $("#viewpanel").getNiceScroll().length ) {
            $("#viewpanel").getNiceScroll().resize();
        }
        $("#viewpanel").niceScroll($rootScope.nicescroll);        
    });
    
    $timeout(function(){
       $('#quetionrendering').css('display','block'); 
    },100);
    
    $(document).keyup(function (e) {
        var key = e.which;
        if(key == 13)  // the enter key code
        {
           $scope.viewPressContinueInstructionText();
        }
    }); 
    
});
/*Controller: Question render parent section end*/

/*Popup controller section start*/
app.controller('instructionPopUpCtrl', function ($scope, $modalInstance, questionPre) {
    $scope.quesIndex = questionPre.questionindex;
    $scope.quesArray = questionPre.assignment;
    
    $scope.ok = function () {
        //$modalInstance.close();
        $modalInstance.dismiss('cancel');
    };

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };
});

app.controller('pressContinueinstructionPopUpCtrl', function ($scope, $modalInstance) {
    
    
    $scope.ok = function () {
        //$modalInstance.close();
        $modalInstance.dismiss('cancel');
    };

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };
});

app.controller('popUpforcellySubmitCtrl', function ($scope, $modalInstance) {
    $scope.ok = function () {
        $modalInstance.dismiss('cancel');
    };
});

app.controller('popUpUserSubmitCtrl', function ( $scope, $modalInstance ) {

    $scope.ok = function () {
        $modalInstance.close();
        //$modalInstance.dismiss('cancel');
    };

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };

});
/*Popup controller section end*/


/*Filter: For pass trusted html and mathjax text render start*/
app.filter('to_trusted', function ($sce) {
    return function (text) {
        MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
        return $sce.trustAsHtml(text);
    };
});
app.filter('character',function(){
    return function(input){
        return String.fromCharCode(64 + parseInt(input,10)).toLowerCase();
    };
});
/*Filter: For pass trusted html and mathjax text render end*/

/*Filter: convert seconds to hours:minutes:seconds start*/
app.filter('duration', function( $sce ) {
    //Returns duration from secs in hh:mm:ss format.
      return function(secs) {
        var hr = Math.floor(secs / 3600);
	var min = Math.floor((secs - (hr * 3600))/60);
	var sec = secs - (hr * 3600) - (min * 60);
	//while (min.length < 2) {min = '0' + min;}
	//while (sec.length < 2) {sec = '0' + min;}
        if( min <= 9 ) {min = '0'+min;}
        if( hr <= 9 ) {hr = '0'+hr;}
        if( sec <= 9 ) {sec = '0'+sec;}        
	hr += ':';
	return $sce.trustAsHtml(hr+min+':'+sec);
    }
});
/*Filter: convert seconds to hours:minutes:seconds end*/

/*Controller: Question render section start*/
app.controller('multipleChoiceRenderCtrl', function ( $scope, $serverRequest, $timeout, $rootScope ) {
    
    if(!$rootScope.renderQuestion[$scope.currentQues-1].userresponse){    
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse = [];
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = [];
            angular.forEach(quesEle.option, function (optionEle, optionIndex) {        
                $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].push({ischeck:false});
            });

        });
    }
    
    $scope.$on("SUBMIT_RESPONSE",function () {
        
        $scope.check = {'attempted' :0,'notattempted' :0};
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {       
            var result = $.grep($rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] , function(e){ return e.ischeck == true; });            
            if ( result.length !== 0 ) {
                $scope.check.attempted = $scope.check.attempted + 1;
            } else {
                $scope.check.notattempted = $scope.check.notattempted + 1;
            }

        });
        
        if( $scope.check.notattempted ===  $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'skipped';
        } else if( $scope.check.attempted === $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'complete';
        } else {
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'inprogress';
        }
        
        var json = {
            'id': $rootScope.renderQuestion[$scope.currentQues-1].id,
            'attempstatus':$rootScope.renderQuestion[$scope.currentQues-1].attempstatus,
            'userresponse':$rootScope.renderQuestion[$scope.currentQues-1].userresponse,
            'attempt_id':$rootScope.renderQuestion[$scope.currentQues-1].attempt_id,
            'num_question':$rootScope.renderQuestion[$scope.currentQues-1].num_question
        };        
        $serverRequest.postData.saveUserResponse( json );
        
    });
    
    $timeout(function(){
       $(window).trigger('resize');
    });
    
});

app.controller('singleChoiceRenderCtrl', function ( $scope, $serverRequest, $timeout, $rootScope ) {
    
    $scope.correctanswer = function( quesIndex, corretIndex ){
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = corretIndex;
    };
    
    if(!$rootScope.renderQuestion[$scope.currentQues-1].userresponse){
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse = [];  
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = '';        
        });    
    }   
    
    $scope.$on("SUBMIT_RESPONSE",function () {
        $scope.check = {'attempted' :0,'notattempted' :0};
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {       
            if ( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] !== '' ) {
                $scope.check.attempted = $scope.check.attempted + 1;
            } else {
                $scope.check.notattempted = $scope.check.notattempted + 1;
            }
        });
        
        if( $scope.check.notattempted ===  $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'skipped';
        } else if( $scope.check.attempted === $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'complete';
        } else {
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'inprogress';
        }
        
        var json = {
            'id': $rootScope.renderQuestion[$scope.currentQues-1].id,
            'attempstatus':$rootScope.renderQuestion[$scope.currentQues-1].attempstatus,
            'userresponse':$rootScope.renderQuestion[$scope.currentQues-1].userresponse,
            'attempt_id':$rootScope.renderQuestion[$scope.currentQues-1].attempt_id,
            'num_question':$rootScope.renderQuestion[$scope.currentQues-1].num_question
        };        
        $serverRequest.postData.saveUserResponse( json );
        
    });
    
    $timeout(function(){
        $(window).trigger('resize');
    });
    
});

app.controller('matchdragdropRenderCtrl', function ($scope, $serverRequest, $timeout, $rootScope) {
        
    var myLines = [], svg = [];
    $scope.leftPersons = [];
    $scope.rightPersons = [];
    $scope.mappingResult = [];
    
    angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function(ele, index){
        $scope.leftPersons[index] = [];
	$scope.rightPersons[index] = [];
        angular.forEach(ele.option, function(ele, childIndex){
            $scope.leftPersons[index].push({id: index,name: ele.left,cls: 'draggable'});
            $scope.rightPersons[index].push({id: index,name: ele.right,cls: 'droppable'});
	}); 
    });
    
        
    if(!$rootScope.renderQuestion[$scope.currentQues-1].userresponse){
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse = [];
        $timeout(function(){
            angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
                $scope.initialize(quesIndex, 'new');
                $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = [];
                angular.forEach(quesEle.option, function (optionEle, optionIndex) {        
                    $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].push({source:'', target:''});        
                });
            }); 
        },1000);
        
    } else {
        $timeout(function(){
            angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
                console.log( "calling initialize..." );
                $scope.initialize(quesIndex, 'edit');
            });  
        },1000);
    }   
    
    $scope.$on("SUBMIT_RESPONSE",function () {
        $scope.check = {'attempted' :0,'notattempted' :0};
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {       
            var result = $.grep($rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] , function(e){ return e.source != ''; });            
            if ( result.length !== 0 ) {
                $scope.check.attempted = $scope.check.attempted + 1;
            } else {
                $scope.check.notattempted = $scope.check.notattempted + 1;
            }
        });
        
        if( $scope.check.notattempted ===  $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'skipped';
        } else if( $scope.check.attempted === $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'complete';
        } else {
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'inprogress';
        }
        
        var json = {
            'id': $rootScope.renderQuestion[$scope.currentQues-1].id,
            'attempstatus':$rootScope.renderQuestion[$scope.currentQues-1].attempstatus,
            'userresponse':$rootScope.renderQuestion[$scope.currentQues-1].userresponse,
            'attempt_id':$rootScope.renderQuestion[$scope.currentQues-1].attempt_id,
            'num_question':$rootScope.renderQuestion[$scope.currentQues-1].num_question
        };        
        $serverRequest.postData.saveUserResponse( json );
        
    });
    
    $timeout(function(){
        $(window).trigger('resize');        
    });
    
    $scope.initialize = function( parentIndex, mode ){
        // set up the drawing area from Body of the document
        console.log("calling function..");
        myLines[parentIndex] = [];
        $("#svgbasics"+parentIndex).css("height", $("#pnlAllIn"+parentIndex).height()).css("width", $("#pnlAllIn"+parentIndex).width());
        
        // all draggable elements
        $("div .draggable").draggable({revert:true, snap: false, cursor: 'crosshair',
            start: function( event, ui ){
                $(this).addClass("custom-drag-ele");
            },stop: function ( event, ui ) {
                $(this).removeClass("custom-drag-ele");
            }
        });
        
        // all droppable elements
        $("div .droppable").droppable({hoverClass: "ui-state-hover",cursor: "move",
            drop: function(event, ui){    
                // disable it so it can"t be used anymore		
                $(this).droppable("disable");
                var pindex = $(this).attr('id').split("_")[2];                
                var optionindex = $(this).attr('id').split("_")[1]; 
                var sourceValue = $(ui.draggable).attr("id").split("_")[1];
                var targetValue = optionindex;
                // change the input element to contain the mapping target and source
                $(ui.draggable).find("input:hidden").val(sourceValue + "_" + targetValue);
                $rootScope.renderQuestion[$scope.currentQues-1].userresponse[pindex][optionindex].source = sourceValue;
                $rootScope.renderQuestion[$scope.currentQues-1].userresponse[pindex][optionindex].target = targetValue;
                // disable it so it can"t be used anymore	
                $(ui.draggable).draggable("disable");
                
                svgDrawLine($(this), $(ui.draggable), pindex);
            }
        });
	if( $('#svgbasics'+parentIndex).find('svg').length ){
            $('#svgbasics'+parentIndex).find('svg').remove();
        }
        svg[parentIndex]= Raphael("svgbasics"+parentIndex, $("#svgbasics"+parentIndex).width(), $("#svgbasics"+parentIndex).height());
        if( mode === 'edit'){
            angular.forEach( $rootScope.renderQuestion[$scope.currentQues-1].questions[parentIndex].option, function (optionEle, optionIndex) {        
                var userresponsetext = $rootScope.renderQuestion[$scope.currentQues-1].userresponse[parentIndex][optionIndex];
                if( userresponsetext.source && userresponsetext.target ){
                    var sourceValue = $('#l_'+userresponsetext.source+'_'+parentIndex);
                    var targetValue = $('#r_'+userresponsetext.target+'_'+parentIndex);
                    sourceValue.find("input:hidden").val(userresponsetext.source + "_" + userresponsetext.target);
                    svgDrawLine(targetValue, sourceValue, parentIndex);
                }
            });
        }
    };
    
    $scope.clearMapping = function(pindex){
        
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse[pindex] = [];
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions[pindex].option, function (optionEle, optionIndex) {        
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[pindex].push({source:'', target:''});   
            console.log( optionIndex , $rootScope.renderQuestion[$scope.currentQues-1].questions[pindex].option.length );
            if( optionIndex === ( $rootScope.renderQuestion[$scope.currentQues-1].questions[pindex].option.length - 1 ) ){
                $scope.navigateToQues('', $scope.currentQues );
            }
        });	
    };
	
    $scope.svgClear = function( index ){
        svg[index].clear();
    };
    
    function svgDrawLine(eTarget, eSource, parentIndex){
        // wait 1 sec before draw the lines, so we can get the position of the draggable
        $timeout(function(){
            var $source = eSource;
            var $target = eTarget;
            var originX = ($source.offset().left + $source.width() + 48) - $('#pnlAllIn'+parentIndex).offset().left;
            var originY = ($source.offset().top + (($source.height() + 4 ) / 2)) - $('#pnlAllIn'+parentIndex).offset().top;
            var endingX = ($target.offset().left + 46) - $('#pnlAllIn'+parentIndex).offset().left;
            var endingY = ($target.offset().top + (($target.height() + 4 ) / 2)) - $('#pnlAllIn'+parentIndex).offset().top;
            
            var space = 20;            
            var a = "M" + originX + " " + originY + " L" + (originX + space) + " " + originY; // beginning
            var b = "M" + (originX + space) + " " + originY + " L" + (endingX - space) + " " + endingY; // diagonal line
            var c = "M" + (endingX - space) + " " + endingY + " L" + endingX + " " + endingY; // ending
            var all = a + " " + b + " " + c;
            myLines[parentIndex][myLines.length] = svg[parentIndex].path(all).attr({
                "stroke": 'red',
                "stroke-width": 2,
                "stroke-dasharray": ""
            });
            
        }, 1000);  
        
    }    
});

app.controller('fillintheBlanksRenderCtrl', function ( $scope, $serverRequest, $timeout, $rootScope ) {
    
    if(!$rootScope.renderQuestion[$scope.currentQues-1].userresponse){    
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse = [];
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = [];
            var objQues = $.parseHTML(quesEle.ques);
            if( $(objQues).find('input').length ){
                angular.forEach($(objQues).find('input'), function ( ele, index ) {
                    $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].push({
                        seq: (index + 1),
                        value: ''
                    });
                });
            }
        });        
    } else {
        $timeout(function(){
            angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
                var objQues = $.parseHTML(quesEle.ques);
                if( $(objQues).find('input').length ){
                    angular.forEach($(objQues).find('input'), function ( ele, index ) {
                        $($('#'+quesIndex).find('input')[index]).val( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex][index].value );
                    });
                }
            });   
        })        
    }
    
    $scope.$on("SUBMIT_RESPONSE",function () {
        $scope.check = {'attempted' :0,'notattempted' :0};
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            var objQues = $.parseHTML(quesEle.ques);
            if( $(objQues).find('input').length ){
                angular.forEach($(objQues).find('input'), function ( ele, index ) {
                    $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex][index].value = $($('#'+quesIndex).find('input')[index]).val();
                });
            }
            
            var result = $.grep($rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] , function(e){ return e.value != ''; });            
            if ( result.length !== 0 ) {
                $scope.check.attempted = $scope.check.attempted + 1;
            } else {
                $scope.check.notattempted = $scope.check.notattempted + 1;
            }
        });
        
        if( $scope.check.notattempted ===  $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'skipped';
        } else if( $scope.check.attempted === $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'complete';
        } else {
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'inprogress';
        }
        
        var json = {
            'id': $rootScope.renderQuestion[$scope.currentQues-1].id,
            'attempstatus':$rootScope.renderQuestion[$scope.currentQues-1].attempstatus,
            'userresponse':$rootScope.renderQuestion[$scope.currentQues-1].userresponse,
            'attempt_id':$rootScope.renderQuestion[$scope.currentQues-1].attempt_id,
            'num_question':$rootScope.renderQuestion[$scope.currentQues-1].num_question
        };
        $serverRequest.postData.saveUserResponse( json );
        
    });
    
    $timeout(function(){
        $(window).trigger('resize');
    });
    
});

app.controller('simplequesRenderCtrl', function ( $scope, $serverRequest, $timeout, $rootScope ) {
    
    if(!$rootScope.renderQuestion[$scope.currentQues-1].userresponse){    
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse = [];
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = {value: ''};            
        });        
    }
    
    $scope.$on("SUBMIT_RESPONSE",function () {
        $scope.check = {'attempted' :0,'notattempted' :0};    
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {       
            if ( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].value !== '' ) {
                $scope.check.attempted = $scope.check.attempted + 1;
            } else {
                $scope.check.notattempted = $scope.check.notattempted + 1;
            }

        });
        
        if( $scope.check.notattempted ===  $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'skipped';
        } else if( $scope.check.attempted === $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'complete';
        } else {
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'inprogress';
        }
        
        var json = {
            'id': $rootScope.renderQuestion[$scope.currentQues-1].id,
            'attempstatus':$rootScope.renderQuestion[$scope.currentQues-1].attempstatus,
            'userresponse':$rootScope.renderQuestion[$scope.currentQues-1].userresponse,
            'attempt_id':$rootScope.renderQuestion[$scope.currentQues-1].attempt_id,
            'num_question':$rootScope.renderQuestion[$scope.currentQues-1].num_question
        };
        $serverRequest.postData.saveUserResponse( json );
        
    });
    
    $timeout(function(){
        $(window).trigger('resize');
    });
    
});

app.controller('insertliteracyRenderCtrl', function ( $scope, $serverRequest, $timeout, $rootScope ) {
    
    $scope.dragObjArray = ['.', ',', ':', ';', '?', '!', " ' ", '"', '_', '-', '...', '(', ')'];
    
    if(!$rootScope.renderQuestion[$scope.currentQues-1].userresponse){    
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse = [];
        $timeout(function(){
            angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
                $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = [];
                angular.forEach($('#textEditor'+quesIndex).find('input[name^="qus_ans'+quesIndex+'"]'), function (ele, indexChild) {
                    $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].push('');            
                });
            
            });        
        });
    } else {
        $timeout(function(){
            angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
                angular.forEach($('#textEditor'+quesIndex).find('input[name^="qus_ans'+quesIndex+'"]'), function (ele, indexChild) {
                    $($('#textEditor'+quesIndex).find('input[name^="qus_ans'+quesIndex+'"]')[indexChild]).val($rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex][indexChild]);            
                });

            });        
        });
    }
    
    $scope.bindHtmlText = function( text, parentIndex ){
        return text.split(' ').join('&nbsp;<input type="text" name="qus_ans'+parentIndex+'" class="droppable insert-literacy-text drop-inputbox" >&nbsp;') ;
    };
    
    $scope.$on("SUBMIT_RESPONSE",function () {
        $scope.check = {'attempted' :0,'notattempted' :0};    
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = [];
            angular.forEach($('#textEditor'+quesIndex).find('input[name^="qus_ans'+quesIndex+'"]'), function (ele, indexChild) {
                $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex][indexChild] = $(ele).val();            
            });
            var result = $.grep($rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] , function(e){ return e != ''; });            
            if ( result.length !== 0 ) {
                $scope.check.attempted = $scope.check.attempted + 1;
            } else {
                $scope.check.notattempted = $scope.check.notattempted + 1;
            }

        });
        
        if( $scope.check.notattempted ===  $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'skipped';
        } else if( $scope.check.attempted === $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'complete';
        } else {
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'inprogress';
        }
        
        var json = {
            'id': $rootScope.renderQuestion[$scope.currentQues-1].id,
            'attempstatus':$rootScope.renderQuestion[$scope.currentQues-1].attempstatus,
            'userresponse':$rootScope.renderQuestion[$scope.currentQues-1].userresponse,
            'attempt_id':$rootScope.renderQuestion[$scope.currentQues-1].attempt_id,
            'num_question':$rootScope.renderQuestion[$scope.currentQues-1].num_question
        };
        $serverRequest.postData.saveUserResponse( json );
        
    });
    
    $timeout(function(){
        $(window).trigger('resize');        
        
        $('.draggable').draggable({revert: function(dropped) {return !dropped;}, helper: 'clone', zIndex: 1});
        $('.droppable').droppable({
            hoverClass: 'ui-state-hover',
            drop: function (e, ui) {
                $(this).val($(ui.draggable).clone().html());
            }
        });
        
    });
    
});

app.controller('spellingaudioRenderCtrl', function ( $scope, $serverRequest, $timeout, $rootScope ) {
    
    if(!$rootScope.renderQuestion[$scope.currentQues-1].userresponse){    
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse = [];
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = [];
            var objQues = $.parseHTML(quesEle.ques);
            if( $(objQues).find('input').length ){
                angular.forEach($(objQues).find('input'), function ( ele, index ) {
                    $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].push({
                        seq: (index + 1),
                        value: ''
                    });
                });
            }
        });        
    } else {
        $timeout(function(){
            angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
                var objQues = $.parseHTML(quesEle.ques);
                if( $(objQues).find('input').length ){
                    angular.forEach($(objQues).find('input'), function ( ele, index ) {
                        $($('#'+quesIndex).find('input')[index]).val( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex][index].value );
                    });
                }
            });   
        })        
    }
    
    $scope.$on("SUBMIT_RESPONSE",function () {
        $scope.check = {'attempted' :0,'notattempted' :0};
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            var objQues = $.parseHTML(quesEle.ques);
            if( $(objQues).find('input').length ){
                angular.forEach($(objQues).find('input'), function ( ele, index ) {
                    $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex][index].value = $($('#'+quesIndex).find('input')[index]).val();
                });
            }
            
            var result = $.grep($rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] , function(e){ return e.value != ''; });            
            if ( result.length !== 0 ) {
                $scope.check.attempted = $scope.check.attempted + 1;
            } else {
                $scope.check.notattempted = $scope.check.notattempted + 1;
            }
        });
        
        if( $scope.check.notattempted ===  $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'skipped';
        } else if( $scope.check.attempted === $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'complete';
        } else {
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'inprogress';
        }
        
        var json = {
            'id': $rootScope.renderQuestion[$scope.currentQues-1].id,
            'attempstatus':$rootScope.renderQuestion[$scope.currentQues-1].attempstatus,
            'userresponse':$rootScope.renderQuestion[$scope.currentQues-1].userresponse,
            'attempt_id':$rootScope.renderQuestion[$scope.currentQues-1].attempt_id,
            'num_question':$rootScope.renderQuestion[$scope.currentQues-1].num_question
        };
        $serverRequest.postData.saveUserResponse( json );
        
    });
    
    $timeout(function(){
        $(window).trigger('resize');
    });
    
});

app.controller('underlineliteracyRenderCtrl', function ( $scope, $serverRequest, $timeout, $rootScope ) {
    
    /*Text underline here.*/
    var Selector_Var = {};
    Selector_Var.Selector = {};
    $scope.underlinetext = function () {
        var st = $scope.getSelectionParentElementPre();
        if (st != '') {
            try {
                var newNode = document.createElement("span");
                var range = st.getRangeAt(0);
                newNode.setAttribute("class", "underline");
                range.surroundContents(newNode);
            } catch (e) {
                console.log(e.message);
            }
        }
        st.removeAllRanges();
    };

    $scope.getSelectionParentElementPre = function () {
        var selected_text = '';
        if (window.getSelection) {
            selected_text = window.getSelection();
        } else if (document.getSelection) {
            selected_text = document.getSelection();
        } else if (document.selection) {
            selected_text = document.selection.createRange().text;
        }
        return selected_text;
    };
    
    if(!$rootScope.renderQuestion[$scope.currentQues-1].userresponse){    
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse = [];
        $timeout(function(){
            angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
                $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = {value:''};
                $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].value = quesEle.ques;            
            }); 
            console.log( $rootScope.renderQuestion[$scope.currentQues-1].userresponse );
        })
    }
    
    $scope.$on("SUBMIT_RESPONSE",function () {
        $scope.check = {'attempted' :0,'notattempted' :0};    
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {            
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].value = $('#textEditor'+quesIndex).html();                        
            var result = $.grep($rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] , function(e){ return e != ''; });            
            if ( $rootScope.renderQuestion[$scope.currentQues-1].questions[quesIndex].ques !== $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].value ) {
                $scope.check.attempted = $scope.check.attempted + 1;
            } else {
                $scope.check.notattempted = $scope.check.notattempted + 1;
            }

        });
        
        if( $scope.check.notattempted ===  $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'skipped';
        } else if( $scope.check.attempted === $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'complete';
        } else {
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'inprogress';
        }
        
        var json = {
            'id': $rootScope.renderQuestion[$scope.currentQues-1].id,
            'attempstatus':$rootScope.renderQuestion[$scope.currentQues-1].attempstatus,
            'userresponse':$rootScope.renderQuestion[$scope.currentQues-1].userresponse,
            'attempt_id':$rootScope.renderQuestion[$scope.currentQues-1].attempt_id,
            'num_question':$rootScope.renderQuestion[$scope.currentQues-1].num_question
        };
        $serverRequest.postData.saveUserResponse( json );
        
    });
    
    $scope.resetUnderline = function( parentIndex ){
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse[parentIndex].value = $rootScope.renderQuestion[$scope.currentQues-1].questions[parentIndex].ques;     
        $timeout(function(){
            $scope.navigateToQues('', $scope.currentQues );
        });
    };
    
    $timeout(function(){
        $(window).trigger('resize');      
        
        $('.draggable').draggable({revert: function(dropped) {return !dropped;}, helper: 'clone', zIndex: 1});
        $('.droppable').droppable({
            hoverClass: 'ui-state-hover',
            drop: function (e, ui) {
                $(this).val($(ui.draggable).clone().html());
            }
        });
        
    });
    
});

app.controller('booleanRenderCtrl', function ($scope, $serverRequest, $timeout, $rootScope) {
           
    if(!$rootScope.renderQuestion[$scope.currentQues-1].userresponse){
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse = [];
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = [];
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].push({main:'', justifypart:[]});
            if( quesEle.showsmultiple ){
                angular.forEach(quesEle.elseques, function (elseEle, elseIndex) {        
                    $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex][0].justifypart.push({value: elseEle.value, ischeck: false});        
                });
            }

        }); 
    }
    
    $scope.$on("SUBMIT_RESPONSE",function () {
        $scope.check = {'attempted' :0,'notattempted' :0};
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {   
            if( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex][0].main !== '' ){
                if(quesEle.showsmultiple){
                    var result = $.grep($rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex][0].justifypart , function(e){ return e.ischeck === true; });            
                    if ( result.length !== 0 ) {
                        $scope.check.attempted = $scope.check.attempted + 1;
                    } else {
                        //$scope.check.notattempted = $scope.check.notattempted + 1;
                    }
                } else {
                    $scope.check.attempted = $scope.check.attempted + 1;
                }                
            } else {
                $scope.check.notattempted = $scope.check.notattempted + 1;
            }
        });
        
        if( $scope.check.notattempted ===  $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'skipped';
        } else if( $scope.check.attempted === $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'complete';
        } else {
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'inprogress';
        }
        
        var json = {
            'id': $rootScope.renderQuestion[$scope.currentQues-1].id,
            'attempstatus':$rootScope.renderQuestion[$scope.currentQues-1].attempstatus,
            'userresponse':$rootScope.renderQuestion[$scope.currentQues-1].userresponse,
            'attempt_id':$rootScope.renderQuestion[$scope.currentQues-1].attempt_id,
            'num_question':$rootScope.renderQuestion[$scope.currentQues-1].num_question
        };        
        $serverRequest.postData.saveUserResponse( json );
        
    });
    
    $timeout(function(){
        $(window).trigger('resize');        
    })
});

app.controller('dragdropRenderCtrl', function ( $scope, $serverRequest, $timeout, $rootScope ) {    
    
    if(!$rootScope.renderQuestion[$scope.currentQues-1].userresponse){
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse = [];  
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = [];        
        });    
    }
    
    $scope.bindDropObject = function( questionIndex, opIndex ){
        if ( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[questionIndex].length ){
            return $rootScope.renderQuestion[$scope.currentQues-1].userresponse[questionIndex][opIndex];
        } else {
            return '';
        }
    };
    
    $scope.getActiveClass = function( questionIndex, opIndex ){
        if ( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[questionIndex].length ){
            if( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[questionIndex][opIndex] ){
                return 'activedropbox';
            }else {
                return '';
            }
        }
    };
    
    $scope.bindDropObjectSortable = function( questionIndex, optionIndex ){
        if ( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[questionIndex].length ){
            return $rootScope.renderQuestion[$scope.currentQues-1].userresponse[questionIndex][optionIndex];
        } else {
            return $rootScope.renderQuestion[$scope.currentQues-1].questions[questionIndex].option.optionvalue[optionIndex].value;
        }
    };
    
    $scope.$on("SUBMIT_RESPONSE",function () {                        
        
        $scope.check = {'attempted' :0,'notattempted' :0};
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {    
            if( $("[name='sortable"+quesIndex+"']").length ){
                var tempArray = [];
                $("[name='sortable"+quesIndex+"']").find('.sorting-answer').each(function (index, ele) {  
                    if( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].length !== 0 ){
                        tempArray[index] = $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex][$(this).attr('name')];
                    } else {
                        tempArray.push($rootScope.renderQuestion[$scope.currentQues-1].questions[quesIndex].option.optionvalue[$(this).attr('name')].value);
                    }
                });
                $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = tempArray;
            }
            
            var result = $.grep($rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] , function(e){ return e === ''; });            
            if ( result.length === 0 && $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].length ) {
                $scope.check.attempted = $scope.check.attempted + 1;
            } else {
                if( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].length === 0 ){
                    $scope.check.notattempted = $scope.check.notattempted + 1;    
                }                
            }
            
            
            
        });
        
        if( $scope.check.notattempted ===  $rootScope.renderQuestion[$scope.currentQues-1].questions.length ){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'skipped';
        } else if( $scope.check.attempted === $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'complete';
        } else {
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'inprogress';
        }
        
        var json = {
            'id': $rootScope.renderQuestion[$scope.currentQues-1].id,
            'attempstatus':$rootScope.renderQuestion[$scope.currentQues-1].attempstatus,
            'userresponse':$rootScope.renderQuestion[$scope.currentQues-1].userresponse,
            'attempt_id':$rootScope.renderQuestion[$scope.currentQues-1].attempt_id,
            'num_question':$rootScope.renderQuestion[$scope.currentQues-1].num_question
        };
        $serverRequest.postData.saveUserResponse( json );
        
    });
    
    $timeout(function(){
        $(window).trigger('resize');        
        angular.forEach( $scope.renderQuestion[$scope.currentQues-1].questions, function( qEle, qIndex ){            
            if( $("[name='sortable"+qIndex+"']") ){
                $("[name='sortable"+qIndex+"']").sortable({cursor: 'move',opacity: 0.65,
                    stop: function (event, ui) {}
                });
            }
            
            
            if( $("[name='draggable"+qIndex+"']") ){
                $("[name='draggable"+qIndex+"']").draggable({revert: function(dropped) {return !dropped;}, helper: 'clone', zIndex: 1});
                $("[name='droppable"+qIndex+"']").droppable({hoverClass: 'ui-state-hover',
                    drop: function (e, ui) {
                        //Remove text function for dragging image.
                        //$(this).html($(ui.draggable).clone().text());
                        var splitId = $(this).attr('id').split('_');
                        var quesID = splitId[1];
                        var optionID = splitId[2];
                        if( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesID].length === 0 ) {
                            $("#dropContainer"+quesID).find("[name='droppable"+quesID+"']").each(function (index, ele) {    
                                $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesID].push('');
                            });
                        }
                        var changeValue = $rootScope.renderQuestion[$scope.currentQues-1].questions[quesID].option.optionvalue[$(ui.draggable).attr('id').split('_')[2]].value;
                        $(this).html( changeValue );
                        $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesID][optionID] = changeValue;
                        //$(this).html($(ui.draggable).clone());
                        //$rootScope.renderQuestion[$scope.currentQues-1].userresponse[questionIndex].push( $rootScope.renderQuestion[$scope.currentQues-1].questions[questionIndex].option.optionvalue[$(this).attr('name')].value );
                    }
                });
            }            
            
        });        
        
    },1000);
    
});

app.controller('wordonimageRenderCtrl', function ( $scope, $serverRequest, $timeout, $rootScope ) {
    
    if(!$rootScope.renderQuestion[$scope.currentQues-1].userresponse){    
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse = [];
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = [];
            angular.forEach(quesEle.correctAns, function (optionEle, optionIndex) {        
                $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].push({label: optionEle.label, value: ''});
            });

        });
    }
    
    $scope.$on("SUBMIT_RESPONSE",function () {
        $scope.check = {'attempted' :0,'notattempted' :0};
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {       
            var result = $.grep($rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] , function(e){ return e.value === ''; });            
            if ( result.length === 0 ) {
                $scope.check.attempted = $scope.check.attempted + 1;
            } else {
                if( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].length ==  result.length){
                    $scope.check.notattempted = $scope.check.notattempted + 1;
                }                
            }

        });
        
        if( $scope.check.notattempted ===  $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'skipped';
        } else if( $scope.check.attempted === $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'complete';
        } else {
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'inprogress';
        }
        
        var json = {
            'id': $rootScope.renderQuestion[$scope.currentQues-1].id,
            'attempstatus':$rootScope.renderQuestion[$scope.currentQues-1].attempstatus,
            'userresponse':$rootScope.renderQuestion[$scope.currentQues-1].userresponse,
            'attempt_id':$rootScope.renderQuestion[$scope.currentQues-1].attempt_id,
            'num_question':$rootScope.renderQuestion[$scope.currentQues-1].num_question
        };        
        $serverRequest.postData.saveUserResponse( json );
        
    });
    
    $timeout(function(){
        $(window).trigger('resize');
    });
    
});

app.controller('labelliteracyRenderCtrl', function ( $scope, $serverRequest, $timeout, $rootScope ) {
    
    if(!$rootScope.renderQuestion[$scope.currentQues-1].userresponse){    
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse = [];
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = [];
            angular.forEach(quesEle.option, function (optionEle, optionIndex) {        
                $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].push('');
            });

        });
    }
    
    $scope.$on("SUBMIT_RESPONSE",function () {
        $scope.check = {'attempted' :0,'notattempted' :0};
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {       
            if( quesEle.literacyType === 'tick' ){
                var result = $.grep($rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] , function(e){ return e === true; });            
                if ( result.length ) {
                    $scope.check.attempted = $scope.check.attempted + 1;
                } else {
                    $scope.check.notattempted = $scope.check.notattempted + 1;                          
                }
            } else {
                var result = $.grep($rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] , function(e){ return e === ''; });            
                if ( result.length === 0 ) {
                    $scope.check.attempted = $scope.check.attempted + 1;
                } else {
                    if( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].length === result.length ){
                        $scope.check.notattempted = $scope.check.notattempted + 1;             
                    }                
                }
            }
            
        });
        
        if( $scope.check.notattempted ===  $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'skipped';
        } else if( $scope.check.attempted === $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'complete';
        } else {
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'inprogress';
        }
        
        var json = {
            'id': $rootScope.renderQuestion[$scope.currentQues-1].id,
            'attempstatus':$rootScope.renderQuestion[$scope.currentQues-1].attempstatus,
            'userresponse':$rootScope.renderQuestion[$scope.currentQues-1].userresponse,
            'attempt_id':$rootScope.renderQuestion[$scope.currentQues-1].attempt_id,
            'num_question':$rootScope.renderQuestion[$scope.currentQues-1].num_question
        };        
        $serverRequest.postData.saveUserResponse( json );
        
    });
    
    $timeout(function(){
        $(window).trigger('resize');
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {  
            $('#question'+quesIndex).find('.draggable').draggable({revert: function(dropped) {return !dropped;}, helper: 'clone', zIndex: 1});
            $('#question'+quesIndex).find('.droppable').droppable({
                hoverClass: 'ui-state-hover',
                drop: function (e, ui) {
                    var dropSplit = $(this).attr('id').split('_');
                    $rootScope.renderQuestion[$scope.currentQues-1].userresponse[dropSplit[1]][dropSplit[2]] = $(ui.draggable).clone().html();
                }
            });
        });
    });
    
});

app.controller('numberwordselectRenderCtrl', function ( $scope, $serverRequest, $timeout, $rootScope ) {
    
    if(!$rootScope.renderQuestion[$scope.currentQues-1].userresponse){    
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse = [];
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = [];
        });
    } else {
        $timeout(function(){
            angular.forEach( $rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
                angular.forEach( quesEle.option.optionvalue, function (optionEle, optionIndex) {
                    var result = $.grep($rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] , function(e){ return e === optionEle.value; });            
                    if ( result.length !== 0 ) {
                        $('#'+quesIndex+'_'+optionIndex).addClass($rootScope.renderQuestion[$scope.currentQues-1].questions[quesIndex].option.draw + '-cls');
                    }
                });
            });
        })
        
    }
    
    $scope.clearResponse = function( parentIndex ){
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse[parentIndex] = [];
        $scope.navigateToQues('', $scope.currentQues );
    };
    
    $scope.$on("SUBMIT_RESPONSE",function () {
        $scope.check = {'attempted' :0,'notattempted' :0};
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {       
            if ( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].length !== 0 ) {
                $scope.check.attempted = $scope.check.attempted + 1;
            } else {
                $scope.check.notattempted = $scope.check.notattempted + 1;             
            }

        });
        
        if( $scope.check.notattempted ===  $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'skipped';
        } else if( $scope.check.attempted === $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'complete';
        } else {
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'inprogress';
        }
        
        var json = {
            'id': $rootScope.renderQuestion[$scope.currentQues-1].id,
            'attempstatus':$rootScope.renderQuestion[$scope.currentQues-1].attempstatus,
            'userresponse':$rootScope.renderQuestion[$scope.currentQues-1].userresponse,
            'attempt_id':$rootScope.renderQuestion[$scope.currentQues-1].attempt_id,
            'num_question':$rootScope.renderQuestion[$scope.currentQues-1].num_question
        };        
        $serverRequest.postData.saveUserResponse( json );
        
    });
    
    $timeout(function(){
        $(window).trigger('resize');      
        
        $("[name='addDrawClass']").unbind().bind('click', function () {
            var quesId = $(this).attr('id').split('_')[0];
            if ($(this).hasClass($rootScope.renderQuestion[$scope.currentQues-1].questions[quesId].option.draw + '-cls')) {
                $(this).removeClass($rootScope.renderQuestion[$scope.currentQues-1].questions[quesId].option.draw + '-cls');
            } else {
                $(this).addClass($rootScope.renderQuestion[$scope.currentQues-1].questions[quesId].option.draw + '-cls');
            }
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesId] = [];
            $('#questionID'+quesId).find("[name='addDrawClass']").each(function( index ) {
                if( $(this).hasClass('circle-cls') ){ 
                    if($(this).find('[id^=MathJax-Element]').length){
                        var TempTxt = '\\('+$(this).find('script').html()+'\\)';
                        $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesId].push( '<p><span class="math-tex">'+TempTxt+'</span></p>\r\n' );
                    } else {
                        $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesId].push( $(this).html() );
                    }
                }
                if($(this).hasClass('underline-cls') ){
                    $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesId].push( $(this).html() );
                }
            });    
        });
    });
    
});

app.controller('selectliteracyRenderCtrl', function ( $scope, $serverRequest, $timeout, $rootScope ) {
    
    if(!$rootScope.renderQuestion[$scope.currentQues-1].userresponse){    
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse = [];
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = [];
        });
    } else {
        $timeout(function(){
            angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (ele, questionIndex) {
                var tempindex = 0;
                angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions[questionIndex].option, function (childEle, childindex) {
                    if ($rootScope.renderQuestion[$scope.currentQues-1].questions[questionIndex].option[childindex].ischeck) {
                        var answerID = 'answer_' + questionIndex + childindex;
                        $('#'+answerID).val( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[questionIndex][tempindex] );
                        tempindex = tempindex + 1;
                    }
                });
            });
        })
        
    }
    
    $scope.$on("SUBMIT_RESPONSE",function () {        
        $scope.check = {'attempted' :0,'notattempted' :0};
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (ele, questionIndex) {
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[questionIndex] = [];
            angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions[questionIndex].option, function (childEle, childindex) {
                if ($rootScope.renderQuestion[$scope.currentQues-1].questions[questionIndex].option[childindex].ischeck) {
                    var answerID = 'answer_' + questionIndex + childindex;
                    $rootScope.renderQuestion[$scope.currentQues-1].userresponse[questionIndex].push($('#'+answerID).val());
                }
            });
        });
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {     
            var result = $.grep($rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] , function(e){ return e === ''; });         
            if ( result.length === 0 ) {
                $scope.check.attempted = $scope.check.attempted + 1;
            } else {
                if( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].length === result.length){
                    $scope.check.notattempted = $scope.check.notattempted + 1;
                }         
            }

        });
        
        if( $scope.check.notattempted ===  $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'skipped';
        } else if( $scope.check.attempted === $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'complete';
        } else {
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'inprogress';
        }
        
        var json = {
            'id': $rootScope.renderQuestion[$scope.currentQues-1].id,
            'attempstatus':$rootScope.renderQuestion[$scope.currentQues-1].attempstatus,
            'userresponse':$rootScope.renderQuestion[$scope.currentQues-1].userresponse,
            'attempt_id':$rootScope.renderQuestion[$scope.currentQues-1].attempt_id,
            'num_question':$rootScope.renderQuestion[$scope.currentQues-1].num_question
        };        
        $serverRequest.postData.saveUserResponse( json );
        
    });
    
    $timeout(function(){
        $(window).trigger('resize');      
    });
    
});

app.controller('drawinggraphsRenderCtrl', function ( $scope, $serverRequest, $timeout, $rootScope ) {
    $scope.backgroundimage = [];
    if(!$rootScope.renderQuestion[$scope.currentQues-1].userresponse){    
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse = [];
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = [];
            angular.forEach(quesEle.option, function (optionEle, optionIndex) {        
                $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].push({label: optionEle.label, value: ''});
            });

        });
    }
    angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
        $scope.backgroundimage[quesIndex] = '/questionimg/'+ quesEle.imgPath;
    });
    
    $scope.$on("SUBMIT_RESPONSE",function () {
        $scope.check = {'attempted' :0,'notattempted' :0};
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {     
            var result = $.grep($rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] , function(e){ return e.value === ''; });         
            if ( result.length === 0 ) {
                $scope.check.attempted = $scope.check.attempted + 1;
            } else {
                if( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].length === result.length){
                    $scope.check.notattempted = $scope.check.notattempted + 1;
                }         
            }

        });
        
        if( $scope.check.notattempted ===  $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'skipped';
        } else if( $scope.check.attempted === $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'complete';
        } else {
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'inprogress';
        }
        
        var json = {
            'id': $rootScope.renderQuestion[$scope.currentQues-1].id,
            'attempstatus':$rootScope.renderQuestion[$scope.currentQues-1].attempstatus,
            'userresponse':$rootScope.renderQuestion[$scope.currentQues-1].userresponse,
            'attempt_id':$rootScope.renderQuestion[$scope.currentQues-1].attempt_id,
            'num_question':$rootScope.renderQuestion[$scope.currentQues-1].num_question
        };        
        $serverRequest.postData.saveUserResponse( json );
        
    });
    
    $timeout(function(){
        $(window).trigger('resize');
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {    
            $('#setanswer'+quesIndex).find('.draggable').draggable({revert: function(dropped) {return !dropped;}, helper: 'clone', zIndex: 1});
            $('#dropanswer'+quesIndex).find('.droppable').droppable({hoverClass: 'ui-state-hover',
                drop: function (e, ui) {
                    $(this).text($(ui.draggable).clone().html());
                    var dropSplit = $(this).attr('id').split('_');
                    $scope.renderQuestion[$scope.currentQues-1].userresponse[dropSplit[1]][dropSplit[2]].value = $(this).text();
                }
            });
        });
        
    });
    
});

app.controller('drawlineonimageRenderCtrl', function ( $scope, $serverRequest, $timeout, $rootScope ) {
    
    if(!$rootScope.renderQuestion[$scope.currentQues-1].userresponse){    
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse = [];
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = [];
        });
    }
    
    $scope.$on("SUBMIT_RESPONSE",function () {
        $scope.check = {'attempted' :0,'notattempted' :0};
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {     
            if ( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].length !== 0 ) {
                $scope.check.attempted = $scope.check.attempted + 1;
            } else {
                $scope.check.notattempted = $scope.check.notattempted + 1;                      
            }
        });
        
        if( $scope.check.notattempted ===  $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'skipped';
        } else if( $scope.check.attempted === $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'complete';
        } else {
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'inprogress';
        }
        
        var json = {
            'id': $rootScope.renderQuestion[$scope.currentQues-1].id,
            'attempstatus':$rootScope.renderQuestion[$scope.currentQues-1].attempstatus,
            'userresponse':$rootScope.renderQuestion[$scope.currentQues-1].userresponse,
            'attempt_id':$rootScope.renderQuestion[$scope.currentQues-1].attempt_id,
            'num_question':$rootScope.renderQuestion[$scope.currentQues-1].num_question
        };        
        $serverRequest.postData.saveUserResponse( json );
        
    });
    
    $scope.clearDrawLine = function( parentIndex ){
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse[parentIndex] = [];
        $scope.navigateToQues('', $scope.currentQues );
    };
    
    $scope.setanswerCanvasRender = function( quesId ){
        var canvas = new fabric.Canvas('setansCanvas'+quesId, { selection: false });
	var canvas1 = new fabric.Canvas('setansCanvasImg'+quesId);
	var line, isDown, imageUrl, isMove;
        var correctpoints, correctset;
        imageUrl = "/questionimg/"+$rootScope.renderQuestion[$scope.currentQues-1].questions[quesId].imgPath;
	fabric.Image.fromURL(imageUrl, function(oImg) {
	  canvas1.add(oImg);
	});	
        
        if( $rootScope.renderQuestion[$scope.currentQues-1].questions[quesId].option.length ){
             angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions[quesId].option, function (eoption, ioption) {
               canvas.add(new fabric.Circle({ radius: 5, fill: 'red', top: eoption.top, left: eoption.left }));                
               canvas.item(ioption).selectable = false;
            });            
        }
        
        if( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesId].length ){
            angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesId], function (uoption, iuoption) {
                line = new fabric.Line(uoption.points, {
                    strokeWidth: 5,
                    fill: 'red',
                    stroke: 'red',
                    originX: 'center',
                    originY: 'center'
                });
                canvas.add(line);

                line.set(uoption.set);
                canvas.renderAll();
            }); 
            
        }
	canvas.on('mouse:down', function(o){   
            isDown = true;
            isMove = false;
            var pointer = canvas.getPointer(o.e);
            var points = [ pointer.x, pointer.y, pointer.x, pointer.y ];
            correctpoints = points;
            line = new fabric.Line(points, {
                strokeWidth: 5,
                fill: 'red',
                stroke: 'red',
                originX: 'center',
                originY: 'center'
            });
            canvas.add(line);
	});	
	canvas.on('mouse:move', function(o){
            if (!isDown) return;
            isMove = true;
            var pointer = canvas.getPointer(o.e);
            correctset = { x2: pointer.x, y2: pointer.y };
            line.set({ x2: pointer.x, y2: pointer.y });
            canvas.renderAll();
        });	
	canvas.on('mouse:up', function(o){
            if( isMove ){
                $rootScope.renderQuestion[$scope.currentQues-1].userresponse[$(this.lowerCanvasEl).attr('id').substr($(this.lowerCanvasEl).attr('id').length - 1)].push({points: correctpoints, set: correctset});
            }
	    isDown = false;
            //$scope.setanswerCanvasRender(quesId);
	});
    };
    
    $timeout(function(){
        $(window).trigger('resize');        
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {    
             $scope.setanswerCanvasRender(quesIndex);
        });        
    });    
});

app.controller('measurementRenderCtrl', function ( $scope, $serverRequest, $timeout, $rootScope, $sce ) {
    
    $scope.canvasWidth = '600';
    $scope.canvasHeigth = '500';
    $scope.stopdragging = false;
    
    if(!$rootScope.renderQuestion[$scope.currentQues-1].userresponse){    
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse = [];
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = '';
        });
    }
    
    $scope.$on("SUBMIT_RESPONSE",function () {
        $scope.check = {'attempted' :0,'notattempted' :0};
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {     
            if ( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] !== '' ) {
                $scope.check.attempted = $scope.check.attempted + 1;
            } else {
                $scope.check.notattempted = $scope.check.notattempted + 1;                      
            }
        });
        
        if( $scope.check.notattempted ===  $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'skipped';
        } else if( $scope.check.attempted === $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'complete';
        } else {
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'inprogress';
        }
        
        var json = {
            'id': $rootScope.renderQuestion[$scope.currentQues-1].id,
            'attempstatus':$rootScope.renderQuestion[$scope.currentQues-1].attempstatus,
            'userresponse':$rootScope.renderQuestion[$scope.currentQues-1].userresponse,
            'attempt_id':$rootScope.renderQuestion[$scope.currentQues-1].attempt_id,
            'num_question':$rootScope.renderQuestion[$scope.currentQues-1].num_question
        };        
        $serverRequest.postData.saveUserResponse( json );
        
    });
    
    $scope.clearDrawing = function( index ){
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse[index] = '';
        $scope.navigateToQues('', $scope.currentQues );
    };
    
    /*Set canvas data here*/
    $scope.setCanvasData = function (src, width, height, quesIndex) {
        
        var canvas = document.getElementById('measurementCanvas'+quesIndex);
        $scope.stopdragging = false;
        var ctx = canvas.getContext('2d');
        var line = new Line(ctx);
        var img = new Image;

        ctx.strokeStyle = 'red';

        function Line(ctx) {

            var me = this;

            this.x1 = 0;
            this.x2 = 0;
            this.y1 = 0;
            this.y2 = 0;

            this.draw = function () {
                ctx.beginPath();
                ctx.moveTo(me.y1, me.x1);
                ctx.lineTo(me.y2, me.x2);
                ctx.stroke();
            }
        }
        img.onload = start;
        img.src = '/questionimg/'+src;
        img.height = $scope.canvasHeight+'px';
        img.width = $scope.canvasWidth+'px';
        function start() {
            var element = '';
            element = $('#measurementCanvas'+quesIndex);
            ctx.drawImage(img, 0, 0, canvas.height, canvas.width);
            
            var r = canvas.getBoundingClientRect()
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            updateLineUserResponse();
            canvas.addEventListener('mousemove', function(e) {               
                if(!$scope.stopdragging){
                    updateLine(e);
                }
            });
            element.unbind().bind('click', function( e ){
                $scope.stopdragging = !$scope.stopdragging;
            });
        }
        function updateLine(e) {
            var r = canvas.getBoundingClientRect(),
                    x = e.clientY - r.top,
                    y = e.clientX - r.left;
            ctx.rect(0,0,canvas.width, canvas.height);
            ctx.fillStyle="#fff";
            ctx.fill();
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            if( $rootScope.renderQuestion[$scope.currentQues-1].questions[quesIndex].view === 'vertical' ){
                $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = x;
                line.x1 = x;
                line.y1 = 0;
                line.x2 = x;
                line.y2 = canvas.height;
            } else {
                $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = y;
                line.x1 = 0;
                line.y1 = y;
                line.x2 = canvas.width;
                line.y2 = y;
            }
            //console.log( line );
            line.draw();            
        }
        function updateLineUserResponse() {
            var r = canvas.getBoundingClientRect();
            ctx.rect(0,0,canvas.width, canvas.height);
            ctx.fillStyle="#fff";
            ctx.fill();
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            if( $rootScope.renderQuestion[$scope.currentQues-1].questions[quesIndex].view === 'vertical' ){
                var x  = $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex];
                var y = 0;
                line.x1 = x;
                line.y1 = 0;
                line.x2 = x;
                line.y2 = canvas.height;
            } else {
                var x = 0;
                var y = $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex];
                line.x1 = 0;
                line.y1 = y;
                line.x2 = canvas.width;
                line.y2 = y;
            }
            //console.log( line );
            line.draw();              
        }
    };
    
    $timeout(function(){
        $(window).trigger('resize');   
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) { 
            setTimeout(function(){
                $scope.setCanvasData(quesEle.imgPath, $scope.canvasWidth, $scope.canvasHeigth, quesIndex );
            })            
        });
    });    
});

app.controller('joiningdotsRenderCtrl', function ( $scope, $serverRequest, $timeout, $rootScope, $sce ) {
    
    if(!$rootScope.renderQuestion[$scope.currentQues-1].userresponse){    
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse = [];
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = [];
        });
    }
    
    $scope.clearDrawing = function( index ){
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse[index] = [];
        $scope.navigateToQues('', $scope.currentQues );
    };
    
    $scope.$on("SUBMIT_RESPONSE",function () {
        $scope.check = {'attempted' :0,'notattempted' :0};
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {     
            if ( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].length !== 0 ) {
                $scope.check.attempted = $scope.check.attempted + 1;
            } else {
                $scope.check.notattempted = $scope.check.notattempted + 1;                      
            }
        });
        
        if( $scope.check.notattempted ===  $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'skipped';
        } else if( $scope.check.attempted === $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'complete';
        } else {
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'inprogress';
        }
        
        var json = {
            'id': $rootScope.renderQuestion[$scope.currentQues-1].id,
            'attempstatus':$rootScope.renderQuestion[$scope.currentQues-1].attempstatus,
            'userresponse':$rootScope.renderQuestion[$scope.currentQues-1].userresponse,
            'attempt_id':$rootScope.renderQuestion[$scope.currentQues-1].attempt_id,
            'num_question':$rootScope.renderQuestion[$scope.currentQues-1].num_question
        };
        $serverRequest.postData.saveUserResponse( json );
        
    });
    
    $timeout(function(){
        $(window).trigger('resize');   
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) { 
           var forDotsjoin = {
                'ques': $rootScope.renderQuestion[$scope.currentQues-1].questions[quesIndex],
                'userresponse' : $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex],
                'action': 'student'
            }
            var lock = new DotsJoin("#patternContainer"+quesIndex, forDotsjoin );
        });
        
        $( window ).on( "watchdots", function( event, obj ) {
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[obj.holder[0].id.split('Container')[1]].push( obj.pattern );
        });
    });    
});

app.controller('singlemultipleentryRenderCtrl', function ($scope, $serverRequest, $timeout, $rootScope) {
    angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (ele, index) {
        if(typeof ele.headerrow == "undefined" || typeof ele.headerrow == "undefined"){
            $rootScope.renderQuestion[$scope.currentQues-1].questions[index].headerrow = true;
            $rootScope.renderQuestion[$scope.currentQues-1].questions[index].headercol = true;
        }
    });
    if(!$rootScope.renderQuestion[$scope.currentQues-1].userresponse){
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse = [];
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {  
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = {option:''};
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].option = quesEle.option;        
            
        }); 
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].userresponse, function (quesEle, quesIndex) {  
            angular.forEach(quesEle.option, function (optionEle, optionIndex) { 
                angular.forEach(optionEle, function (eEle, eleIndex) {
                    $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].option[optionIndex][eleIndex].checkvalue = '';
                });
            });
        }); 
        
    }
    
    $scope.updateValues = function (type, quesIndex, parentInd, childInd) {
        if (type === 'single') {
            angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions[quesIndex].option[parentInd], function (childEle, index) {
                if ( index === childInd ) {
                    $rootScope.renderQuestion[$scope.currentQues-1].questions[quesIndex].option[parentInd][index].checkvalue = true;
                } else {
                    $rootScope.renderQuestion[$scope.currentQues-1].questions[quesIndex].option[parentInd][index].checkvalue = false;
                }

            });
        }

    };
    
    $scope.$on("SUBMIT_RESPONSE",function () {
        $scope.check = {'attempted' :0,'notattempted' :0};
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].option, function ( optionEle, optionIndex ) {    
                if( optionIndex !== 0 ){
                    var result = $.grep(optionEle , function(e){ return e.checkvalue != '' && e.title === false; });            
                    if ( result.length !== 0 ) {
                        $scope.check.attempted = $scope.check.attempted + 1;
                    } else {
                        $scope.check.notattempted = $scope.check.notattempted + 1;
                    }
                    
                }
            });
        });
        if( $scope.check.notattempted && $scope.check.attempted === 0){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'skipped';
        } else if( $scope.check.attempted && $scope.check.notattempted === 0){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'complete';
        } else {
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'inprogress';
        }
        
        var json = {
            'id': $rootScope.renderQuestion[$scope.currentQues-1].id,
            'attempstatus':$rootScope.renderQuestion[$scope.currentQues-1].attempstatus,
            'userresponse':$rootScope.renderQuestion[$scope.currentQues-1].userresponse,
            'attempt_id':$rootScope.renderQuestion[$scope.currentQues-1].attempt_id,
            'num_question':$rootScope.renderQuestion[$scope.currentQues-1].num_question
        };    
        $serverRequest.postData.saveUserResponse( json );
        
    });
    
    $timeout(function(){
        $(window).trigger('resize');        
    });
});

app.controller('reflectionRenderCtrl', function ( $scope, $serverRequest, $timeout, $rootScope ) {
    
    if(!$rootScope.renderQuestion[$scope.currentQues-1].userresponse){    
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse = [];
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = [];
            $rootScope.renderQuestion[$scope.currentQues-1].questions[quesIndex].correctAns = [];
        });
    } else {
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            $rootScope.renderQuestion[$scope.currentQues-1].questions[quesIndex].correctAns = $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex];
        });
    }
    
    $scope.$on("SUBMIT_RESPONSE",function () {
        $scope.check = {'attempted' :0,'notattempted' :0};
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {     
            if ( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].length !== 0 ) {
                $scope.check.attempted = $scope.check.attempted + 1;
            } else {
                $scope.check.notattempted = $scope.check.notattempted + 1;                      
            }
        });
        
        if( $scope.check.notattempted ===  $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'skipped';
        } else if( $scope.check.attempted === $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'complete';
        } else {
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'inprogress';
        }
        
        var json = {
            'id': $rootScope.renderQuestion[$scope.currentQues-1].id,
            'attempstatus':$rootScope.renderQuestion[$scope.currentQues-1].attempstatus,
            'userresponse':$rootScope.renderQuestion[$scope.currentQues-1].userresponse,
            'attempt_id':$rootScope.renderQuestion[$scope.currentQues-1].attempt_id,
            'num_question':$rootScope.renderQuestion[$scope.currentQues-1].num_question
        };        
        $serverRequest.postData.saveUserResponse( json );
        
    });
    
    $scope.clearDrawing = function( index ){
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse[index] = [];
        $rootScope.renderQuestion[$scope.currentQues-1].questions[index].correctAns = [];
        $scope.navigateToQues('', $scope.currentQues );
    };
    
    $timeout(function(){
        $(window).trigger('resize');
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {    
            var forDotsjoin = {
                'ques': $rootScope.renderQuestion[$scope.currentQues-1].questions[quesIndex],
                'action': 'ques',
                'addevent' : false
                
            };
            var lock = new Reflection("#setpatternContainer_ques_"+quesIndex, forDotsjoin );
            forDotsjoin.action = 'ans';
            forDotsjoin.addevent = true;
            var lock = new Reflection("#setpatternAnswer_ans_"+quesIndex, forDotsjoin );
            
            $( window ).on( "watch", function( event, obj ) {
                if(obj.holder[0].id.split('_')[1] === 'ques'){
                    $rootScope.renderQuestion[$scope.currentQues-1].questions[obj.holder[0].id.split('_')[2]].option.pattern.push( obj.pattern );
                } else {
                    $rootScope.renderQuestion[$scope.currentQues-1].userresponse[obj.holder[0].id.split('_')[2]].push( obj.pattern );
                    $rootScope.renderQuestion[$scope.currentQues-1].questions[obj.holder[0].id.split('_')[2]].correctAns.push( obj.pattern );
                }
            });
        });  
    });
    
});

app.controller('measurementlineangleRenderCtrl', function ( $scope, $serverRequest, $timeout, $rootScope ) {
    
    if(!$rootScope.renderQuestion[$scope.currentQues-1].userresponse){
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse = [];  
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            angular.forEach(quesEle.correctAns, function (optionEle, optionIndex) {        
                $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = [{label: optionEle.label, value: ''}];        
            });
        });    
    }   
    
    $scope.$on("SUBMIT_RESPONSE",function () {
        $scope.check = {'attempted' :0,'notattempted' :0};
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {       
            var result = $.grep($rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] , function(e){ return e.value === ''; });            
            if ( result.length === 0 ) {
                $scope.check.attempted = $scope.check.attempted + 1;
            } else {
               if( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].length ===  result.length){
                    $scope.check.notattempted = $scope.check.notattempted + 1;
                }       
            }
        });
        
        if( $scope.check.notattempted ===  $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'skipped';
        } else if( $scope.check.attempted === $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'complete';
        } else {
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'inprogress';
        }
        
        var json = {
            'id': $rootScope.renderQuestion[$scope.currentQues-1].id,
            'attempstatus':$rootScope.renderQuestion[$scope.currentQues-1].attempstatus,
            'userresponse':$rootScope.renderQuestion[$scope.currentQues-1].userresponse,
            'attempt_id':$rootScope.renderQuestion[$scope.currentQues-1].attempt_id,
            'num_question':$rootScope.renderQuestion[$scope.currentQues-1].num_question
        };        
        $serverRequest.postData.saveUserResponse( json );
        
    });
    
    $timeout(function(){
        $(window).trigger('resize');        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            var tempprotractor = '';
            var canvas = new fabric.Canvas('canswer'+quesIndex);
            if(quesEle.option.protractor){
                tempprotractor = '/questionimg/'+quesEle.option.protractor;
            }else{
                tempprotractor = '../../images/protractor.gif';
            }
            fabric.Image.fromURL(tempprotractor, function(oImg) {
              canvas.add(oImg);
              canvas.item(0).set({
                borderColor: '#1caf9a',
                cornerColor: '#549BDB',
                transparentCorners: false
              });
              canvas.item(0).lockScalingX = canvas.item(0).lockScalingY = true;
            }); 
        });           
    });
    
});

app.controller('piechartRenderCtrl', function ( $scope, $serverRequest, $timeout, $rootScope ) {
    
    $scope.percentr = [];
    if(!$rootScope.renderQuestion[$scope.currentQues-1].userresponse){
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse = [];  
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = [];
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].push({value: ''});
            $scope.percentr[quesIndex] = 0;
        });    
    } else {
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            $scope.percentr[quesIndex] = $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex][0].value;
        });    
    }   
    
    $scope.updatechart = function( val, parentIndex ){
        $scope.percentr[parentIndex] = val !== '' ? val : 0;
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse[parentIndex][0].value  = $scope.percentr[parentIndex];
    };
    
    $scope.$on("SUBMIT_RESPONSE",function () {
        $scope.check = {'attempted' :0,'notattempted' :0};
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {       
            var result = $.grep($rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] , function(e){ return e.value === 0; });            
            if ( result.length === 0 ) {
                $scope.check.attempted = $scope.check.attempted + 1;
            } else {
               if( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].length ===  result.length){
                    $scope.check.notattempted = $scope.check.notattempted + 1;
                }       
            }
        });
        
        if( $scope.check.notattempted ===  $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'skipped';
        } else if( $scope.check.attempted === $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'complete';
        } else {
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'inprogress';
        }
        
        var json = {
            'id': $rootScope.renderQuestion[$scope.currentQues-1].id,
            'attempstatus':$rootScope.renderQuestion[$scope.currentQues-1].attempstatus,
            'userresponse':$rootScope.renderQuestion[$scope.currentQues-1].userresponse,
            'attempt_id':$rootScope.renderQuestion[$scope.currentQues-1].attempt_id,
            'num_question':$rootScope.renderQuestion[$scope.currentQues-1].num_question
        };        
        $serverRequest.postData.saveUserResponse( json );
        
    });
    
    $timeout(function(){
        $(window).trigger('resize');        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            $scope.percentr[quesIndex] = $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex][0].value;
        });           
    });
    
});

/*app.controller('reflectionRenderCtrl', function ( $scope, $serverRequest, $timeout, $rootScope ) {
    
    if(!$rootScope.renderQuestion[$scope.currentQues-1].userresponse){    
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse = [];
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = [];
        });
    }
    
    $scope.$on("SUBMIT_RESPONSE",function () {
        $scope.check = {'attempted' :0,'notattempted' :0};
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {     
            if ( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].length !== 0 ) {
                $scope.check.attempted = $scope.check.attempted + 1;
            } else {
                $scope.check.notattempted = $scope.check.notattempted + 1;                      
            }
        });
        
        if( $scope.check.notattempted ===  $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'skipped';
        } else if( $scope.check.attempted === $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'complete';
        } else {
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'inprogress';
        }
        
        var json = {
            'id': $rootScope.renderQuestion[$scope.currentQues-1].id,
            'attempstatus':$rootScope.renderQuestion[$scope.currentQues-1].attempstatus,
            'userresponse':$rootScope.renderQuestion[$scope.currentQues-1].userresponse,
            'attempt_id':$rootScope.renderQuestion[$scope.currentQues-1].attempt_id,
            'num_question':$rootScope.renderQuestion[$scope.currentQues-1].num_question
        };        
        $serverRequest.postData.saveUserResponse( json );
        
    });
    
    $scope.quesCanvasRender = function( quesId, canvasName ){
        var canvas = new fabric.Canvas(canvasName, { selection: false });
	var line;
        
        var templeft = 0, temptop = 0, itemCount = 0;
        for( var j=0; j<25; j++){
            if( j === 0 ){
                temptop = 5;
            } else {
                temptop = temptop + 12;
            }
            
            templeft = 0;
            for( var i=0; i<17; i++){
                if( i === 0 ){
                    templeft = 3;
                } else {
                    templeft = templeft  + 12;
                }
                
                var dot = new fabric.Circle({
                    left: templeft,
                    top: temptop,
                    radius: 2,
                    fill: '#888888'
                });
                canvas.add(dot);
                itemCount = itemCount + 1;
                canvas.item(itemCount - 1).selectable = false;
            }
        }      
        
        if( $rootScope.renderQuestion[$scope.currentQues-1].questions[quesId].option.optiontext.length ){
             angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions[quesId].option.optiontext, function (elecorrecAns, correcAnsIndex) {
                line = new fabric.Line(elecorrecAns.points, {
                    strokeWidth: 3,
                    fill: 'red',
                    stroke: 'red',
                    originX: 'center',
                    originY: 'center'
                });
                canvas.add(line);
                
                line.set(elecorrecAns.set);
                canvas.renderAll();
                
            });            
        }	
    };
    
    $scope.answerCanvasRender = function( quesId ){
        var canvasType = $rootScope.renderQuestion[$scope.currentQues-1].questions[quesId].option.mirrorline;
        var canvas = new fabric.Canvas('ansCanvas'+canvasType+quesId, { selection: false });
	
	var line, isDown;
        var correctpoints, correctset, templeft = 0, temptop = 0, itemCount = 0;
        for( var j=0; j<25; j++){
            if( j === 0 ){
                temptop = 5;
            } else {
                temptop = temptop + 12;
            }
            
            templeft = 0;
            for( var i=0; i<17; i++){
                if( i === 0 ){
                    templeft = 3;
                } else {
                    templeft = templeft  + 12;
                }
                
                var dot = new fabric.Circle({
                    left: templeft,
                    top: temptop,
                    radius: 2,
                    fill: '#888888'
                });
                canvas.add(dot);
                itemCount = itemCount + 1;
                canvas.item(itemCount - 1).selectable = false;
            }
        }      
        
        var canvasName = canvasType === 'right' ? 'left' : canvasType === 'left' ? 'right' : canvasType === 'top' ? 'bottom' : canvasType === 'bottom' ? 'top' : '';
        $scope.quesCanvasRender(quesId, 'ansCanvas'+canvasName+quesId );
        
        if( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesId].length ){
             angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesId], function (elecorrecAns, correcAnsIndex) {
                line = new fabric.Line(elecorrecAns.points, {
                    strokeWidth: 3,
                    fill: 'red',
                    stroke: 'red',
                    originX: 'center',
                    originY: 'center'
                });
                canvas.add(line);
                
                line.set(elecorrecAns.set);
                canvas.renderAll();
                
            });            
        }
	canvas.on('mouse:down', function(o){   
            isDown = true;
            var pointer = canvas.getPointer(o.e);
            var points = [ pointer.x, pointer.y, pointer.x, pointer.y ];
            correctpoints = points;
            line = new fabric.Line(points, {
                strokeWidth: 3,
                fill: 'red',
                stroke: 'red',
                originX: 'center',
                originY: 'center'
            });
            canvas.add(line);
	});	
	canvas.on('mouse:move', function(o){
            if (!isDown) return;
            var pointer = canvas.getPointer(o.e);
            correctset = { x2: pointer.x, y2: pointer.y };
            line.set({ x2: pointer.x, y2: pointer.y });
            canvas.renderAll();
        });	
	canvas.on('mouse:up', function(o){
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[$(this.lowerCanvasEl).attr('id').substr($(this.lowerCanvasEl).attr('id').length - 1)].push({points: correctpoints, set: correctset});
	    isDown = false;
	});
    };
    
    $scope.clearDrawing = function( index ){
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse[index] = [];
        $scope.navigateToQues('', $scope.currentQues );
    };
    
    $timeout(function(){
        $(window).trigger('resize');
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {    
            $scope.answerCanvasRender(quesIndex);
        });  
    });
    
});*/

app.controller('shadingshapeRenderCtrl', function ( $scope, $serverRequest, $timeout, $rootScope ) {
    
    if(!$rootScope.renderQuestion[$scope.currentQues-1].userresponse){
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse = [];  
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = quesEle.option.optionval;        
        });    
    }   
    
    $scope.bindCorrectAnswer = function(){
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (ele, index) {
            $($('#setAnsShape'+index).find('td')).unbind( 'click' ).bind( 'click', function() {
                
                var splitObj = $(this).attr('id').split('_');                
                if ($rootScope.renderQuestion[$scope.currentQues-1].userresponse[splitObj[1]][splitObj[2]][splitObj[3]].class === 'first-cell' ) {
                    $rootScope.renderQuestion[$scope.currentQues-1].userresponse[splitObj[1]][splitObj[2]][splitObj[3]].class = 'second-cell';
                    
                } else if( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[splitObj[1]][splitObj[2]][splitObj[3]].class === 'second-cell' ){
                    $rootScope.renderQuestion[$scope.currentQues-1].userresponse[splitObj[1]][splitObj[2]][splitObj[3]].class = 'third-cell';
                    
                } else if( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[splitObj[1]][splitObj[2]][splitObj[3]].class === 'third-cell' ){
                    //$scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = 'forth-cell';
                    $rootScope.renderQuestion[$scope.currentQues-1].userresponse[splitObj[1]][splitObj[2]][splitObj[3]].class = 'fifth-cell';
                    
                }else if( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[splitObj[1]][splitObj[2]][splitObj[3]].class === 'fifth-cell' ){
                    //$scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = 'forth-cell';
                    $rootScope.renderQuestion[$scope.currentQues-1].userresponse[splitObj[1]][splitObj[2]][splitObj[3]].class = 'sixth-cell';
                    
                }else if( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[splitObj[1]][splitObj[2]][splitObj[3]].class === 'sixth-cell' ){
                    //$scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = 'forth-cell';
                    $rootScope.renderQuestion[$scope.currentQues-1].userresponse[splitObj[1]][splitObj[2]][splitObj[3]].class = 'seventh-cell';
                    
                }else if( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[splitObj[1]][splitObj[2]][splitObj[3]].class === 'seventh-cell' ){
                    //$scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = 'forth-cell';
                    $rootScope.renderQuestion[$scope.currentQues-1].userresponse[splitObj[1]][splitObj[2]][splitObj[3]].class = '';
                    
                }else if( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[splitObj[1]][splitObj[2]][splitObj[3]].class === 'forth-cell' ){
                    //$scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = '';
                    
                } else {
                    $rootScope.renderQuestion[$scope.currentQues-1].userresponse[splitObj[1]][splitObj[2]][splitObj[3]].class = 'first-cell';
                    
                } 
                $scope.$digest();
            });
        });
    };
    
    $scope.$on("SUBMIT_RESPONSE",function () {
        $scope.check = {'attempted' :0,'notattempted' :0};
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {       
            if ( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] !== '' ) {
                $scope.check.attempted = $scope.check.attempted + 1;
            } else {
                $scope.check.notattempted = $scope.check.notattempted + 1;
            }
        });
        
        if( $scope.check.notattempted ===  $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'skipped';
        } else if( $scope.check.attempted === $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'complete';
        } else {
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'inprogress';
        }
        
        var json = {
            'id': $rootScope.renderQuestion[$scope.currentQues-1].id,
            'attempstatus':$rootScope.renderQuestion[$scope.currentQues-1].attempstatus,
            'userresponse':$rootScope.renderQuestion[$scope.currentQues-1].userresponse,
            'attempt_id':$rootScope.renderQuestion[$scope.currentQues-1].attempt_id,
            'num_question':$rootScope.renderQuestion[$scope.currentQues-1].num_question
        };
        $serverRequest.postData.saveUserResponse( json );
        
    });
    
    $timeout(function(){
        $(window).trigger('resize');
        $scope.bindCorrectAnswer();
    });
    
});

app.controller('symmetricRenderCtrl', function ( $scope, $serverRequest, $timeout, $rootScope, $sce ) {
    
    if(!$rootScope.renderQuestion[$scope.currentQues-1].userresponse){    
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse = [];
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = [{'ischeck':false},{'ischeck':false},{'ischeck':false},{'ischeck':false}];
        });
    }
    
    $scope.$on("SUBMIT_RESPONSE",function () {
        $scope.check = {'attempted' :0,'notattempted' :0};
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {     
            var result = $.grep($rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] , function(e){ return e.ischeck === true; });
            if ( result.length !== 0 ) {
                $scope.check.attempted = $scope.check.attempted + 1;
            } else {
                $scope.check.notattempted = $scope.check.notattempted + 1;                      
            }
        });
        
        if( $scope.check.notattempted ===  $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'skipped';
        } else if( $scope.check.attempted === $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'complete';
        } else {
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'inprogress';
        }
        
        var json = {
            'id': $rootScope.renderQuestion[$scope.currentQues-1].id,
            'attempstatus':$rootScope.renderQuestion[$scope.currentQues-1].attempstatus,
            'userresponse':$rootScope.renderQuestion[$scope.currentQues-1].userresponse,
            'attempt_id':$rootScope.renderQuestion[$scope.currentQues-1].attempt_id,
            'num_question':$rootScope.renderQuestion[$scope.currentQues-1].num_question
        };
        $serverRequest.postData.saveUserResponse( json );
        
    });
    
    $timeout(function(){
        $(window).trigger('resize');   
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {             
            var forDotsjoin = {
                'ques': $rootScope.renderQuestion[$scope.currentQues-1].questions[quesIndex],
                'action': 'ques',
                'addevent' : false
            };
            var lock = new Reflection("#patternContainer"+quesIndex, forDotsjoin );         
            
        });
    });    
});

app.controller('inputonimageRenderCtrl', function ( $scope, $serverRequest, $timeout, $rootScope ) {
    $scope.backgroundimage = [];
    
    if(!$rootScope.renderQuestion[$scope.currentQues-1].userresponse){    
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse = [];
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = [];
            angular.forEach(quesEle.option, function (optionEle, optionIndex) {        
                $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].push({value: ''});
            });

        });
    }
    angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
        $scope.backgroundimage[quesIndex] = '/questionimg/'+ quesEle.imgPath;
    });
    $scope.$on("SUBMIT_RESPONSE",function () {
        
        $scope.check = {'attempted' :0,'notattempted' :0};
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {     
            var result = $.grep($rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] , function(e){ return e.value === ''; });         
            if ( result.length === 0 ) {
                $scope.check.attempted = $scope.check.attempted + 1;
            } else {
                if( $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].length === result.length){
                    $scope.check.notattempted = $scope.check.notattempted + 1;
                }         
            }

        });
        
        if( $scope.check.notattempted ===  $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'skipped';
        } else if( $scope.check.attempted === $rootScope.renderQuestion[$scope.currentQues-1].questions.length){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'complete';
        } else {
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'inprogress';
        }
        
        var json = {
            'id': $rootScope.renderQuestion[$scope.currentQues-1].id,
            'attempstatus':$rootScope.renderQuestion[$scope.currentQues-1].attempstatus,
            'userresponse':$rootScope.renderQuestion[$scope.currentQues-1].userresponse,
            'attempt_id':$rootScope.renderQuestion[$scope.currentQues-1].attempt_id,
            'num_question':$rootScope.renderQuestion[$scope.currentQues-1].num_question
        };
        $serverRequest.postData.saveUserResponse( json );
        
    });
    
    $timeout(function(){
        $(window).trigger('resize');        
    });
    
});

app.controller('tableinputentryRenderCtrl', function ($scope, $serverRequest, $timeout, $rootScope) {
    angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (ele, index) {
        if(typeof ele.headerrow == "undefined" || typeof ele.headerrow == "undefined"){
            $rootScope.renderQuestion[$scope.currentQues-1].questions[index].headerrow = true;
            $rootScope.renderQuestion[$scope.currentQues-1].questions[index].headercol = true;
        }
    });
    if(!$rootScope.renderQuestion[$scope.currentQues-1].userresponse){
        $rootScope.renderQuestion[$scope.currentQues-1].userresponse = [];
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {  
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex] = {option:''};
            $rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].option = quesEle.option;        
            
        });         
    }
    
    $scope.$on("SUBMIT_RESPONSE",function () {
        $scope.check = {'attempted' :0,'notattempted' :0};
        
        angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].questions, function (quesEle, quesIndex) {
            angular.forEach($rootScope.renderQuestion[$scope.currentQues-1].userresponse[quesIndex].option, function ( optionEle, optionIndex ) {    
                if( optionIndex !== 0 ){
                    var result = $.grep(optionEle , function(e){ return e.checkvalue === false && e.value === '' && e.title === false; });            
                    if ( result.length === 0 ) {
                        $scope.check.attempted = $scope.check.attempted + 1;
                    } else {
                        if( ( optionEle.length - 1 ) === result.length ){
                            $scope.check.notattempted = $scope.check.notattempted + 1;
                        }
                    }
                    
                }
            });
        });
        if( $scope.check.notattempted && $scope.check.attempted === 0){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'skipped';
        } else if( $scope.check.attempted && $scope.check.notattempted === 0){
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'complete';
        } else {
            $rootScope.renderQuestion[$scope.currentQues-1].attempstatus = 'inprogress';
        }
        
        var json = {
            'id': $rootScope.renderQuestion[$scope.currentQues-1].id,
            'attempstatus':$rootScope.renderQuestion[$scope.currentQues-1].attempstatus,
            'userresponse':$rootScope.renderQuestion[$scope.currentQues-1].userresponse,
            'attempt_id':$rootScope.renderQuestion[$scope.currentQues-1].attempt_id,
            'num_question':$rootScope.renderQuestion[$scope.currentQues-1].num_question
        };    
        $serverRequest.postData.saveUserResponse( json );
        
    });
    
    $timeout(function(){
        $(window).trigger('resize');        
    });
});

/*Controller: Question render section end*/

/*Service: For post user response start*/
app.service( '$serverRequest', function ( $rootScope, $http ) {
    var $serverRequest = this;
    $serverRequest.postData = {
        
        saveUserResponse: function ( json ){
            /*Question Summary update here!: Code Start*/
            $rootScope.quessummary.answered = 0;
            $rootScope.quessummary.skipped = 0;
             angular.forEach($rootScope.renderQuestion, function (quesEle, quesIndex) {
                if( quesEle.attempstatus === 'inprogress' || quesEle.attempstatus === 'complete'){
                    $rootScope.quessummary.answered = $rootScope.quessummary.answered + 1;
                } else if ( quesEle.attempstatus === 'skipped'){
                    $rootScope.quessummary.skipped = $rootScope.quessummary.skipped + 1;
                }

            });
            $rootScope.quessummary.remaining = $rootScope.quessummary.totalques - ($rootScope.quessummary.answered+$rootScope.quessummary.skipped);  
            /*Question Summary update here! : Code End*/
            
            var modifyData = {
              'questionsummary' : $rootScope.quessummary,
              'questions' : json
            };
            
            $http ({
                method: 'POST',
                url: $rootScope.api['userresponse'],
                data: modifyData,
                headers: {
                    'Content-Type': 'application/json'
                }
            }).success ( function ( data, status, headers, config ) {
                
            }).error ( function ( data, status, headers, config ) {
                if(data=='Unauthorized.'){
                    location.reload();
                }
            });
        }
    };
    
});
/*Service: For post user response end*/
