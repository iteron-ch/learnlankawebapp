/**
 * Created by sunny.rana on 27-10-2015.
 */
'use strict';

/**
 * @ngdoc function
 * @name questionBuilder.controller:drawlineonimageCtrl
 * @description
 * # drawlineonimageCtrl
 * Controller of the questionBuilder
 */
var app = angular.module('questionBuilder');
app.controller( 'drawlineonimageCtrl' , function ( $scope, $window, $sce, $rootScope, $routeParams, $modal, Flash, $http, $timeout ) {

    //Accordion scope.
    $scope.oneAtATime = true;
    $scope.isFirstOpen = true;

    $scope.submittedForm = false;
    $scope.showAnswerEle = '';
    $scope.questionCode = $scope.selected.questiontype;
    $scope.correctOption = '';
    $scope.questionType = $rootScope.MASTERS.questiontype[$scope.questionCode];
    $scope.addQuesBtnState = false;
    $scope.clickResetDrawing = false;
    $scope.setclickResetDrawing = false;
    
    $scope.resetDrawing = function( parentIndex, mode ){
        if( mode === 'ques' ){
            $scope.questionJSON.questions[parentIndex].option = [];
            $scope.questionJSON.questions[parentIndex].correctAns = [];            
            var canvas = new fabric.Canvas('quesCanvas'+parentIndex, { selection: false });
            canvas.clear();
            $scope.clickResetDrawing = true;            
            $scope.setclickResetDrawing = true;
        } else {
            $scope.questionJSON.questions[parentIndex].correctAns = [];        
            var canvas = new fabric.Canvas('setansCanvas'+parentIndex, { selection: false });
            canvas.clear();
            $scope.setclickResetDrawing = true;
            $scope.setanswerCanvasRender( parentIndex );
        }
    };
    
    $scope.questionCanvasRender = function( quesId ){
        var canvas = new fabric.Canvas('quesCanvas'+quesId, { selection: false });
	var canvas1 = new fabric.Canvas('quesCanvasImg'+quesId);
        //canvas.item(0).selectable = false;
	var line, isDown, imageUrl;
        imageUrl = "/questionimg/"+$scope.questionJSON.questions[quesId].imgPath;
	fabric.Image.fromURL(imageUrl, function(oImg) {
	  canvas1.add(oImg);
	});
        if( $scope.questionJSON.questions[quesId].option.length ){
             angular.forEach($scope.questionJSON.questions[quesId].option, function (eoption, ioption) {
               canvas.add(new fabric.Circle({ radius: 5, fill: 'red', top: eoption.top, left: eoption.left }));                
               canvas.item(ioption).selectable = false;
            });            
        }
	canvas.on('mouse:down', function(o){
            isDown = true;      
            if( $scope.clickResetDrawing ){
                canvas.clear();
                $scope.clickResetDrawing = false;
            }
            canvas.add(new fabric.Circle({ radius: 5, fill: 'red', top: o.e.offsetY - 5, left: o.e.offsetX - 5 }));
            $scope.questionJSON.questions[quesId].option.push({top:o.e.offsetY - 5, left:o.e.offsetX - 5});	  
	});	
	canvas.on('mouse:move', function(o){});	
	canvas.on('mouse:up', function(o){
	    isDown = false;
	});
    };    
    
    if( $scope.questionData ) {
        $scope.questionJSON = angular.copy( $scope.questionData );     
        $timeout(function(){
            angular.forEach($scope.questionJSON.questions, function (ele, index) {
                $scope.questionCanvasRender(index);
            });    
        },100);
        
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
                    imgPath:'',
                    option: [],
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
            imgPath:'',
            option: [],
            correctAns: [],
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
        if( $scope.questionJSON.id && $scope.questionJSON.questions[questionIndex].imgPath ){
            return $sce.trustAsHtml('<img width="100%" height="100%" src="/questionimg/'+$scope.questionJSON.questions[questionIndex].imgPath+'"/>');
        } else {
            return '';
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
            if ( $scope.questionJSON.questions[questionIndex].correctAns.length === 0 && !$scope.questionJSON.questions[questionIndex].imgPath) {
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
            
            if(!$scope.questionJSON.questions[questionIndex].imgPath){
                $scope.quesvalidate = false;
            }
        });
        return $scope.quesvalidate;   
    };    
    
    /*Save & Next trigger here*/
    $scope.submitdrawlineonimageCQ = function ( navigateTo, actionMode ) {
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
        
        if(!$scope.questionJSON.questions[questionIndex].imgPath){
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
                templateUrl: 'drawlineonimagePre',
                controller: 'drawlineonimagePreCtrl',
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
    
    $scope.setanswerCanvasRender = function( quesId ){
        var canvas = new fabric.Canvas('setansCanvas'+quesId, { selection: false });
	var canvas1 = new fabric.Canvas('setansCanvasImg'+quesId);
	var line, isDown, imageUrl, isMove;
        var correctpoints, correctset;
        imageUrl = "/questionimg/"+$scope.questionJSON.questions[quesId].imgPath;
	fabric.Image.fromURL(imageUrl, function(oImg) {
	  canvas1.add(oImg);
	});	
        
        if( $scope.questionJSON.questions[quesId].option.length ){
             angular.forEach($scope.questionJSON.questions[quesId].option, function (eoption, ioption) {
               canvas.add(new fabric.Circle({ radius: 5, fill: 'red', top: eoption.top, left: eoption.left }));                
               canvas.item(ioption).selectable = false;
            });            
        }
        
        if( $scope.questionJSON.questions[quesId].correctAns.length ){
             angular.forEach($scope.questionJSON.questions[quesId].correctAns, function (elecorrecAns, correcAnsIndex) {
                line = new fabric.Line(elecorrecAns.points, {
                    strokeWidth: 5,
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
            
            if( $scope.setclickResetDrawing ){
                canvas.clear();                
                $scope.setclickResetDrawing = false;
                if( $scope.questionJSON.questions[quesId].option.length ){
                    angular.forEach($scope.questionJSON.questions[quesId].option, function (eoption, ioption) {
                      canvas.add(new fabric.Circle({ radius: 5, fill: 'red', top: eoption.top, left: eoption.left }));                
                      canvas.item(ioption).selectable = false;
                   });            
               }
            }
            
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
                $scope.questionJSON.questions[quesId].correctAns.push({points: correctpoints, set: correctset});
            }
	    isDown = false;
	});
    };
    
    $scope.bindClick = function(e){
        var quesId = $(e).attr('id');
        var data = new FormData();
        var file = typeof $(e)[0].files[0] === 'undefined' ? '' : $(e)[0].files[0];
        data.append('file', file);
        data.append('dimension', [600,500]);
        data.append('selectedImg', $scope.questionJSON.questions[quesId].imgPath);
        $.ajax({
            url: $rootScope.MASTERS.api['uploadimage'],
            type: 'POST',
            method : 'POST',
            data: data,
            cache: false,
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            success: function( data, textStatus, jqXHR ){
                $scope.questionJSON.questions[quesId].imgPath = data.filename;
                $scope.questionCanvasRender( quesId );
            }, error: function( jqXHR, textStatus, errorThrown ){
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
                // STOP LOADING SPINNER
            }
        });
    };
    
    $timeout(function () {        
        CKEDITOR.config.toolbar = $rootScope.MASTERS.editorConfig;
        CKEDITOR.inline('descriptionText', {
            extraPlugins: 'mathjax'
        });
        $scope.populateckeditor();
    }, 10);   

});

app.controller('drawlineonimagePreCtrl', function ( $scope, $modalInstance, questionPre, $timeout, $http, $modal ) {

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
    
    $scope.preCanvasRender = function( quesId ){
        var canvas = new fabric.Canvas('preCanvas'+quesId, { selection: false });
	var canvas1 = new fabric.Canvas('preCanvasImg'+quesId);
	var line, isDown, imageUrl;
        imageUrl = "/questionimg/"+$scope.quesArray.questions[quesId].imgPath;
        
	fabric.Image.fromURL(imageUrl, function(oImg) {
            canvas1.add(oImg);
	});	
        
        
        if( $scope.quesArray.questions[quesId].option.length ){
             angular.forEach($scope.quesArray.questions[quesId].option, function (eoption, ioption) {
               canvas.add(new fabric.Circle({ radius: 5, fill: 'red', top: eoption.top, left: eoption.left }));                
               canvas.item(ioption).selectable = false;
            });            
        }
        
	canvas.on('mouse:down', function(o){   
            isDown = true;
            var pointer = canvas.getPointer(o.e);
            var points = [ pointer.x, pointer.y, pointer.x, pointer.y ];
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
            var pointer = canvas.getPointer(o.e);
            line.set({ x2: pointer.x, y2: pointer.y });
            canvas.renderAll();
        });	
	canvas.on('mouse:up', function(o){
	    isDown = false;
	});
    };
    
    $timeout(function(){
        $scope.preCanvasRender( $scope.quesIndex );
    },100);
    
});
