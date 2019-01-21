/**
 * Created by sunny.rana on 26-11-2015.
 */
'use strict';

/**
 * @ngdoc function
 * @name questionBuilder.controller:shadingshapeCtrl
 * @description
 * # shadingshapeCtrl
 * Controller of the questionBuilder
 */
var app = angular.module('questionBuilder');
app.controller( 'shadingshapeCtrl' , function ( $scope, $rootScope, $routeParams, $modal, Flash, $http, $timeout ) {

    //Accordion scope.
    $scope.oneAtATime = true;
    $scope.isFirstOpen = true;

    $scope.submittedForm = false;
    $scope.showAnswerEle = '';
    $scope.questionCode = $scope.selected.questiontype;
    $scope.correctOption = '';
    $scope.questionType = $rootScope.MASTERS.questiontype[$scope.questionCode];
    $scope.addQuesBtnState = false;
    if( $scope.questionData ) {
        $scope.questionJSON = angular.copy( $scope.questionData );
    } else {
        $scope.questionJSON = {
            questionparam:$scope.selected,
            description: '',
            descvisible: true,
            subjecttype: $routeParams.subject,
            questiontype: $scope.questionCode,
            questions: [
                {
                    ques: '',
                    option: {'column' : 5, 'row' : 5, 'optionval' : [
                            [{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''}],
                            [{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''}],
                            [{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''}],
                            [{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''}],
                            [{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''}]
                        ]},
                    correctAns: [
                            [{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''}],
                            [{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''}],
                            [{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''}],
                            [{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''}],
                            [{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''}]
                        ],
                    correctmark: [{val: '', marks: ''}],
                    mark: 1,
                    questiontype: $scope.questionCode
                }
            ]
        };
    }

    /*Add question here*/
    $scope.questionAdd = function () {
        $scope.questionJSON.questions.push({
            ques: '',
            option: {'column' : 5, 'row' : 5, 'optionval' : [
                            [{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''}],
                            [{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''}],
                            [{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''}],
                            [{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''}],
                            [{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''}]
                        ]},
                    correctAns: [
                            [{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''}],
                            [{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''}],
                            [{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''}],
                            [{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''}],
                            [{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''},{'class' : ''}]
                        ],
            correctmark: [{val: '', marks: ''}],
            mark: 1,
            questiontype: $scope.questionCode
        });

        var questionIndex = $scope.questionJSON.questions.length;
        var questionName = 'questionText' + (questionIndex);
        
        $timeout(function () {
            CKEDITOR.inline(questionName, {extraPlugins: 'mathjax'});
            $scope.bindElement();
        }, 100);
        
    };
  

    /*Remove question here*/
    $scope.removeQuestion = function (parentIndex) {
        
        if ($scope.questionJSON.questions.length > 1) {

            angular.forEach($scope.questionJSON.questions, function (ele, index) {
                var questionName = 'questionText' + (index + 1);
                CKEDITOR.instances[questionName].destroy();
                
                if ($scope.questionJSON.questions.length === (index + 1)) {
                    $scope.questionJSON.questions.splice(parentIndex, 1);
                }                
            });

        }
    };
    
    $scope.updateobject = function( parentIndex ){
        $scope.questionJSON.questions[parentIndex].correctAns = [];
        $scope.questionJSON.questions[parentIndex].option.optionval = [];
        for( var i=0; i < $scope.questionJSON.questions[parentIndex].option.row ; i++ ){
            $scope.questionJSON.questions[parentIndex].option.optionval[i] = [];
            $scope.questionJSON.questions[parentIndex].correctAns[i] = [];
            for( var j=0; j < $scope.questionJSON.questions[parentIndex].option.column ; j++ ){
                $scope.questionJSON.questions[parentIndex].option.optionval[i][j] = {'class':''};
                $scope.questionJSON.questions[parentIndex].correctAns[i][j] = {'class':''};
            }
        }
        $timeout(function(){
            $scope.bindElement();
        });
        //$scope.questionJSON.questions[parentIndex].option.optionval = [];
    };
    
    /*Validate question before sumbit*/
    $scope.submitValidateQues = function(){
        $scope.quesvalidate = true
        $scope.incompleteArray = '';
        
        angular.forEach($scope.questionJSON.questions, function (ele, questionIndex) {
            /*var result = $.grep($scope.questionJSON.questions[questionIndex].correctAns , function(e){ return e.ischeck == true; });
            
            if ( result.length === 0 ) {
                $scope.incompleteArray = $scope.incompleteArray !== '' ? $scope.incompleteArray+', '+(questionIndex+1) : questionIndex+1;
                $scope.quesvalidate = false;
            }*/
            
            var questionName = 'questionText' + (questionIndex + 1);
            $scope.questionJSON.questions[questionIndex].ques = CKEDITOR.instances[questionName].getData();
            $scope.questionJSON.questions[questionIndex].mark = parseInt($('#mark' + questionIndex).val(), 10);
            
            if (CKEDITOR.instances[questionName].getData()) {} else {
                $scope.quesvalidate = false;
            }
        });        
        return $scope.quesvalidate;   
    };    
    
    /*Save & Next trigger here*/
    $scope.submitshadingshapeCQ = function ( navigateTo, actionMode ) {
        if($scope.submitValidateQues()){
            $scope.questionJSON.questionparam.dynamicId1 = $scope.selected.dynamicId1;
            $scope.questionJSON.questionparam.dynamicId2 = $scope.selected.dynamicId2;
            $scope.questionJSON.questionparam.quesnote = $scope.selected.quesnote;
            $scope.questionJSON.description = CKEDITOR.instances['descriptionText'].getData();

            var modalInstanceConfirm = $modal.open({
                templateUrl: 'confirmPopUp',
                controller: 'confirmCtrl',
                size: 'sm',
                scope: $scope
            });

            modalInstanceConfirm.result.then(function () {
                $http.post('/questionbuilder', {data: angular.copy($scope.questionJSON)}).
                  then(function ( response ) {
                      if( response.data.questionReference ){
                            $scope.selected.dynamicId1 = response.data.dynamicId1;
                            var message = '<strong>'+$rootScope.MASTERS.keywords['typesuccess']+'</strong> '+ $rootScope.MASTERS.keywords['questionsuccess'];
                            Flash.create('success', message);
                            $scope.refreshQuestionSection( navigateTo, actionMode );
                      }
                  }, function ( response ) {});
                console.log('Modal close at: ' + new Date());
            }, function () {
                console.log('Modal dismissed at: ' + new Date());
            });
            
        } else {
            var message = '<strong>'+$rootScope.MASTERS.keywords['typeerror']+'</strong> '+ $rootScope.MASTERS.keywords['alertsubmitanswer'] + $scope.incompleteArray;
            Flash.create('danger', message);

        }
    };

    /*Validate for question data is entered or not*/
    $scope.validateQuestion = function (questionIndex) {
        $scope.validate = true;

        //Auto remove red border after 5 sec.
        $scope.tempQuestionIndex = questionIndex;
        setTimeout(function () {
            var questionName = 'questionText' + ($scope.tempQuestionIndex + 1);
            $($("[name='" + questionName + "']").next()).removeClass('cke_textarea_inline_error');
            angular.forEach($scope.questionJSON.questions[$scope.tempQuestionIndex].option, function (childEle, childindex) {
                var optionName = 'option' + ($scope.tempQuestionIndex + 1) + (childindex + 1);
                $($("[name='" + optionName + "']").next()).removeClass('cke_textarea_inline_error');
            });
        }, 5000);

        var questionName = 'questionText' + (questionIndex + 1);
        if (CKEDITOR.instances[questionName].getData()) {
            $($("[name='" + questionName + "']").next()).removeClass('cke_textarea_inline_error');

        } else {
            $($("[name='" + questionName + "']").next()).addClass('cke_textarea_inline_error');
            $scope.validate = false;
            
        }

        return $scope.validate;
    };

    /*Set Answer trigger here*/
    $scope.setAnswer = function (index) {
        $scope.questionJSON.questions[index].mark = parseInt($('#mark' + index).val(), 10);
        $scope.validatedVal = $scope.validateQuestion(index);
        if ($scope.validatedVal) {
            $scope.showAnswerEle = index + 1;
            $scope.addQuesBtnState = true;
            if ($scope.questionJSON.questions[index].correctmark.length === 1) {
                angular.forEach($scope.questionJSON.questions[index].correctmark, function (childEle, childindex) {
                    $scope.questionJSON.questions[index].correctmark[childindex].val = 1;
                    $scope.questionJSON.questions[index].correctmark[childindex].marks = parseInt($('#mark' + index).val(), 10);
                });
            }
            angular.forEach($scope.questionJSON.questions, function (ele, index) {
                var questionName = 'questionText' + (index + 1);
                $scope.questionJSON.questions[index].ques = CKEDITOR.instances[questionName].getData();
                CKEDITOR.instances[questionName].setReadOnly(true);
            });
            $scope.bindCorrectAnswer();
        } else {
            var message = '<strong>'+$rootScope.MASTERS.keywords['typeerror']+'</strong> '+ $rootScope.MASTERS.keywords['alertsetanswer'];
            Flash.create('danger', message);

        }

    };

    /*Edit Question trigger here*/
    $scope.editQuestion = function () {
        $scope.showAnswerEle = '';
        $scope.addQuesBtnState = false;
        angular.forEach($scope.questionJSON.questions, function (ele, index) {
            var questionName = 'questionText' + (index + 1);
            CKEDITOR.instances[questionName].setReadOnly(false);
        });

    };

    /*Preview Question trigger here*/
    $scope.previewQuestion = function (preQuesIndex, popupSize) {

        $scope.validatedVal = $scope.validateQuestion(preQuesIndex);
        if ($scope.validatedVal) {
            //Here Update question object. 
            $scope.questionJSON.description = CKEDITOR.instances['descriptionText'].getData();
            angular.forEach($scope.questionJSON.questions, function (ele, index) {
                var questionName = 'questionText' + (index + 1);
                $scope.questionJSON.questions[index].ques = CKEDITOR.instances[questionName].getData();
                $scope.questionJSON.questions[index].mark = parseInt($('#mark' + index).val(), 10);

            });

            var modalInstance = $modal.open({
                templateUrl: 'shadingshapeQuestionPre',
                controller: 'preShadingShapeCtrl',
                size: popupSize,
                scope: $scope,
                resolve: {
                    questionPre: function () {
                        return {
                            'assignment': $scope.questionJSON,
                            'questionindex': preQuesIndex
                        };
                    }
                }
            });

            modalInstance.result.then(function () {
                console.log('Modal close at: ' + new Date());
            }, function () {
                console.log('Modal dismissed at: ' + new Date());
            });

        } else {

            var message = '<strong>'+$rootScope.MASTERS.keywords['typeerror']+'</strong> '+ $rootScope.MASTERS.keywords['alertpreview'];
            Flash.create('danger', message);
        }

    };

    /* Populate ckeditor here */
    $scope.populateckeditor = function (json) {

        angular.forEach($scope.questionJSON.questions, function (ele, index) {
            var questionName = 'questionText' + (index + 1);

            if (CKEDITOR.instances[questionName]) {
                CKEDITOR.instances[questionName].destroy();
                $timeout(function () {
                    CKEDITOR.inline(questionName, {extraPlugins: 'mathjax'});
                });
            } else {
                $timeout(function () {
                    CKEDITOR.inline(questionName, {extraPlugins: 'mathjax'});
                }, 100);

            }

        });

    };
    
    $scope.bindElement = function(){
        angular.forEach($scope.questionJSON.questions, function (ele, index) {
            $($('#questionshape'+index).find('td')).unbind( 'click' ).bind( 'click', function() {
                if( !$scope.addQuesBtnState ){
                    var splitObj = $(this).attr('id').split('_');
                    if ($scope.questionJSON.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class === 'first-cell' ) {
                        $scope.questionJSON.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class = 'second-cell';
                        $scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = 'second-cell';

                    } else if( $scope.questionJSON.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class === 'second-cell' ){
                        $scope.questionJSON.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class = 'third-cell';
                        $scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = 'third-cell';

                    } else if( $scope.questionJSON.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class === 'third-cell' ){
                        $scope.questionJSON.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class = 'fifth-cell';
                        $scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = 'fifth-cell';

                    } else if( $scope.questionJSON.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class === 'fifth-cell' ){
                        $scope.questionJSON.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class = 'sixth-cell';
                        $scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = 'sixth-cell';

                    } else if( $scope.questionJSON.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class === 'sixth-cell' ){
                        $scope.questionJSON.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class = 'seventh-cell';
                        $scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = 'seventh-cell';

                    }else if( $scope.questionJSON.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class === 'seventh-cell' ){
                        $scope.questionJSON.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class = 'forth-cell';
                        $scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = 'forth-cell';

                    }else if( $scope.questionJSON.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class === 'forth-cell' ){
                        $scope.questionJSON.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class = '';
                        $scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = '';

                    } else {
                        $scope.questionJSON.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class = 'first-cell';
                        $scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = 'first-cell';

                    } 
                    $scope.$digest();
                }
                
            });
        });
    };
    
    $scope.bindCorrectAnswer = function(){
        angular.forEach($scope.questionJSON.questions, function (ele, index) {
            $($('#setAnsShape'+index).find('td')).unbind( 'click' ).bind( 'click', function() {
                
                var splitObj = $(this).attr('id').split('_');                
                if ($scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class === 'first-cell' ) {
                    $scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = 'second-cell';
                    
                } else if( $scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class === 'second-cell' ){
                    $scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = 'third-cell';
                    
                } else if( $scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class === 'third-cell' ){
                    //$scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = 'forth-cell';
                    $scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = 'fifth-cell';

                }else if( $scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class === 'fifth-cell' ){
                    //$scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = 'forth-cell';
                    $scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = 'sixth-cell';

                }else if( $scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class === 'sixth-cell' ){
                    //$scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = 'forth-cell';
                    $scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = 'seventh-cell';

                }else if( $scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class === 'seventh-cell' ){
                    //$scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = 'forth-cell';
                    $scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = '';

                }else if( $scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class === 'forth-cell' ){
                    //$scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = '';

                } else {
                    $scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = 'first-cell';

                } 
                $scope.$digest();
            });
        });
    };
    
    $timeout(function () {
        CKEDITOR.config.toolbar = $rootScope.MASTERS.editorConfig;
        CKEDITOR.inline('descriptionText', {extraPlugins: 'mathjax'});
        $scope.populateckeditor();        
        $scope.bindElement();
    }, 100);

});

app.controller('preShadingShapeCtrl', function ( $scope, $modalInstance, questionPre, $timeout, $http, $modal ) {

    $scope.quesIndex = questionPre.questionindex;
    $scope.quesArray = angular.copy( questionPre.assignment );
    
    $scope.ok = function () {
        $modalInstance.dismiss('cancel');
    };

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };
    
    $scope.bindPreviewShape = function(){
        $($('#preShadingShape'+$scope.quesIndex).find('td')).unbind( 'click' ).bind( 'click', function() {
            var splitObj = $(this).attr('id').split('_');                
            if ($scope.quesArray.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class === 'first-cell' ) {
                $scope.quesArray.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class = 'second-cell';

            } else if( $scope.quesArray.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class === 'second-cell' ){
                $scope.quesArray.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class = 'third-cell';

            } else if( $scope.quesArray.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class === 'third-cell' ){
                //$scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = 'forth-cell';
                $scope.quesArray.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class = 'fifth-cell';

            }else if( $scope.quesArray.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class === 'fifth-cell' ){
                //$scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = 'forth-cell';
                $scope.quesArray.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class = 'sixth-cell';

            }else if( $scope.quesArray.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class === 'sixth-cell' ){
                //$scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = 'forth-cell';
                $scope.quesArray.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class = 'seventh-cell';

            }else if( $scope.quesArray.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class === 'seventh-cell' ){
                //$scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = 'forth-cell';
                $scope.quesArray.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class = '';

            }else if( $scope.quesArray.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class === 'forth-cell' ){
                //$scope.questionJSON.questions[splitObj[1]].correctAns[splitObj[2]][splitObj[3]].class = '';

            } else {
                $scope.quesArray.questions[splitObj[1]].option.optionval[splitObj[2]][splitObj[3]].class = 'first-cell';

            } 
            $scope.$digest();
        });
    };
    
    $scope.validateQuestion = function(){
        
        var modalInstanceConfirm = $modal.open({
                templateUrl: 'confirmvalidatorPopUp',
                controller: 'confirmvalidatorCtrl',
                size: 'sm',
                scope: $scope
            });

        modalInstanceConfirm.result.then(function () {
            var json = {
                'id':$scope.quesArray.id,
                'reason' : $scope.selectedreason,
                'validate_stage' : $scope.validate_stage
            };
            $http.post('/validatequestion', {data: angular.copy(json)})
                .then(function ( response ) {
                    window.location.href = window.location.href.split('questionbuilder')[0]+'questionbuilder';
            }, function (response) {});
            console.log('Modal close at: ' + new Date());
        }, function () {
            console.log('Modal dismissed at: ' + new Date());
        });
        
    };
    
    $timeout(function(){
        $scope.bindPreviewShape();
    },100);
    
});
