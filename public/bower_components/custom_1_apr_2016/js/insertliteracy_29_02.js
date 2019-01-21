/**
 * Created by sunny.rana on 28-08-2015.
 */
'use strict';

/**
 * @ngdoc function
 * @name questionBuilder.controller:insertliteracyCtrl
 * @description
 * # insertliteracyCtrl
 * Controller of the questionBuilder
 */
var app = angular.module('questionBuilder');

app.controller( 'insertliteracyCtrl' , function ( $scope, $window, $sce, $rootScope, $routeParams, $modal, Flash, $http ) {

    //Accordion scope.
    $scope.oneAtATime = true;
    $scope.isFirstOpen = true;

    $scope.showAnswerEle = '';
    $scope.disabledQuestion = false;
    $scope.questionText = '';
    $scope.correctOption = [];
    $scope.questionCode = $scope.selected.questiontype;
    $scope.questionType = $rootScope.MASTERS.questiontype[$scope.questionCode];
    $scope.addQuesBtnState = false;
    $scope.dragObjArray = ['.', ',', ':', ';', '?', '!', " ' ", '"', '_', '-', '...', '(', ')'];
    
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
            correctAns: [],
            correctmark: [{val: '', marks: ''}],
            mark: 1,
            questiontype: $scope.questionCode
        });
        var questionIndex = $scope.questionJSON.questions.length;
        var questionName = 'questionText' + (questionIndex);
        setTimeout(function () {
            CKEDITOR.inline(questionName, {
                extraPlugins: 'mathjax'
            });
        });
    };

    /*Remove question here*/
    $scope.removeQuestion = function (parentIndex) {
        if ($scope.questionJSON.questions.length > 1) {
            angular.forEach($scope.questionJSON.questions, function (ele, index) {
                var questionName = 'questionText' + (index + 1);
                CKEDITOR.instances[questionName].destroy();
                if ($scope.questionJSON.questions.length == (index + 1)) {
                    $scope.questionJSON.questions.splice(parentIndex, 1);
                    $scope.populateckeditor({action: 'remove', index: parentIndex});
                }
            });

        }
    };
    
    /*Validate question before sumbit*/
    $scope.submitValidateQues = function(){
        $scope.quesvalidate = true
        $scope.incompleteArray = '';
        
        angular.forEach($scope.questionJSON.questions, function (ele, questionIndex) {
            
            angular.forEach($('#textEditor'+questionIndex).find('input[name^="qus_ans'+questionIndex+'"]'), function (ele, indexChild) {
                $scope.questionJSON.questions[questionIndex].correctAns.push($(ele).val());
            });
            
            if ( $scope.questionJSON.questions[questionIndex].correctAns.length === 0 ) {
                $scope.incompleteArray = $scope.incompleteArray !== '' ? $scope.incompleteArray+', '+(questionIndex+1) : questionIndex+1;
                $scope.quesvalidate = false;
            } else {                
                var result = $.grep($scope.questionJSON.questions[questionIndex].correctAns , function(e){ return e !== ''; });            
                if ( result.length === 0 ) {
                    $scope.incompleteArray = $scope.incompleteArray !== '' ? $scope.incompleteArray+', '+(questionIndex+1) : questionIndex+1;
                    $scope.quesvalidate = false;
                }
            }
            
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
    $scope.submitInsertLiteracyCQ = function ( navigateTo, actionMode ) {
        
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
    
    $scope.removeCorrectmark = function (parentIndex, index) {
        $scope.questionJSON.questions[parentIndex].correctmark.splice(index, 1);
    };
    
    $scope.addCorrectmark = function (parentIndex) {
        if ($scope.questionJSON.questions[parentIndex].ques.split(' ').length - 1 > $scope.questionJSON.questions[parentIndex].correctmark.length) {
            var tempVar = $scope.questionJSON.questions[parentIndex].correctmark;
            var valueInput = tempVar[tempVar.length - 1].val ? tempVar[tempVar.length - 1].val - 1 : ($scope.questionJSON.questions[parentIndex].ques.split(' ').length - 1);
            var marksInput = tempVar[tempVar.length - 1].marks ? tempVar[(tempVar.length) - 1].marks - 1 : (parseInt($('#mark' + parentIndex).val(), 10) - 1);
            if ( valueInput === 0 ) {
                valueInput = $scope.questionJSON.questions[parentIndex].ques.split(' ').length;
            }
            if ( marksInput === 0 ) {
                marksInput = parseInt($('#mark' + parentIndex).val(), 10);
            }
            $scope.questionJSON.questions[parentIndex].correctmark.push({val: valueInput, marks: marksInput});
        } else {
            var message = '<strong>'+$rootScope.MASTERS.keywords['typeerror']+'</strong> Max ' + ($scope.questionJSON.questions[parentIndex].ques.split(' ').length - 1) + ' option allowed.';
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
    $scope.setAnswer = function (questionIndex) {
        $scope.questionJSON.questions[questionIndex].mark = parseInt($('#mark' + questionIndex).val(), 10);
        $scope.validatedVal = $scope.validateQuestion(questionIndex);
        if ($scope.validatedVal) {
            $scope.showAnswerEle = questionIndex + 1;
            $scope.addQuesBtnState = true;
            
            if ($scope.questionJSON.questions[questionIndex].correctmark.length === 1 && $scope.questionJSON.questions[questionIndex].correctmark[0].val === '') {
                angular.forEach($scope.questionJSON.questions[questionIndex].correctmark, function (childEle, childindex) {
                    $scope.questionJSON.questions[questionIndex].correctmark[childindex].val = 1;
                    $scope.questionJSON.questions[questionIndex].correctmark[childindex].marks = parseInt($('#mark' + questionIndex).val(), 10);
                });
            }
            if( !$scope.questionJSON.id || $scope.questionJSON.questions[questionIndex].ques !== CKEDITOR.instances['questionText' + $scope.showAnswerEle].getData()){
                $scope.questionJSON.questions[questionIndex].correctAns = [];
            }
            
            CKEDITOR.instances['questionText' + $scope.showAnswerEle].setReadOnly(true);
            $scope.questionJSON.questions[questionIndex].ques = CKEDITOR.instances['questionText' + $scope.showAnswerEle].getData();
            $('#textEditor'+questionIndex).html( $scope.questionJSON.questions[questionIndex].ques.split(' ').join('&nbsp;<input type="text" name="qus_ans'+questionIndex+'" class="droppable insert-literacy-text" >&nbsp;') );

            setTimeout(function () {
                $('.draggable').draggable({revert: function(dropped) {return !dropped;}, helper: 'clone', zIndex: 1});
                $('.droppable').droppable({
                    hoverClass: 'ui-state-hover',
                    drop: function (e, ui) {
                        $(this).val($(ui.draggable).clone().html());
                    }
                });
                
                angular.forEach($('#textEditor'+questionIndex).find('input[name^="qus_ans'+questionIndex+'"]'), function (ele, indexChild) {
                    $($('#textEditor'+questionIndex).find('input[name^="qus_ans'+questionIndex+'"]')[indexChild]).val($scope.questionJSON.questions[questionIndex].correctAns[indexChild]);            
                });
                
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
                templateUrl: 'insertliteracyQuestionPre',
                controller: 'questionPreILCtrl',
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
                $scope.questionJSON.questions[json.index].option.push({left: '', right: ''});
            } else if (json.action) {
                //Do here if required anything in action remove.
            }
        };

        angular.forEach($scope.questionJSON.questions, function (ele, index) {
            var questionName = 'questionText' + (index + 1);
            setTimeout(function () {
                CKEDITOR.inline(questionName, {extraPlugins: 'mathjax'});
            });
        });

    };

    setTimeout(function () {
        CKEDITOR.config.toolbar = $rootScope.MASTERS.editorConfig;
        CKEDITOR.inline('descriptionText', {extraPlugins: 'mathjax'});
        $scope.populateckeditor();

    }, 10);

});

app.controller('questionPreILCtrl', function ( $scope, $modalInstance, questionPre, $http, $timeout, $modal ) {
    $scope.quesIndex = questionPre.questionindex;
    $scope.quesArray = questionPre.assignment;
    $scope.dragObjArray = ['.', ',', ':', ';', '?', '!', " ' ", '"', '_', '-', '...', '(', ')'];
    $scope.tempQuestext = $scope.quesArray.questions[$scope.quesIndex].ques.split(' ').join('&nbsp;<input type="text" name="qus_ans[]" class="droppable insert-literacy-text drop-inputbox" >&nbsp;');
   
    $timeout(function () {
        $('.draggable').draggable({revert: function(dropped) {return !dropped;}, helper: 'clone', zIndex: 1});
        $('.droppable').droppable({
            hoverClass: 'active',
            drop: function (e, ui) {
                $(this).val($(ui.draggable).clone().html());
            }
        });

    });
    
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
