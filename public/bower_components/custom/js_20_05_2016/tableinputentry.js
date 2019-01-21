/**
 * Created by sunny.rana on 29-12-2015.
 */
'use strict';

/**
 * @ngdoc function
 * @name questionBuilder.controller:tableinputentryCtrl
 * @description
 * # tableinputentryCtrl
 * Controller of the questionBuilder
 */
var app = angular.module('questionBuilder');
app.controller( 'tableinputentryCtrl' , function ( $scope, $window, $sce, $rootScope, $routeParams, $modal, Flash, $http, $timeout ) {

    //Accordion scope.
    $scope.oneAtATime = true;
    $scope.isFirstOpen = true;

    $scope.submittedForm = false;
    $scope.showAnswerEle = '';
    $scope.questionCode = 'TSME';
    $scope.correctOption = $scope.selected.questiontype;
    $scope.questionType = $rootScope.MASTERS.questiontype[$scope.questionCode];
    $scope.addQuesBtnState = false;
    
    if( $scope.questionData ) {
        $scope.questionJSON = angular.copy( $scope.questionData );
        angular.forEach($scope.questionJSON.questions, function (ele, index) {
            if(typeof ele.headerrow == "undefined" || typeof ele.headerrow == "undefined"){
                $scope.questionJSON.questions[index].headerrow = true;
                $scope.questionJSON.questions[index].headercol = true;
            }
        });
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
                    questype: 'input',
                    option: [
                        [{"value": "", "checkvalue": '', "title": true}, {"value": "", "checkvalue": '', "title": true}, {"value": "", "checkvalue": '', "title": true}, {"value": "", "checkvalue": '', "title": true}],
                        [{"value": "", "checkvalue": '', "title": true}, {"value": "", "checkvalue": false, "title": false}, {"value": "", "checkvalue": false, "title": false}, {"value": "", "checkvalue": false, "title": false}],
                        [{"value": "", "checkvalue": '', "title": true}, {"value": "", "checkvalue": false, "title": false}, {"value": "", "checkvalue": false, "title": false}, {"value": "", "checkvalue": false, "title": false}],
                        [{"value": "", "checkvalue": '', "title": true}, {"value": "", "checkvalue": false, "title": false}, {"value": "", "checkvalue": false, "title": false}, {"value": "", "checkvalue": false, "title": false}]
                    ],
                    headerrow: true,
                    headercol: true,
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
            questype: 'input',
            option: [
                [{"value": "", "checkvalue": '', "title": true}, {"value": "", "checkvalue": '', "title": true}, {"value": "", "checkvalue": '', "title": true}, {"value": "", "checkvalue": '', "title": true}],
                [{"value": "", "checkvalue": '', "title": true}, {"value": "", "checkvalue": false, "title": false}, {"value": "", "checkvalue": false, "title": false}, {"value": "", "checkvalue": false, "title": false}],
                [{"value": "", "checkvalue": '', "title": true}, {"value": "", "checkvalue": false, "title": false}, {"value": "", "checkvalue": false, "title": false}, {"value": "", "checkvalue": false, "title": false}],
                [{"value": "", "checkvalue": '', "title": true}, {"value": "", "checkvalue": false, "title": false}, {"value": "", "checkvalue": false, "title": false}, {"value": "", "checkvalue": false, "title": false}]
            ],
            headerrow: true,
            headercol: true,
            correctAns: [],
            correctmark: [{val: '', marks: ''}],
            mark: 1,
            questiontype: $scope.questionCode
        });

        var questionIndex = $scope.questionJSON.questions.length;
        var questionName = 'questionText' + (questionIndex);
        angular.forEach($scope.questionJSON.questions[questionIndex-1].option, function (oEle, oIndex) {
            angular.forEach(oEle, function (oEleChild, oIndexChild) {
                var optionName = "optionText" + (questionIndex-1) + oIndex + oIndexChild;
                setTimeout(function(){
                    CKEDITOR.inline(optionName, {extraPlugins: 'mathjax'});
                });
            });
        });
        setTimeout(function(){
            CKEDITOR.inline(questionName, {extraPlugins: 'mathjax'});
            if( $scope.questionJSON.questions[questionIndex-1].questype === 'input' ){
                $('#questionId'+(questionIndex-1)).find('.visibility-editor').find('.cke_textarea_inline').css('display', 'none');
            } else {
                $('#questionId'+(questionIndex-1)).find('.visibility-editor').find('.cke_textarea_inline').css('display', 'block');
            }
        });
    };

    /*Remove question here*/
    $scope.removeQuestion = function (parentIndex) {
        if ($scope.questionJSON.questions.length > 1) {

            angular.forEach($scope.questionJSON.questions, function (ele, index) {
                var questionName = 'questionText' + (index + 1);
                CKEDITOR.instances[questionName].destroy();
                if ($scope.questionJSON.questions.length === (index + 1)) {
                    $scope.questionJSON.questions.splice(parentIndex, 1);
                    $scope.populateckeditor({action: 'remove', index: parentIndex});
                }
            });

        }
    };
    
     /*Validate question before sumbit*/
    $scope.submitValidateQues = function(){
        $scope.quesvalidate = true;
        
        angular.forEach($scope.questionJSON.questions, function (ele, questionIndex) {
            
            var questionName = 'questionText' + (questionIndex + 1);
            $scope.questionJSON.questions[questionIndex].ques = CKEDITOR.instances[questionName].getData();
            $scope.questionJSON.questions[questionIndex].mark = parseInt($('#mark' + questionIndex).val(), 10);
            
            if (CKEDITOR.instances[questionName].getData()) {} else {
                $scope.quesvalidate = false;
            }
            
            /*angular.forEach(ele.option, function (oEle, oIndex) {
                angular.forEach(oEle, function (oEleChild, oIndexChild) {
                    var optionName = "optionText" + questionIndex + oIndex + oIndexChild;
                    $scope.questionJSON.questions[questionIndex].option[oIndex][oIndexChild].value = CKEDITOR.instances[optionName].getData();
                    if (CKEDITOR.instances[optionName].getData()  ) {} else {
                        if(oIndex === 0 || oIndexChild === 0){
                            $scope.quesvalidate = false;
                        }                        
                    }
                });
            });*/
            
        });        
        return $scope.quesvalidate;   
    };  
    
    /*Save & Next trigger here*/
    $scope.submittableinputentryCQ = function ( navigateTo, actionMode ) {
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
                      // this callback will be called asynchronously
                      // when the response is available
                  }, function (response) {
                      // called asynchronously if an error occurs
                      // or server returns response with an error status.
                  });
                console.log('Modal close at: ' + new Date());
            }, function () {
                console.log('Modal dismissed at: ' + new Date());
            });
            //angular.copy used for remove "$$hashKey" object element in the json.	
            console.log(angular.copy($scope.questionJSON));
            //$window.location.reload();
        } else {
            var message = '<strong>'+$rootScope.MASTERS.keywords['typeerror']+'</strong> '+ $rootScope.MASTERS.keywords['alertsubmitanswer'] + $scope.incompleteArray;
            Flash.create('danger', message);

        }
    };

    /*Validate for question data is entered or not*/
    $scope.validateQuestion = function (questionIndex) {        
        $scope.validate = true;
        
        if( $scope.questionJSON.questions[questionIndex].option.length !== $scope.questionJSON.questions[questionIndex].correctAns.length ){
            $scope.questionJSON.questions[questionIndex].correctAns = angular.copy($scope.questionJSON.questions[questionIndex].option); 

        }
        var questionName = 'questionText' + (questionIndex + 1);
        $scope.questionJSON.questions[questionIndex].ques = CKEDITOR.instances[questionName].getData();

        angular.forEach($scope.questionJSON.questions[questionIndex].option, function (oEle, oIndex) {
            angular.forEach(oEle, function (oEleChild, oIndexChild) {
                if( oIndex === 0 ||  oIndexChild === 0){
                    var optionName = "optionText" + questionIndex + oIndex + oIndexChild;
                    $scope.questionJSON.questions[questionIndex].option[oIndex][oIndexChild].value = CKEDITOR.instances[optionName].getData();
                } else {
                    $scope.questionJSON.questions[questionIndex].option[oIndex][oIndexChild].value = $('#option_'+questionIndex+'_'+oIndex+'_'+oIndexChild).val(); 
                    if( $scope.questionJSON.questions[questionIndex].option[oIndex][oIndexChild].checkvalue ){
                        $scope.questionJSON.questions[questionIndex].correctAns[oIndex][oIndexChild].checkvalue = $scope.questionJSON.questions[questionIndex].option[oIndex][oIndexChild].checkvalue;
                        $scope.questionJSON.questions[questionIndex].correctAns[oIndex][oIndexChild].value = $('#option_'+questionIndex+'_'+oIndex+'_'+oIndexChild).val();
                    } else {
                        $scope.questionJSON.questions[questionIndex].correctAns[oIndex][oIndexChild].checkvalue = false;
                        $scope.questionJSON.questions[questionIndex].option[oIndex][oIndexChild].value = "";
                    }
               }
            });
        });
        
        //Auto remove red border after 5 sec.
        $scope.tempQuestionIndex = questionIndex;
        setTimeout(function () {
            var questionName = 'questionText' + ($scope.tempQuestionIndex + 1);
            $($("[name='" + questionName + "']").next()).removeClass('cke_textarea_inline_error');
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
            if( $scope.questionJSON.questions[index].option.length !== $scope.questionJSON.questions[index].correctAns.length ){
                $scope.questionJSON.questions[index].correctAns = angular.copy($scope.questionJSON.questions[index].option); 
                
            } else {
                if( $scope.questionJSON.questions[index].option[0].length !== $scope.questionJSON.questions[index].correctAns[0].length ){
                    $scope.questionJSON.questions[index].correctAns = angular.copy($scope.questionJSON.questions[index].option); 
                }
            }
            
            if ($scope.questionJSON.questions[index].correctmark.length === 1 && $scope.questionJSON.questions[index].correctmark[0].val === '') {
                angular.forEach($scope.questionJSON.questions[index].correctmark, function (childEle, childindex) {
                    $scope.questionJSON.questions[index].correctmark[childindex].val = ($scope.questionJSON.questions[index].option.length - 1) * ($scope.questionJSON.questions[index].option[0].length - 1);
                    $scope.questionJSON.questions[index].correctmark[childindex].marks = parseInt($('#mark' + index).val(), 10);
                });
            }
            angular.forEach($scope.questionJSON.questions, function (ele, index) {
                var questionName = 'questionText' + (index + 1);
                $scope.questionJSON.questions[index].ques = CKEDITOR.instances[questionName].getData();
                CKEDITOR.instances[questionName].setReadOnly(true);
                
                angular.forEach(ele.option, function (oEle, oIndex) {
                    angular.forEach(oEle, function (oEleChild, oIndexChild) {
                        if( oIndex === 0 ||  oIndexChild === 0){
                            var optionName = "optionText" + index + oIndex + oIndexChild;
                            $scope.questionJSON.questions[index].option[oIndex][oIndexChild].value = CKEDITOR.instances[optionName].getData();
                            CKEDITOR.instances[optionName].setReadOnly(true);
                        } else {
                            $scope.questionJSON.questions[index].option[oIndex][oIndexChild].value = $('#option_'+index+'_'+oIndex+'_'+oIndexChild).val(); 
                            if( $scope.questionJSON.questions[index].option[oIndex][oIndexChild].checkvalue ){
                                $scope.questionJSON.questions[index].correctAns[oIndex][oIndexChild].checkvalue = $scope.questionJSON.questions[index].option[oIndex][oIndexChild].checkvalue;
                                $scope.questionJSON.questions[index].correctAns[oIndex][oIndexChild].value = $('#option_'+index+'_'+oIndex+'_'+oIndexChild).val();
                            } else {
                                $scope.questionJSON.questions[index].correctAns[oIndex][oIndexChild].checkvalue = false;
                                //$scope.questionJSON.questions[index].correctAns[oIndex][oIndexChild].value = "";
                                $scope.questionJSON.questions[index].option[oIndex][oIndexChild].value = "";
                            }
                       }
                    });
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
            angular.forEach(ele.option, function (oEle, oIndex) {
                angular.forEach(oEle, function (oEleChild, oIndexChild) {
                    if( oIndex === 0 || oIndexChild === 0 ){
                        var optionName = "optionText" + index + oIndex + oIndexChild;
                        CKEDITOR.instances[optionName].setReadOnly(false);
                    }
                });
            });
        });

    };

    /*Preview Question trigger here*/
    $scope.previewQuestion = function (preQuesIndex, popupSize) {
        
        $scope.validatedVal = $scope.validateQuestion(preQuesIndex);
        if ($scope.validatedVal) {
            
            if( $scope.questionJSON.questions[preQuesIndex].option.length !== $scope.questionJSON.questions[preQuesIndex].correctAns.length ){
                $scope.questionJSON.questions[preQuesIndex].correctAns = angular.copy($scope.questionJSON.questions[preQuesIndex].option); 
                
            } else {
                if( $scope.questionJSON.questions[preQuesIndex].option[0].length !== $scope.questionJSON.questions[preQuesIndex].correctAns[0].length ){
                    $scope.questionJSON.questions[preQuesIndex].correctAns = angular.copy($scope.questionJSON.questions[preQuesIndex].option); 
                }
            }
                $scope.questionJSON.description = CKEDITOR.instances['descriptionText'].getData();
                var questionName = 'questionText' + (preQuesIndex + 1);
                $scope.questionJSON.questions[preQuesIndex].ques = CKEDITOR.instances[questionName].getData();
                $scope.questionJSON.questions[preQuesIndex].mark = parseInt($('#mark' + preQuesIndex).val(), 10);
                
                angular.forEach($scope.questionJSON.questions[preQuesIndex].option, function (oEle, oIndex) {
                    angular.forEach(oEle, function (oEleChild, oIndexChild) {
                        if( oIndex === 0 ||  oIndexChild === 0){
                            var optionName = "optionText" + preQuesIndex + oIndex + oIndexChild;
                            $scope.questionJSON.questions[preQuesIndex].option[oIndex][oIndexChild].value = CKEDITOR.instances[optionName].getData();
                        } else {                            
                            $scope.questionJSON.questions[preQuesIndex].option[oIndex][oIndexChild].value = $('#option_'+preQuesIndex+'_'+oIndex+'_'+oIndexChild).val(); 
                       }
                    });
                });
                
                   
            $timeout(function(){
                var modalInstance = $modal.open({
                    templateUrl: 'tableinputentryPre',
                    controller: 'tableinputentryPreCtrl',
                    size: popupSize,
                    scope: $scope,
                    resolve: {
                        questionPre: function () {
                            return {
                                'assignment': angular.copy($scope.questionJSON),
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

            if (CKEDITOR.instances[questionName]) {
                CKEDITOR.instances[questionName].destroy();
                setTimeout(function () {
                    CKEDITOR.inline(questionName, {extraPlugins: 'mathjax'});
                });

            } else {
                setTimeout(function () {
                    CKEDITOR.inline(questionName, {extraPlugins: 'mathjax'});
                }, 10);

            }
            angular.forEach($scope.questionJSON.questions[index].option, function (oEle, oIndex) {
                angular.forEach(oEle, function (oEleChild, oIndexChild) {
                    if( oIndex === 0 || oIndexChild === 0 ){
                        var optionName = "optionText" + index + oIndex + oIndexChild;
                        if (CKEDITOR.instances[optionName]) {
                            CKEDITOR.instances[optionName].destroy();
                            setTimeout(function(){
                                CKEDITOR.inline(optionName, {extraPlugins: 'mathjax'});
                            });

                        } else {
                            setTimeout(function(){
                                CKEDITOR.inline(optionName, {extraPlugins: 'mathjax'});
                            });
                        }
                    }
                });
            });
            setTimeout(function(){
                if( ele.questype === 'input' ){
                    $('#questionId'+index).find('.visibility-editor').find('.cke_textarea_inline').css('display', 'none');
                } else {
                    $('#questionId'+index).find('.visibility-editor').find('.cke_textarea_inline').css('display', 'block');
                }
                $('#questionId'+index).css('visibility', 'visible');
            },100);
        });
    };

    // Add a column
    $scope.addColumn = function (index) {
        $('#questionId'+index).css('visibility', 'hidden');
        angular.forEach($scope.questionJSON.questions[index].option, function (oEle, oIndex) {
            if (oIndex === 0) {
                $scope.questionJSON.questions[index].option[oIndex].push({"value": "", "checkvalue": '', "title": true});
            } else {
                $scope.questionJSON.questions[index].option[oIndex].push({"value": "", "checkvalue": false, "title": false});
            }
            angular.forEach(oEle, function (oEleChild, oIndexChild) {
                var optionName = "optionText" + index + oIndex + oIndexChild;
                if( oIndexChild === 0 || oIndex === 0){
                    if (CKEDITOR.instances[optionName]) {
                        CKEDITOR.instances[optionName].destroy();
                        setTimeout(function(){
                            CKEDITOR.inline(optionName, {extraPlugins: 'mathjax'});
                        });
                    } else {
                        setTimeout(function(){
                            CKEDITOR.inline(optionName, {extraPlugins: 'mathjax'});
                        });
                    }
                }
            });
        });
        setTimeout(function(){
            if( $scope.questionJSON.questions[index].questype === 'input' ){
                $('#questionId'+index).find('.visibility-editor').find('.cke_textarea_inline').css('display', 'none');
            } else {
                $('#questionId'+index).find('.visibility-editor').find('.cke_textarea_inline').css('display', 'block');
            }
            $('#questionId'+index).css('visibility', 'visible');
        },100);

    };

    // Remove the selected column
    $scope.removeColumn = function (pIndex, index) {
        $('#questionId'+index).css('visibility', 'hidden');
        angular.forEach($scope.questionJSON.questions[pIndex].option, function (childEle, childindex) {
            $scope.questionJSON.questions[pIndex].option[childindex].splice(index, 1);
            if( $scope.questionJSON.questions[pIndex].option.length === childindex+1){
                $scope.populateckeditor();
            }
        });

    };

    // remove the selected row
    $scope.removeRow = function (pIndex, index) {
        $('#questionId'+index).css('visibility', 'hidden');
        $scope.questionJSON.questions[pIndex].option.splice(index, 1);
        $scope.populateckeditor();
    };

    //add a row in the array
    $scope.addRow = function (index) {
        $('#questionId'+index).css('visibility', 'hidden');
        var tempArray = [];
        angular.forEach($scope.questionJSON.questions[index].option[0], function (childEle, childindex) {
            if (childindex === 0) {
                tempArray.push({"value": "", "checkvalue": '', "title": true});
            } else {
                tempArray.push({"value": "", "checkvalue": false, "title": false});
            }
        });
        $scope.questionJSON.questions[index].option.push(tempArray);
        angular.forEach($scope.questionJSON.questions[index].option, function (oEle, oIndex) {
            angular.forEach(oEle, function (oEleChild, oIndexChild) {
                var optionName = "optionText" + index + oIndex + oIndexChild;
                if( oIndexChild === 0 || oIndex === 0){
                    if (CKEDITOR.instances[optionName]) {
                        CKEDITOR.instances[optionName].destroy();
                        setTimeout(function(){
                            CKEDITOR.inline(optionName, {extraPlugins: 'mathjax'});
                        });
                    } else {
                        setTimeout(function(){
                            CKEDITOR.inline(optionName, {extraPlugins: 'mathjax'});
                        });
                    }
                }
                
            });
        });
        setTimeout(function(){
            if( $scope.questionJSON.questions[index].questype === 'input' ){
                $('#questionId'+index).find('.visibility-editor').find('.cke_textarea_inline').css('display', 'none');
            } else {
                $('#questionId'+index).find('.visibility-editor').find('.cke_textarea_inline').css('display', 'block');
            }
            $('#questionId'+index).css('visibility', 'visible');
        },100);

    };
   
    $scope.removeCorrectmark = function (parentIndex, index) {
        $scope.questionJSON.questions[parentIndex].correctmark.splice(index, 1);
    };
    
    $scope.addCorrectmark = function (parentIndex) {
        if (($scope.questionJSON.questions[parentIndex].option.length - 1) * ($scope.questionJSON.questions[parentIndex].option[0].length - 1) > $scope.questionJSON.questions[parentIndex].correctmark.length) {
            var tempVar = $scope.questionJSON.questions[parentIndex].correctmark;
            var valueInput = tempVar[tempVar.length - 1].val ? tempVar[tempVar.length - 1].val - 1 : (($scope.questionJSON.questions[parentIndex].option.length - 1) * ($scope.questionJSON.questions[parentIndex].option[0].length - 1));
            var marksInput = tempVar[tempVar.length - 1].marks ? tempVar[(tempVar.length) - 1].marks - 1 : (parseInt($('#mark' + parentIndex).val(), 10) - 1);
            if ( valueInput === 0 ) {
                valueInput = ($scope.questionJSON.questions[parentIndex].option.length - 1) * ($scope.questionJSON.questions[parentIndex].option[0].length - 1);
            }
            if ( marksInput === 0 ) {
                marksInput = parseInt($('#mark' + parentIndex).val(), 10);
            }
            $scope.questionJSON.questions[parentIndex].correctmark.push({val: valueInput, marks: marksInput});
        } else {
            var message = '<strong>'+$rootScope.MASTERS.keywords['typeerror']+'</strong> Max ' + ($scope.questionJSON.questions[parentIndex].option.length - 1) * ($scope.questionJSON.questions[parentIndex].option[0].length - 1) + ' option allowed.';
            Flash.create('danger', message);
        }

    };

    setTimeout(function () {
        CKEDITOR.config.toolbar = $rootScope.MASTERS.editorConfig;
        CKEDITOR.inline('descriptionText', {extraPlugins: 'mathjax'});
        $scope.populateckeditor();

    }, 10);

});

app.controller('tableinputentryPreCtrl', function ( $scope, $modalInstance, questionPre, $http, $modal ) {

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
