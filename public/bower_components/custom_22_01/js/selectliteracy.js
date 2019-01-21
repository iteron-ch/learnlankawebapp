/**
 * Created by sunny.rana on 26-08-2015.
 */
'use strict';

/**
 * @ngdoc function
 * @name questionBuilder.controller:selectliteracyCtrl
 * @description
 * # selectliteracyCtrl
 * Controller of the questionBuilder
 */
var app = angular.module('questionBuilder');
app.controller( 'selectliteracyCtrl' , function ( $scope, $window, $sce, $rootScope, $routeParams, $modal, Flash, $http ) {

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
                    option: [
                        {option: '', ischeck: false},
                        {option: '', ischeck: false},
                        {option: '', ischeck: false}
                    ],
                    correctAns: [],
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
            option: [{option: '', ischeck: false},
                {option: '', ischeck: false},
                {option: '', ischeck: false}],
            correctAns: [],
            correctmark: [{val: '', marks: ''}],
            mark: 1,
            questiontype: $scope.questionCode
        });

        var questionIndex = $scope.questionJSON.questions.length;
        var questionName = 'questionText' + (questionIndex);
        angular.forEach($scope.questionJSON.questions[questionIndex - 1].option, function (childEle, childindex) {
            var optionName = 'option' + (questionIndex) + (childindex + 1);
            setTimeout(function () {
                CKEDITOR.inline(optionName, {extraPlugins: 'mathjax'});
            }, 100);

        });

        setTimeout(function () {
            CKEDITOR.inline(questionName, {extraPlugins: 'mathjax'});
        }, 100);

    };

    /*Add option here*/
    $scope.addOption = function (parentIndex) {
        $scope.questionJSON.questions[parentIndex].option.push({option: ''});
        var instanceRef = 'option' + (parentIndex + 1) + $scope.questionJSON.questions[parentIndex].option.length;
        setTimeout(function () {
            CKEDITOR.inline(instanceRef, {extraPlugins: 'mathjax'});
        });
    };

    /*Remove option here*/
    $scope.removeOption = function (parentIndex, optionIndex) {
        $scope.questionJSON.questions[parentIndex].correctAns = $scope.questionJSON.questions[parentIndex].correctAns === optionIndex ? '' : $scope.questionJSON.questions[parentIndex].correctAns;

        if ($scope.questionJSON.questions[parentIndex].option.length > 2) {
            angular.forEach($scope.questionJSON.questions[parentIndex].option, function (ele, index) {
                var instanceRef = 'option' + (parentIndex + 1) + (index + 1);
                CKEDITOR.instances[instanceRef].destroy();
                if ($scope.questionJSON.questions[parentIndex].option.length == (index + 1)) {
                    $scope.questionJSON.questions[parentIndex].option.splice(optionIndex, 1);
                    $scope.populateckeditor({action: 'remove', index: parentIndex});

                }
            });
        }
    };

    /*Remove question here*/
    $scope.removeQuestion = function (parentIndex) {
        if ($scope.questionJSON.questions.length > 1) {

            angular.forEach($scope.questionJSON.questions, function (ele, index) {
                var questionName = 'questionText' + (index + 1);
                CKEDITOR.instances[questionName].destroy();
                angular.forEach(ele.option, function (childEle, childindex) {
                    var optionName = 'option' + (index + 1) + (childindex + 1);
                    CKEDITOR.instances[optionName].destroy();

                });
                if ($scope.questionJSON.questions.length === (index + 1)) {
                    $scope.questionJSON.questions.splice(parentIndex, 1);
                    $scope.populateckeditor({action: 'remove', index: parentIndex});
                }
            });

        }
    };

    /*Capture correct option here*/
    $scope.changeSelectedOption = function (parentIndex, val) {
        $scope.questionJSON.questions[parentIndex].correctAns = val;
    };
    
    /*Validate question before sumbit*/
    $scope.submitValidateQues = function(){
        $scope.quesvalidate = true
        $scope.incompleteArray = '';
        
        angular.forEach($scope.questionJSON.questions, function (ele, questionIndex) {
            $scope.questionJSON.questions[questionIndex].correctAns = [];
            angular.forEach($scope.questionJSON.questions[questionIndex].option, function (childEle, childindex) {
                var optionName = 'option' + (questionIndex + 1) + (childindex + 1);
                $scope.questionJSON.questions[questionIndex].option[childindex].option = CKEDITOR.instances[optionName].getData();
                if ($scope.questionJSON.questions[questionIndex].option[childindex].ischeck) {
                    var answerID = 'answer_' + questionIndex + childindex;
                    $scope.questionJSON.questions[questionIndex].correctAns.push($('#' + answerID).val());
                }

            });
            
            console.log( $scope.questionJSON.questions[questionIndex].correctAns );
            if ( $scope.questionJSON.questions[questionIndex].correctAns.length === 0 ) {
                $scope.incompleteArray = $scope.incompleteArray !== '' ? $scope.incompleteArray+', '+(questionIndex+1) : questionIndex+1;
                $scope.quesvalidate = false;
            } else {
                 angular.forEach($scope.questionJSON.questions[questionIndex].correctAns, function (ele, index) {
                    if(ele === '' && $scope.quesvalidate){
                        $scope.incompleteArray = $scope.incompleteArray !== '' ? $scope.incompleteArray+', '+(questionIndex+1) : questionIndex+1;
                        $scope.quesvalidate = false;
                    }
                })
            }
            
            var questionName = 'questionText' + (questionIndex + 1);
            $scope.questionJSON.questions[questionIndex].ques = CKEDITOR.instances[questionName].getData();
            $scope.questionJSON.questions[questionIndex].mark = parseInt($('#mark' + questionIndex).val(), 10);
            
            if (CKEDITOR.instances[questionName].getData()) {
                
                angular.forEach($scope.questionJSON.questions[questionIndex].option, function (childEle, childindex) {
                    var optionName = 'option' + (questionIndex + 1) + (childindex + 1);
                    $scope.questionJSON.questions[questionIndex].option[childindex].option = CKEDITOR.instances[optionName].getData();
                    if (CKEDITOR.instances[optionName].getData()) {
                    } else {
                        $scope.quesvalidate = false;
                    }
                });

            } else {
                $scope.quesvalidate = false;
                
                angular.forEach($scope.questionJSON.questions[questionIndex].option, function (childEle, childindex) {
                    var optionName = 'option' + (questionIndex + 1) + (childindex + 1);
                    $scope.questionJSON.questions[questionIndex].option[childindex].option = CKEDITOR.instances[optionName].getData();
                    if (CKEDITOR.instances[optionName].getData()) {
                    } else {
                        $scope.quesvalidate = false;
                    }
                });
            }
        });        
        return $scope.quesvalidate;   
    };    
    
    /*Save & Next trigger here*/
    $scope.submitselectliteracyCQ = function ( navigateTo, actionMode ) {
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
                  }, function (response) {});
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
            angular.forEach($scope.questionJSON.questions[questionIndex].option, function (childEle, childindex) {
                var optionName = 'option' + (questionIndex + 1) + (childindex + 1);
                if (CKEDITOR.instances[optionName].getData()) {
                    $($("[name='" + optionName + "']").next()).removeClass('cke_textarea_inline_error');

                } else {
                    //error
                    $($("[name='" + optionName + "']").next()).addClass('cke_textarea_inline_error');
                    $scope.validate = false;

                }
            });

        } else {

            $($("[name='" + questionName + "']").next()).addClass('cke_textarea_inline_error');
            $scope.validate = false;

            angular.forEach($scope.questionJSON.questions[questionIndex].option, function (childEle, childindex) {
                var optionName = 'option' + (questionIndex + 1) + (childindex + 1);
                if (CKEDITOR.instances[optionName].getData()) {
                    $($("[name='" + optionName + "']").next()).removeClass('cke_textarea_inline_error');

                } else {
                    //error
                    $($("[name='" + optionName + "']").next()).addClass('cke_textarea_inline_error');
                    $scope.validate = false;

                }

            });
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
            if ($scope.questionJSON.questions[index].correctmark.length === 1 && $scope.questionJSON.questions[index].correctmark[0].val === '') {
                angular.forEach($scope.questionJSON.questions[index].correctmark, function (childEle, childindex) {
                    $scope.questionJSON.questions[index].correctmark[childindex].val = 1;
                    $scope.questionJSON.questions[index].correctmark[childindex].marks = parseInt($('#mark' + index).val(), 10);
                });
            }
            $scope.questionJSON.questions[index].correctAns = [];
            angular.forEach($scope.questionJSON.questions, function (ele, index) {
                var questionName = 'questionText' + (index + 1);
                angular.forEach(ele.option, function (childEle, childindex) {
                    var optionName = 'option' + (index + 1) + (childindex + 1);
                    $scope.questionJSON.questions[index].option[childindex].option = CKEDITOR.instances[optionName].getData();
                    CKEDITOR.instances[optionName].setReadOnly(true);

                });
                $scope.questionJSON.questions[index].ques = CKEDITOR.instances[questionName].getData();
                CKEDITOR.instances[questionName].setReadOnly(true);
            });
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
            angular.forEach(ele.option, function (childEle, childindex) {
                var optionName = 'option' + (index + 1) + (childindex + 1);
                CKEDITOR.instances[optionName].setReadOnly(false);

            });
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
                angular.forEach(ele.option, function (childEle, childindex) {
                    var optionName = 'option' + (index + 1) + (childindex + 1);
                    $scope.questionJSON.questions[index].option[childindex].option = CKEDITOR.instances[optionName].getData();

                });

            });

            var modalInstance = $modal.open({
                templateUrl: 'selectliteracyPre',
                controller: 'selectliteracyPreCtrl',
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

        if (json) {
            if (json.action === 'add') {
                $scope.questionJSON.questions[json.index].option.push({option: ''});
            } else if (json.action) {
                //Do here if required anything in action remove.
            }
        };

        angular.forEach($scope.questionJSON.questions, function (ele, index) {
            var questionName = 'questionText' + (index + 1);
            angular.forEach(ele.option, function (childEle, childindex) {
                var optionName = 'option' + (index + 1) + (childindex + 1);
                setTimeout(function () {
                    CKEDITOR.inline(optionName, {extraPlugins: 'mathjax'});
                });

            });

            if (CKEDITOR.instances[questionName]) {
                CKEDITOR.instances[questionName].destroy();
                setTimeout(function(){
                    CKEDITOR.inline(questionName, {extraPlugins: 'mathjax'});
                });

            } else {
                setTimeout(function () {
                    CKEDITOR.inline(questionName, {extraPlugins: 'mathjax'});
                }, 10);

            }

        });

    };

    setTimeout(function () {
        CKEDITOR.config.toolbar = $rootScope.MASTERS.editorConfig;
        CKEDITOR.inline('descriptionText', {extraPlugins: 'mathjax'});
        $scope.populateckeditor();

    }, 10);

});

app.controller('selectliteracyPreCtrl', function ( $scope, $modalInstance, questionPre, $http, $modal ) {

    $scope.quesIndex = questionPre.questionindex;
    $scope.quesArray = questionPre.assignment;

    $scope.ok = function () {
        $modalInstance.dismiss('cancel');
    };

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
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

});
