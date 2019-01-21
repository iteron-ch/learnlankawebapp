/**
 * Created by sunny.rana on 24-08-2015.
 */
'use strict';

/**
 * @ngdoc function
 * @name questionBuilder.controller:measurementCtrl
 * @description
 * # measurementCtrl
 * Controller of the questionBuilder
 */
var app = angular.module('questionBuilder');

app.controller( 'measurementCtrl' , function ( $scope, $window, $sce, $rootScope, $routeParams, $modal, Flash, $http ) {

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
    $scope.canvasWidth = '400';
    $scope.canvasHeigth = '300';
    $scope.stopdragging = false;
    
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
                    imgPath:'',
                    correctAns: '',
                    correctmark: [{val: '', marks: ''}],
                    mark: 1,
                    questiontype: $scope.questionCode,
                    view:'horizontal'
                }
            ]
        };
    }
    /*Add question here*/
    $scope.questionAdd = function () {
        $scope.questionJSON.questions.push({
            ques: '',
            imgPath:'',
            correctAns: '',
            correctmark: [{val: '', marks: ''}],
            mark: 1,
            questiontype: $scope.questionCode,
            view:'horizontal'
        });
        var questionIndex = $scope.questionJSON.questions.length;
        var questionName = 'questionText' + (questionIndex);
        setTimeout(function () {
            CKEDITOR.inline(questionName, {extraPlugins: 'mathjax'});
        });
    };
    
    $scope.imageBind = function( questionIndex ){
        if( $scope.questionJSON.id && $scope.questionJSON.questions[questionIndex].imgPath ){
            return $sce.trustAsHtml('<img width="[%canvasWidth%]" height="[%canvasHeight%]" src="/questionimg/'+$scope.questionJSON.questions[questionIndex].imgPath+'"/>');
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
                if ($scope.questionJSON.questions.length == (index + 1)) {
                    $scope.questionJSON.questions.splice(parentIndex, 1);
                    $scope.populateckeditor({action: 'remove', index: parentIndex});
                }
            });

        }
    };
    
    $scope.bindClick = function(e){
        var quesId = $(e).attr('id');
        var data = new FormData();
        var file = typeof $(e)[0].files[0] === 'undefined' ? '' : $(e)[0].files[0];
        data.append('file', file);
        data.append('dimension', [400,300]);
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
            }, error: function( jqXHR, textStatus, errorThrown ){
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
                // STOP LOADING SPINNER
            }
        });
    };
    
    /*Validate question before sumbit*/
    $scope.submitValidateQues = function(){
        $scope.quesvalidate = true
        $scope.incompleteArray = '';
        
        angular.forEach($scope.questionJSON.questions, function (ele, questionIndex) {
            
            if ( $scope.questionJSON.questions[questionIndex].correctAns === '' ) {
                $scope.incompleteArray = $scope.incompleteArray !== '' ? $scope.incompleteArray+', '+(questionIndex+1) : questionIndex+1;
                $scope.quesvalidate = false;
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
    
    $scope.changeView = function( questionIndex ){
        $scope.setCanvasData($scope.questionJSON.questions[questionIndex].imgPath, $scope.canvasWidth, $scope.canvasHeigth, questionIndex );
    };
    
    /*Save & Next trigger here*/
    $scope.submitMesurementCQ = function ( navigateTo, actionMode ) {
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
                $http.post( $rootScope.MASTERS.api['questionbuilder'] , {data: angular.copy($scope.questionJSON)} ).
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
        var tempQuestionText = CKEDITOR.instances[questionName].getData();
        
        if (CKEDITOR.instances[questionName].getData()) {
            $($("[name='" + questionName + "']").next()).removeClass('cke_textarea_inline_error');
        } else {
            $($("[name='" + questionName + "']").next()).addClass('cke_textarea_inline_error');
            $scope.validate = false;
        }
        if(!$scope.questionJSON.questions[questionIndex].imgPath){
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
            var questionName = 'questionText' + (questionIndex + 1);
            
            if ($scope.questionJSON.questions[questionIndex].correctmark.length === 1) {
                angular.forEach($scope.questionJSON.questions[questionIndex].correctmark, function (childEle, childindex) {
                    $scope.questionJSON.questions[questionIndex].correctmark[childindex].val = 1;
                    $scope.questionJSON.questions[questionIndex].correctmark[childindex].marks = parseInt($('#mark' + questionIndex).val(), 10);
                });
            }
            
            $scope.questionJSON.questions[questionIndex].ques = CKEDITOR.instances[questionName].getData();
            $scope.questionJSON.questions[questionIndex].mark = parseInt($('#mark' + questionIndex).val(), 10);
            $scope.questionJSON.questions[questionIndex].correctAns = $scope.questionJSON.questions[questionIndex].correctAns ? $scope.questionJSON.questions[questionIndex].correctAns : '';
            
            CKEDITOR.instances['questionText' + $scope.showAnswerEle].setReadOnly(true);           
            
            setTimeout(function () {
                $scope.setCanvasData($scope.questionJSON.questions[questionIndex].imgPath, $scope.canvasWidth, $scope.canvasHeigth, questionIndex );
            });

        } else {
            var message = '<strong>'+$rootScope.MASTERS.keywords['typeerror']+'</strong> '+ $rootScope.MASTERS.keywords['alertsetanswer'];
            Flash.create('danger', message);
        }
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
            updateLine({r:{'top':0,'left':0}});
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
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            if( $scope.questionJSON.questions[quesIndex].view === 'vertical' ){
                $scope.questionJSON.questions[quesIndex].correctAns = x;
                line.x1 = x;
                line.y1 = 0;
                line.x2 = x;
                line.y2 = canvas.height;
            } else {
                $scope.questionJSON.questions[quesIndex].correctAns = y;
                line.x1 = 0;
                line.y1 = y;
                line.x2 = canvas.width;
                line.y2 = y;
            }
            //console.log( line );
            line.draw();            
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
                templateUrl: 'measurementPre',
                controller: 'measurementPreCtrl',
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

app.controller('measurementPreCtrl', function ( $scope, $modalInstance, questionPre, $http, $timeout, $modal ) {

    $scope.quesIndex = questionPre.questionindex;
    $scope.quesArray = questionPre.assignment;
    $scope.canvasWidth = '400';
    $scope.canvasHeigth = '300';
    $scope.stopdragging = false;

    /*Set canvas data here*/
    $scope.setCanvasDataPre = function (src, width, height) {
        var canvas = document.getElementById('measurementCanvasPre');
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
            element = $('#measurementCanvasPre');
            ctx.drawImage(img, 0, 0, canvas.height, canvas.width);
            updateLine({r:{'top':0,'left':0}});
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
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            if( $scope.questionJSON.questions[$scope.quesIndex].view === 'vertical' ){
                line.x1 = x;
                line.y1 = 0;
                line.x2 = x;
                line.y2 = canvas.height;
            } else {
                line.x1 = 0;
                line.y1 = y;
                line.x2 = canvas.width;
                line.y2 = y;
            }
            //console.log( line );
            line.draw();            
        }
    };

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
    
    $timeout(function () {
        $scope.setCanvasDataPre($scope.quesArray.questions[$scope.quesIndex].imgPath, $scope.canvasWidth, $scope.canvasHeigth);
    });
    
});
