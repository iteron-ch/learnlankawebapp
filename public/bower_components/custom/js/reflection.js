/**
 * Created by sunny.rana on 30-11-2015.
 */
'use strict';

/**
 * @ngdoc function
 * @name questionBuilder.controller:reflectionrightleftCtrl
 * @description
 * # reflectionrightleftCtrl
 * Controller of the questionBuilder
 */
var app = angular.module('questionBuilder');
app.controller( 'reflectionCtrl' , function ( $scope, $window, $sce, $rootScope, $routeParams, $modal, Flash, $http, $timeout ) {

    //Accordion scope.
    $scope.oneAtATime = true;
    $scope.isFirstOpen = true;

    $scope.submittedForm = false;
    $scope.showAnswerEle = '';
    $scope.questionCode = $scope.selected.questiontype;
    $scope.correctOption = '';
    $scope.questionType = $rootScope.MASTERS.questiontype[$scope.questionCode];
    $scope.addQuesBtnState = false;
    $scope.matrics = {
        'column' : '',
        'row' : ''
    };
    
    if (parseInt( $scope.selected.questiontype, 10) === 25 || parseInt( $scope.selected.questiontype, 10) === 24){
        $scope.matrics.column = 10;
        $scope.matrics.row = 15;
    } else if ( parseInt( $scope.selected.questiontype, 10) === 17 || parseInt( $scope.selected.questiontype, 10) === 23  || parseInt( $scope.selected.questiontype, 10) === 26 || parseInt( $scope.selected.questiontype, 10) === 27) {
        $scope.matrics.column = 15;
        $scope.matrics.row = 10;
    }
    
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
                    option: {column:$scope.matrics.column, row:$scope.matrics.row, radius: 6, margin:5, pattern:[]},
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
            option: {column:$scope.matrics.column, row:$scope.matrics.row, radius: 6, margin:5, pattern:[]},
            correctAns: [],
            correctmark: [{val: '', marks: ''}],
            mark: 1,
            questiontype: $scope.questionCode
        });

        var questionIndex = $scope.questionJSON.questions.length;
        var questionName = 'questionText' + (questionIndex);

        $timeout(function () {
            CKEDITOR.inline(questionName, {extraPlugins: 'mathjax'});
            var forDotsjoin = {
                'ques': $scope.questionJSON.questions[questionIndex-1],
                'action': 'ques',
                'addevent' : true
            };
            var lock = new Reflection("#patternContainer_ques_"+(questionIndex-1), forDotsjoin );
            forDotsjoin.action = '';
            forDotsjoin.addevent = '';
            var lock = new Reflection("#patternAnswer_ans_"+(questionIndex-1), forDotsjoin );
            
            $( window ).on( "watch", function( event, obj ) {
                if(obj.holder[0].id.split('_')[1] === 'ques'){
                    $scope.questionJSON.questions[obj.holder[0].id.split('_')[2]].option.pattern.push(obj.pattern);
                } else {
                    $scope.questionJSON.questions[obj.holder[0].id.split('_')[2]].correctAns.push(obj.pattern);
                }
            });
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
    
    $scope.resetDrawing = function( questionIndex, mode ){
        if( mode === 'ques' ){
            $scope.questionJSON.questions[questionIndex].option.pattern = [];
            $scope.questionJSON.questions[questionIndex].correctAns = [];
            var forDotsjoin = {
                'ques': $scope.questionJSON.questions[questionIndex],
                'action': 'ques',
                'addevent' : true
            };
            var lock = new Reflection("#patternContainer_ques_"+questionIndex, forDotsjoin );
            forDotsjoin.action = '';
            forDotsjoin.addevent = '';
            var lock = new Reflection("#patternAnswer_ans_"+questionIndex, forDotsjoin );

            $( window ).on( "watch", function( event, obj ) {
                if(obj.holder[0].id.split('_')[1] === 'ques'){
                    $scope.questionJSON.questions[obj.holder[0].id.split('_')[2]].option.pattern.push( obj.pattern );
                } else {
                    $scope.questionJSON.questions[obj.holder[0].id.split('_')[2]].correctAns.push( obj.pattern );
                }
            });
        } else {
            $scope.questionJSON.questions[questionIndex].correctAns = [];            
            var forDotsjoin = {
                'ques': $scope.questionJSON.questions[questionIndex],
                'action': 'ques',
                'addevent' : false
            };
            var lock = new Reflection("#setpatternContainer_ques_"+questionIndex, forDotsjoin );
            forDotsjoin.action = 'ans';
            forDotsjoin.addevent = true;
            var lock = new Reflection("#setpatternAnswer_ans_"+questionIndex, forDotsjoin );
            
            $( window ).on( "watch", function( event, obj ) {
                if(obj.holder[0].id.split('_')[1] === 'ques'){
                    $scope.questionJSON.questions[obj.holder[0].id.split('_')[2]].option.pattern.push(obj.pattern);
                } else {
                    $scope.questionJSON.questions[obj.holder[0].id.split('_')[2]].correctAns.push(obj.pattern);
                }
            });
            
        }        
        
    };
    
    /*Save & Next trigger here*/
    $scope.submitreflectionrightleftCQ = function ( navigateTo, actionMode ) {
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
            $('#mark' + questionIndex).removeClass('cke_textarea_inline_error');
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
        if($scope.questionJSON.questions[questionIndex].option.pattern.length === 0){
            $scope.validate = false;
        }
        
        if($('#mark' + questionIndex).val()==='' || parseInt($('#mark' + questionIndex).val())<=0) {
            $('#mark' + questionIndex).addClass('cke_textarea_inline_error');
            $scope.validate = false;
        }          
        return $scope.validate;
    };
        
    /*Set Answer trigger here*/
    $scope.setAnswer = function (index) {
        var questionIndex = index;
        $scope.questionJSON.questions[index].mark = parseInt($('#mark' + index).val(), 10);
        $scope.validatedVal = $scope.validateQuestion(index);
        if ($scope.validatedVal) {
            $scope.showAnswerEle = index + 1;
            $scope.addQuesBtnState = true;
            if ($scope.questionJSON.questions[index].correctmark.length === 1 && $scope.questionJSON.questions[index].correctmark[0].val === '') {
                $scope.questionJSON.questions[index].correctmark[0].val = 1;
                $scope.questionJSON.questions[index].correctmark[0].marks = parseInt($('#mark' + index).val(), 10);
            }
            
            angular.forEach($scope.questionJSON.questions, function (ele, index) {
                var questionName = 'questionText' + (index + 1);
                $scope.questionJSON.questions[index].ques = CKEDITOR.instances[questionName].getData();
                CKEDITOR.instances[questionName].setReadOnly(true);
            });
            var forDotsjoin = {
                'ques': $scope.questionJSON.questions[questionIndex],
                'action': 'ques',
                'addevent' : false
            }
            var lock = new Reflection("#setpatternContainer_ques_"+questionIndex, forDotsjoin );
            forDotsjoin.action = 'ans';
            forDotsjoin.addevent = true;
            var lock = new Reflection("#setpatternAnswer_ans_"+questionIndex, forDotsjoin );
            
            $( window ).on( "watch", function( event, obj ) {
                if(obj.holder[0].id.split('_')[1] === 'ques'){
                    $scope.questionJSON.questions[obj.holder[0].id.split('_')[2]].option.pattern.push(obj.pattern);
                } else {
                    $scope.questionJSON.questions[obj.holder[0].id.split('_')[2]].correctAns.push(obj.pattern);
                }
            });

            
        } else {
            var message = '<strong>'+$rootScope.MASTERS.keywords['typeerror']+'</strong> '+ $rootScope.MASTERS.keywords['alertsetanswer'];
            Flash.create('danger', message);
        }
    };
    
    /*Edit Question trigger here*/
    $scope.editQuestion = function ( parentIndex ) {
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
                templateUrl: 'reflectionrightleftPre',
                controller: 'reflectionrightleftPreCtrl',
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
                $timeout(function () {
                    CKEDITOR.inline(questionName, {extraPlugins: 'mathjax'});
                });

            } else {
                $timeout(function () {
                    CKEDITOR.inline(questionName, {extraPlugins: 'mathjax'});
                }, 10);

            }
            var forDotsjoin = {
                'ques': $scope.questionJSON.questions[index],
                'action': 'ques',
                'addevent' : true
            }
            var lock = new Reflection("#patternContainer_ques_"+index, forDotsjoin );
            forDotsjoin.action = '';
            forDotsjoin.addevent = '';
            var lock = new Reflection("#patternAnswer_ans_"+index, forDotsjoin );
            
            $( window ).on( "watch", function( event, obj ) {
                if(obj.holder[0].id.split('_')[1] === 'ques'){
                    $scope.questionJSON.questions[obj.holder[0].id.split('_')[2]].option.pattern.push( obj.pattern );
                } else {
                    $scope.questionJSON.questions[obj.holder[0].id.split('_')[2]].correctAns.push( obj.pattern );
                }
            });

        });

    };
    
    $timeout(function () {
        CKEDITOR.config.toolbar = $rootScope.MASTERS.editorConfig;
        CKEDITOR.inline('descriptionText', {extraPlugins: 'mathjax'});
        $scope.populateckeditor();
    }, 10);   

});

app.controller('reflectionrightleftPreCtrl', function ( $scope, $modalInstance, questionPre, $http, $timeout, $modal ) {

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
    
    $timeout(function(){
        var forDotsjoin = {
            'ques': $scope.questionJSON.questions[$scope.quesIndex],
            'action': 'ques',
            'addevent' : false
        }
        var lock = new Reflection("#prepatternContainer_ques_"+$scope.quesIndex, forDotsjoin );
        forDotsjoin.action = '';
        forDotsjoin.addevent = true;
        var lock = new Reflection("#prepatternAnswer_ans_"+$scope.quesIndex, forDotsjoin );
    },100);    
});