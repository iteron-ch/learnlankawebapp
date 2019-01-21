/**
 * Created by sunny.rana on 31-08-2015.
 */
'use strict';

/**
 * @ngdoc function
 * @name questionBuilder.controller:wordonimageCtrl
 * @description
 * # wordonimageCtrl
 * Controller of the questionBuilder
 */
var app = angular.module('questionBuilder');
app.controller( 'wordonimageCtrl' , function ( $scope, $window, $sce, $rootScope, $routeParams, $modal, Flash, $http, $timeout ) {

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
            questiontype: $scope.questionCode,
            description: '',
            descvisible: true,
            subjecttype: $routeParams.subject,
            questions: [
                {
                    ques: '',
                    option: {value: '', noofoption: 2, optiontype: 'aplhanumeric', notofchar: 5},
                    correctAns: [{label: '', value: ''}, {label: '', value: ''}],
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
            option: {value: '', noofoption: 2, optiontype: 'aplhanumeric', notofchar: 5},
            correctAns: [{label: '', value: ''}, {label: '', value: ''}],
            correctmark: [{val: '', marks: ''}],
            mark: 1,
            questiontype: $scope.questionCode
        });

        var questionIndex = $scope.questionJSON.questions.length;
        var questionName = 'questionText' + (questionIndex);

        $timeout(function () {
            CKEDITOR.inline(questionName, {extraPlugins: 'mathjax'});
        }, 100);

    };
    
    $scope.imageBind = function( questionIndex ){
        if( $scope.questionJSON.questions[questionIndex].option.value ){
            return $sce.trustAsHtml('<img width="100%" height="100%" src="/questionimg/'+$scope.questionJSON.questions[questionIndex].option.value+'"/>');
        } else {
            return '';
        }
        
    };

    $scope.updateLabelObj = function (questionIndex) {
        $scope.questionJSON.questions[questionIndex].correctAns = [];
        for (var i = 1; i <= $scope.questionJSON.questions[questionIndex].option.noofoption; i++) {
            $scope.questionJSON.questions[questionIndex].correctAns.push({label: '', value: ''});
        }
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
        $scope.incompleteArray = '';
        $scope.questionlebelValidation = true;
        
        angular.forEach($scope.questionJSON.questions, function (ele, questionIndex) {
            $scope.questionlebelValidation = true;
            if ( $scope.questionJSON.questions[questionIndex].correctAns.length === 0 ) {
                $scope.incompleteArray = $scope.incompleteArray !== '' ? $scope.incompleteArray+', '+(questionIndex+1) : questionIndex+1;
                $scope.quesvalidate = false;
            } else {
                angular.forEach($scope.questionJSON.questions[questionIndex].correctAns, function (ele, index) {
                    if(ele.value === '' && $scope.questionlebelValidation){
                        $scope.incompleteArray = $scope.incompleteArray !== '' ? $scope.incompleteArray+', '+(questionIndex+1) : questionIndex+1;
                        $scope.quesvalidate = false;
                        $scope.questionlebelValidation = false;
                    }
                })
            }
            
            var questionName = 'questionText' + (questionIndex + 1);
            $scope.questionJSON.questions[questionIndex].ques = CKEDITOR.instances[questionName].getData();
            $scope.questionJSON.questions[questionIndex].mark = parseInt($('#mark' + questionIndex).val(), 10);
            
            if (CKEDITOR.instances[questionName].getData()) {} else {
                $scope.quesvalidate = false;
            }
            //Check image uploaded or not.
            if( $scope.questionJSON.questions[questionIndex].option.value === ''){
                $scope.quesvalidate = false;
            }
        });        
        return $scope.quesvalidate;   
    };    
    
    /*Save & Next trigger here*/
    $scope.submitwordonimageCQ = function ( navigateTo, actionMode ) {
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
                $http.post($rootScope.MASTERS.api['questionbuilder'], {data: angular.copy($scope.questionJSON)}).
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
        $timeout(function () {
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
        
        //Check image uploaded or not.
        if( $scope.questionJSON.questions[questionIndex].option.value === ''){
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
            if ($scope.questionJSON.questions[index].correctmark.length === 1 && $scope.questionJSON.questions[index].correctmark[0].val === '') {
                angular.forEach($scope.questionJSON.questions[index].correctmark, function (childEle, childindex) {
                    $scope.questionJSON.questions[index].correctmark[childindex].val = $scope.questionJSON.questions[index].correctAns.length;
                    $scope.questionJSON.questions[index].correctmark[childindex].marks = parseInt($('#mark' + index).val(), 10);
                });
            }
            angular.forEach($scope.questionJSON.questions, function (ele, index) {
                var questionName = 'questionText' + (index + 1);
                $scope.questionJSON.questions[index].ques = CKEDITOR.instances[questionName].getData();
                CKEDITOR.instances[questionName].setReadOnly(true);
            });
        } else {
            var message = '<strong>'+$rootScope.MASTERS.keywords['typeerror']+'</strong> '+ $rootScope.MASTERS.keywords['alertsetanswer'];
            Flash.create('danger', message);
        }


    };
    
     $scope.removeCorrectmark = function (parentIndex, index) {
        $scope.questionJSON.questions[parentIndex].correctmark.splice(index, 1);
    };
    
    $scope.addCorrectmark = function (parentIndex) {
        if ($scope.questionJSON.questions[parentIndex].correctAns.length > $scope.questionJSON.questions[parentIndex].correctmark.length) {
            var tempVar = $scope.questionJSON.questions[parentIndex].correctmark;
            var valueInput = tempVar[tempVar.length - 1].val ? tempVar[tempVar.length - 1].val - 1 : ($scope.questionJSON.questions[parentIndex].correctAns.length - 1);
            var marksInput = tempVar[tempVar.length - 1].marks ? tempVar[(tempVar.length) - 1].marks - 1 : (parseInt($('#mark' + parentIndex).val(), 10) - 1);
            if ( valueInput == 0 ) {
                valueInput = $scope.questionJSON.questions[parentIndex].correctAns.length;
            }
            if ( marksInput == 0 ) {
                marksInput = parseInt($('#mark' + parentIndex).val(), 10);
            }
            $scope.questionJSON.questions[parentIndex].correctmark.push({val: valueInput, marks: marksInput});
        } else {
            var message = '<strong>'+$rootScope.MASTERS.keywords['typeerror']+'</strong> Max ' + $scope.questionJSON.questions[parentIndex].correctAns.length + ' option allowed.';
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
//                $scope.questionJSON.questions[index].option.value = $('#imageID' + index).attr('ng-src');

            });

            var modalInstance = $modal.open({
                templateUrl: 'wordonimagePre',
                controller: 'wordonimagePreCtrl',
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

            if (CKEDITOR.instances[questionName]) {
                CKEDITOR.instances[questionName].destroy();
                setTimeout(function(){
                    CKEDITOR.inline(questionName, {extraPlugins: 'mathjax'});
                });

            } else {
                $timeout(function () {
                    CKEDITOR.inline(questionName, {extraPlugins: 'mathjax'});
                }, 10);

            }

        });

    };
    
    $scope.bindClick = function(e){
        var quesId = $(e).attr('id');
        var data = new FormData();
        var file = typeof $(e)[0].files[0] === 'undefined' ? '' : $(e)[0].files[0];
        data.append('file', file);
        data.append('dimension', [600,400]);
        data.append('selectedImg', $scope.questionJSON.questions[quesId].option.value);
        $.ajax({
            url: $rootScope.MASTERS.api['uploadimage'],
            type: 'POST',
            method : 'POST',
            data: data,
            cache: false,
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            success: function( data, textStatus, jqXHR ){
                $scope.questionJSON.questions[quesId].option.value = data.filename;
            }, error: function( jqXHR, textStatus, errorThrown ){
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
                // STOP LOADING SPINNER
            }
        });
    };
    
    $timeout(function () {
        CKEDITOR.config.toolbar = $rootScope.MASTERS.editorConfig;
        CKEDITOR.inline('descriptionText', {extraPlugins: 'mathjax'});
        $scope.populateckeditor();
        
    }, 10);   

});

app.controller('wordonimagePreCtrl', function ( $scope, $modalInstance, questionPre, $http, $modal ) {

    $scope.quesIndex = questionPre.questionindex;
    $scope.quesArray = questionPre.assignment;

    console.log('$modalInstance', $modalInstance);
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
