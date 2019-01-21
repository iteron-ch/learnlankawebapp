/**
 * Created by sunny.rana on 24-11-2015.
 */
'use strict';

/**
 * @ngdoc function
 * @name questionBuilder.controller:reflectionCtrl
 * @description
 * # reflectionCtrl
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
                    option: {'mirrorline':'right','optiontext':[]},
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
            option: {'mirrorline':'right','optiontext':[]},
            correctAns: [],
            correctmark: [{val: '', marks: ''}],
            mark: 1,
            questiontype: $scope.questionCode
        });

        var questionIndex = $scope.questionJSON.questions.length;
        var questionName = 'questionText' + (questionIndex);
        $timeout(function () {
            CKEDITOR.inline(questionName, {extraPlugins: 'mathjax'});
            $scope.quesCanvasRender(questionIndex-1, 'quesCanvasleft'+(questionIndex-1), 'ques');
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
                    angular.forEach($scope.questionJSON.questions, function (ele, index) {
                        var canvasName = ele.option.mirrorline === 'right' ? 'left' : ele.option.mirrorline === 'left' ? 'right' : ele.option.mirrorline === 'top' ? 'bottom' : ele.option.mirrorline === 'bottom' ? 'top' : '';
                        $scope.quesCanvasRender(index, 'quesCanvas'+canvasName+index, 'ques');
                    }); 
                }
            });

        }
    };
    
    /*Validate question before sumbit*/
    $scope.submitValidateQues = function(){
        $scope.quesvalidate = true
        $scope.incompleteArray = '';
        $scope.questionlebelValidation = true;
        
        angular.forEach($scope.questionJSON.questions, function (ele, questionIndex) {
            $scope.questionlebelValidation = true;
            if ( $scope.questionJSON.questions[questionIndex].correctAns.length === 0) {
                $scope.incompleteArray = $scope.incompleteArray !== '' ? $scope.incompleteArray+', '+(questionIndex+1) : questionIndex+1;
                $scope.quesvalidate = false;
            } else {
                angular.forEach($scope.questionJSON.questions[questionIndex].correctAns, function (ele, index) {
                    if(ele.value === '' && $scope.questionlebelValidation){
                        $scope.incompleteArray = $scope.incompleteArray !== '' ? $scope.incompleteArray+', '+(questionIndex+1) : questionIndex+1;
                        $scope.quesvalidate = false;
                        $scope.questionlebelValidation = false;
                    }
                });
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
    $scope.submitreflectionCQ = function ( navigateTo, actionMode ) {
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
        
        return $scope.validate;
    };
    
    /*Set Answer trigger here*/
    $scope.setAnswer = function (index) {
        $scope.questionJSON.questions[index].mark = parseInt($('#mark' + index).val(), 10);
        $scope.validatedVal = $scope.validateQuestion(index);
        if ($scope.validatedVal) {
            $scope.showAnswerEle = index + 1;
            $scope.addQuesBtnState = true;
            if ($scope.questionJSON.questions[index].correctmark.length) {
                $scope.questionJSON.questions[index].correctmark[0].val = 1;
                $scope.questionJSON.questions[index].correctmark[0].marks = parseInt($('#mark' + index).val(), 10);
            }
            
            angular.forEach($scope.questionJSON.questions, function (ele, index) {
                var questionName = 'questionText' + (index + 1);
                $scope.questionJSON.questions[index].ques = CKEDITOR.instances[questionName].getData();
                CKEDITOR.instances[questionName].setReadOnly(true);
            });
            $scope.setanswerCanvasRender($scope.showAnswerEle - 1);
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

    $scope.updateCanvas = function(parentIndex){
        //console.log( $('#quesCanvas'+parentIndex) );
        var tempEle = $scope.questionJSON.questions[parentIndex];
        $scope.questionJSON.questions[parentIndex].correctAns = [];
        $scope.questionJSON.questions[parentIndex].option.optiontext = [];
        
        var canvasName = tempEle.option.mirrorline === 'right' ? 'left' : tempEle.option.mirrorline === 'left' ? 'right' : tempEle.option.mirrorline === 'top' ? 'bottom' : tempEle.option.mirrorline === 'bottom' ? 'top' : '';
        $timeout(function(){
            $scope.quesCanvasRender(parentIndex, 'quesCanvas'+canvasName+parentIndex, 'ques');
        })
        
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
                templateUrl: 'reflectionPre',
                controller: 'reflectionPreCtrl',
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
        }
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
    
    $scope.quesCanvasRender = function( quesId, canvasName, type ){
        var canvas = new fabric.Canvas(canvasName, { selection: false });
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
        
        
        if( $scope.questionJSON.questions[quesId].option.optiontext.length ){
             angular.forEach($scope.questionJSON.questions[quesId].option.optiontext, function (elecorrecAns, correcAnsIndex) {
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
        if( type === 'ques') {
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
                $scope.questionJSON.questions[$(this.lowerCanvasEl).attr('id').substr($(this.lowerCanvasEl).attr('id').length - 1)].option.optiontext.push({points: correctpoints, set: correctset});
                isDown = false;
            });
        }
	
    };
    
    $scope.setanswerCanvasRender = function( quesId ){
        var canvasType = $scope.questionJSON.questions[quesId].option.mirrorline;
        var canvas = new fabric.Canvas('ansCanvas'+canvasType+quesId, { selection: false });
	
	var line, isDown;
        var correctpoints, correctset;
        
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
        $scope.quesCanvasRender(quesId, 'ansCanvas'+canvasName+quesId, 'ans');
        
        if( $scope.questionJSON.questions[quesId].correctAns.length ){
             angular.forEach($scope.questionJSON.questions[quesId].correctAns, function (elecorrecAns, correcAnsIndex) {
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
            $scope.questionJSON.questions[quesId].correctAns.push({points: correctpoints, set: correctset});
	    isDown = false;
	});
    };
    
    $timeout(function () {        
        CKEDITOR.config.toolbar = $rootScope.MASTERS.editorConfig;
        CKEDITOR.inline('descriptionText', {
            extraPlugins: 'mathjax'
        });
        $scope.populateckeditor();
        angular.forEach($scope.questionJSON.questions, function (ele, index) {
            var canvasName = ele.option.mirrorline === 'right' ? 'left' : ele.option.mirrorline === 'left' ? 'right' : ele.option.mirrorline === 'top' ? 'bottom' : ele.option.mirrorline === 'bottom' ? 'top' : '';
            $scope.quesCanvasRender(index, 'quesCanvas'+canvasName+index, 'ques');
        }); 
    }, 100);   

});

app.controller('reflectionPreCtrl', function ($scope, $modalInstance, questionPre, $timeout) {

    $scope.quesIndex = questionPre.questionindex;
    $scope.quesArray = questionPre.assignment;

    $scope.ok = function () {
        $modalInstance.dismiss('cancel');
    };

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };
    
    $scope.preCanvasRender = function( quesId ){
        var canvasType = $scope.quesArray.questions[quesId].option.mirrorline;
        var canvas = new fabric.Canvas('preCanvas'+canvasType+quesId, { selection: false });
	
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
        $scope.preQuesCanvasRender(quesId, 'preCanvas'+canvasName+quesId);
        
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
	    isDown = false;
	});
    };
    
    $scope.preQuesCanvasRender = function( quesId, canvasName ){
        var canvasques = new fabric.Canvas(canvasName, { selection: false });
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
                canvasques.add(dot);
                itemCount = itemCount + 1;
                canvasques.item(itemCount - 1).selectable = false;
            }
        }      
        
        if( $scope.questionJSON.questions[quesId].option.optiontext.length ){
             angular.forEach($scope.questionJSON.questions[quesId].option.optiontext, function (elecorrecAns, correcAnsIndex) {
                line = new fabric.Line(elecorrecAns.points, {
                    strokeWidth: 3,
                    fill: 'red',
                    stroke: 'red',
                    originX: 'center',
                    originY: 'center'
                });
                canvasques.add(line);
                
                line.set(elecorrecAns.set);
                canvasques.renderAll();
                
            });            
        }
	
    };
    
    $timeout(function(){
        $scope.preCanvasRender( $scope.quesIndex );
    });
    
});
