/**
 * Created by sunny.rana on 11-08-2015.
 */
'use strict';

/**
 * @ngdoc function
 * @name questionBuilder.controller:matchdragdropCtrl
 * @description
 * # matchdragdropCtrl
 * Controller of the questionBuilder
 */
var app = angular.module('questionBuilder');

app.controller( 'matchdragdropCtrl' , function ( $scope, $window, $sce, $rootScope, $routeParams, $modal, Flash, $http, $timeout ) {

    //Accordion scope.
    $scope.oneAtATime = true;
    $scope.isFirstOpen = true;

    $scope.showAnswerEle = '';
    $scope.disabledQuestion = false;
    $scope.questionText = '';
    $scope.correctOption = '';
    $scope.questionCode = $scope.selected.questiontype;
    $scope.questionType = $rootScope.MASTERS.questiontype[$scope.questionCode];
    $scope.addQuesBtnState = false;
    if( $scope.questionData ) {
        $scope.questionJSON = angular.copy( $scope.questionData );        
        angular.forEach($scope.questionJSON.questions, function (quesEle, quesIndex) {
            if( !quesEle.header ){
                $scope.questionJSON.questions[quesIndex].header = {left: 'Column 1', right: 'Column 2'};
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
                    option: [
                        {left: '', right: ''},
                        {left: '', right: ''}
                    ],
                    header: {left: 'Column 1', right: 'Column 2'},
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
            option: [
                {left: '', right: ''},
                {left: '', right: ''}
            ],
            header: {left: 'Column 1', right: 'Column 2'},
            correctAns: [],
            correctmark: [{val: '', marks: ''}],
            mark: 1,
            questiontype: $scope.questionCode
        });
        var questionIndex = $scope.questionJSON.questions.length;
        var questionName = 'questionText' + (questionIndex);
        angular.forEach($scope.questionJSON.questions[questionIndex - 1].option, function (childEle, childindex) {
            var optionLeft = 'left' + (questionIndex) + (childindex + 1);
            var optionRight = 'right' + (questionIndex) + (childindex + 1);
            setTimeout(function () {
                CKEDITOR.inline(optionLeft, {extraPlugins: 'mathjax'});
                CKEDITOR.inline(optionRight, {extraPlugins: 'mathjax'});
            }, 100);

        });

        setTimeout(function () {
            CKEDITOR.inline(questionName, {extraPlugins: 'mathjax'});
        }, 100);
    };

    /*Add option here*/
    $scope.addOption = function (parentIndex) {

        $scope.questionJSON.questions[parentIndex].option.push({left: '', right: ''});
        angular.forEach($scope.questionJSON.questions[parentIndex].option, function (ele, index) {
            var instanceLeft = 'left' + (parentIndex + 1) + (index + 1);
            var instanceRight = 'right' + (parentIndex + 1) + (index + 1);

            if (CKEDITOR.instances[instanceLeft]) {/*do nothing.*/} else {
                $timeout(function () {
                    CKEDITOR.inline(instanceLeft, {extraPlugins: 'mathjax'});
                });
            }
            
            if (CKEDITOR.instances[instanceRight]) {/*do nothing.*/} else {
                $timeout(function () {
                    CKEDITOR.inline(instanceRight, {extraPlugins: 'mathjax'});
                });
            }

        });

    };

    /*Remove option here*/
    $scope.removeOption = function (parentIndex, optionIndex) {
        if ($scope.questionJSON.questions[parentIndex].option.length > 2) {

            angular.forEach($scope.questionJSON.questions[parentIndex].option, function (ele, index) {
                var instanceLeft = 'left' + (parentIndex + 1) + (index + 1);
                var instanceRight = 'right' + (parentIndex + 1) + (index + 1);

                CKEDITOR.instances[instanceLeft].destroy();
                CKEDITOR.instances[instanceRight].destroy();
                
                if ($scope.questionJSON.questions[parentIndex].option.length == (index + 1)) {
                    $scope.questionJSON.questions[parentIndex].option.splice(optionIndex, 1);
                }

            });
            angular.forEach($scope.questionJSON.questions[parentIndex].option, function (ele, index) {                        
                var optionLeft = 'left' + (parentIndex + 1) + (index + 1);
                var optionRight = 'right' + (parentIndex + 1) + (index + 1);
                $timeout(function (){
                    CKEDITOR.inline(optionLeft, {extraPlugins: 'mathjax'});
                    CKEDITOR.inline(optionRight, {extraPlugins: 'mathjax'});
                });
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
                    var optionLeft = 'left' + (index + 1) + (childindex + 1);
                    var optionRight = 'right' + (index + 1) + (childindex + 1);
                    CKEDITOR.instances[optionLeft].destroy();
                    CKEDITOR.instances[optionRight].destroy();

                });
                if ($scope.questionJSON.questions.length == (index + 1)) {
                    $scope.questionJSON.questions.splice(parentIndex, 1);
                    $scope.populateckeditor({action: 'remove'});
                }
            });

        }
    };
    
    /*Validate question before sumbit*/
    $scope.submitValidateQues = function(){
        $scope.quesvalidate = true
        $scope.incompleteArray = '';
        
        angular.forEach($scope.questionJSON.questions, function (ele, questionIndex) {
            
            if ( $scope.questionJSON.questions[questionIndex].correctAns.length === 0 ) {
                $scope.incompleteArray = $scope.incompleteArray !== '' ? $scope.incompleteArray+', '+(questionIndex+1) : questionIndex+1;
                $scope.quesvalidate = false;
            }
            
            var questionName = 'questionText' + (questionIndex + 1);
            $scope.questionJSON.questions[questionIndex].ques = CKEDITOR.instances[questionName].getData();
            $scope.questionJSON.questions[questionIndex].mark = parseInt($('#mark' + questionIndex).val(), 10);
            
            if (CKEDITOR.instances[questionName].getData()) {
                
                angular.forEach($scope.questionJSON.questions[questionIndex].option, function (childEle, childindex) {
                    var optionLeft = 'left' + (questionIndex + 1) + (childindex + 1);
                    var optionRight = 'right' + (questionIndex + 1) + (childindex + 1);
                    $scope.questionJSON.questions[questionIndex].option[childindex].left = CKEDITOR.instances[optionLeft].getData();
                    $scope.questionJSON.questions[questionIndex].option[childindex].right = CKEDITOR.instances[optionRight].getData();
                    if (CKEDITOR.instances[optionLeft].getData()) {
                    } else {
                        $scope.quesvalidate = false;
                    }
                    if (CKEDITOR.instances[optionRight].getData()) {
                    } else {
                        $scope.quesvalidate = false;
                    }
                });

            } else {
                $scope.quesvalidate = false;
                
                angular.forEach($scope.questionJSON.questions[questionIndex].option, function (childEle, childindex) {
                    var optionLeft = 'left' + (questionIndex + 1) + (childindex + 1);
                    var optionRight = 'right' + (questionIndex + 1) + (childindex + 1);
                    $scope.questionJSON.questions[questionIndex].option[childindex].left = CKEDITOR.instances[optionLeft].getData();
                    $scope.questionJSON.questions[questionIndex].option[childindex].right = CKEDITOR.instances[optionRight].getData();
                    if (CKEDITOR.instances[optionLeft].getData()) {
                    } else {
                        $scope.quesvalidate = false;
                    }
                    if (CKEDITOR.instances[optionRight].getData()) {
                    } else {
                        $scope.quesvalidate = false;
                    }
                });
            }
        });        
        return $scope.quesvalidate;   
    };  
    
    /*Save & Next trigger here*/
    $scope.submitMatchDDCQ = function ( navigateTo, actionMode ) {
        if($scope.submitValidateQues()){
            $scope.questionJSON.questionparam.dynamicId1 = $scope.selected.dynamicId1;
            $scope.questionJSON.questionparam.dynamicId2 = $scope.selected.dynamicId2;
            $scope.questionJSON.questionparam.quesnote = $scope.selected.quesnote;
            $scope.questionJSON.description = CKEDITOR.instances['descriptionText'].getData();
            console.log( $scope.questionJSON );
            
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
        if ($scope.questionJSON.questions[parentIndex].option.length > $scope.questionJSON.questions[parentIndex].correctmark.length) {
            var tempVar = $scope.questionJSON.questions[parentIndex].correctmark;
            var valueInput = tempVar[tempVar.length - 1].val ? tempVar[tempVar.length - 1].val - 1 : ($scope.questionJSON.questions[parentIndex].option.length - 1);
            var marksInput = tempVar[tempVar.length - 1].marks ? tempVar[(tempVar.length) - 1].marks - 1 : (parseInt($('#mark' + parentIndex).val(), 10) - 1);
            if (valueInput === 0) {
                valueInput = $scope.questionJSON.questions[parentIndex].option.length;
            }
            if (marksInput === 0) {
                marksInput = parseInt($('#mark' + parentIndex).val(), 10);
            }
            $scope.questionJSON.questions[parentIndex].correctmark.push({val: valueInput, marks: marksInput});
        } else {
            var message = '<strong>'+$rootScope.MASTERS.keywords['typeerror']+'</strong> Max ' + $scope.questionJSON.questions[parentIndex].option.length + ' option allowed.';
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
                var optionLeft = 'left' + ($scope.tempQuestionIndex + 1) + (childindex + 1);
                var optionRight = 'right' + ($scope.tempQuestionIndex + 1) + (childindex + 1);
                $($("[name='" + optionLeft + "']").next()).removeClass('cke_textarea_inline_error');
                $($("[name='" + optionRight + "']").next()).removeClass('cke_textarea_inline_error');
            });
        }, 5000);

        var questionName = 'questionText' + (questionIndex + 1);
        if (CKEDITOR.instances[questionName].getData()) {

            $($("[name='" + questionName + "']").next()).removeClass('cke_textarea_inline_error');
            angular.forEach($scope.questionJSON.questions[questionIndex].option, function (childEle, childindex) {
                var optionLeft = 'left' + (questionIndex + 1) + (childindex + 1);
                var optionRight = 'right' + (questionIndex + 1) + (childindex + 1);
                if (CKEDITOR.instances[optionLeft].getData()) {
                    $($("[name='" + optionLeft + "']").next()).removeClass('cke_textarea_inline_error');
                } else {
                    $($("[name='" + optionLeft + "']").next()).addClass('cke_textarea_inline_error');
                    $scope.validate = false;
                }
                if (CKEDITOR.instances[optionRight].getData()) {
                    $($("[name='" + optionRight + "']").next()).removeClass('cke_textarea_inline_error');
                } else {
                    $($("[name='" + optionRight + "']").next()).addClass('cke_textarea_inline_error');
                    $scope.validate = false;

                }
            });

        } else {

            $($("[name='" + questionName + "']").next()).addClass('cke_textarea_inline_error');
            $scope.validate = false;

            angular.forEach($scope.questionJSON.questions[questionIndex].option, function (childEle, childindex) {
                var optionLeft = 'left' + (questionIndex + 1) + (childindex + 1);
                var optionRight = 'right' + (questionIndex + 1) + (childindex + 1);
                if (CKEDITOR.instances[optionLeft].getData()) {
                    $($("[name='" + optionLeft + "']").next()).removeClass('cke_textarea_inline_error');
                } else {
                    $($("[name='" + optionLeft + "']").next()).addClass('cke_textarea_inline_error');
                }

                if (CKEDITOR.instances[optionRight].getData()) {
                    $($("[name='" + optionRight + "']").next()).removeClass('cke_textarea_inline_error');
                } else {
                    $($("[name='" + optionRight + "']").next()).addClass('cke_textarea_inline_error');
                }

            });
        }

        return $scope.validate;
    };

    /*Set Answer trigger here*/
    $scope.setAnswer = function (index) {
        var tempIndex = index;
        var questionEdit = false;
        var optiontextcheck = false;
        $scope.tempCorrectAnser = [];
        $scope.questionJSON.questions[index].mark = parseInt($('#mark' + index).val(), 10);
        $scope.validatedVal = $scope.validateQuestion(index);
        if ($scope.validatedVal) {
            
            if( $scope.questionJSON.questions[index].correctAns.length !== $scope.questionJSON.questions[index].option.length ){
                $scope.questionJSON.questions[index].correctAns = [];
                questionEdit = true;
            } else if ( $scope.questionJSON.questions[index].correctAns.length ===  $scope.questionJSON.questions[index].option.length) {
                angular.forEach($scope.questionJSON.questions[index].option, function (childEle, childindex) {
                    var optionName = 'right' + (index + 1) + (childindex + 1);
                    if($scope.questionJSON.questions[index].option[childindex].right !== CKEDITOR.instances[optionName].getData()){                        
                        $scope.questionJSON.questions[index].correctAns = [];
                        $scope.tempCorrectAnser = [];
                        optiontextcheck = true;
                    }
                });
                $scope.tempCorrectAnser = $scope.questionJSON.questions[index].correctAns;
                
            }
            if ($scope.questionJSON.questions[index].correctmark.length === 1 && $scope.questionJSON.questions[index].correctmark[0].val === '') {
                angular.forEach($scope.questionJSON.questions[index].correctmark, function (childEle, childindex) {
                    $scope.questionJSON.questions[index].correctmark[childindex].val = $scope.questionJSON.questions[index].option.length;
                    $scope.questionJSON.questions[index].correctmark[childindex].marks = parseInt($('#mark' + index).val(), 10);
                });
            }
            $scope.showAnswerEle = index + 1;
            $scope.addQuesBtnState = true;
            angular.forEach($scope.questionJSON.questions, function (ele, index) {
                var questionName = 'questionText' + (index + 1);
                angular.forEach(ele.option, function (childEle, childindex) {
                    var optionLeft = 'left' + (index + 1) + (childindex + 1);
                    var optionRight = 'right' + (index + 1) + (childindex + 1);
                    $scope.questionJSON.questions[index].option[childindex].left = CKEDITOR.instances[optionLeft].getData();
                    $scope.questionJSON.questions[index].option[childindex].right = CKEDITOR.instances[optionRight].getData();
                    if( (questionEdit || optiontextcheck) && tempIndex == index){
                        if( childindex == 0 ){
                            $scope.questionJSON.questions[index].correctAns = [];
                            $scope.tempCorrectAnser = [];
                        }
                        $scope.questionJSON.questions[index].correctAns.push(CKEDITOR.instances[optionRight].getData());
                        $scope.tempCorrectAnser.push(CKEDITOR.instances[optionRight].getData());
                    }
                    
                    CKEDITOR.instances[optionLeft].setReadOnly(true);
                    CKEDITOR.instances[optionRight].setReadOnly(true);

                });
                $scope.questionJSON.questions[index].ques = CKEDITOR.instances[questionName].getData();
                CKEDITOR.instances[questionName].setReadOnly(true);
            });
            
            setTimeout(function(){
                var tempheight = '';
                $.each( $($('#setheight'+tempIndex).find('.sorting-answer')), function( index, ele ){
                    if( tempheight < $(ele).height()){
                        tempheight = $(ele).height() < 40 ? 40 : $(ele).height();
                        $($('#setheight'+tempIndex).find('.sorting-answer')).css('min-height', tempheight);
                    }                
                });     
            });
            
            $(".sortable").sortable({
                cursor: 'crosshair',
                start: function( event, ui ){
                    $(ui.item).addClass("custom-drag-ele");
                },
                stop: function ( event, ui ) {
                    $(ui.item).removeClass("custom-drag-ele");
                    var questionIndex = '';
                    questionIndex = $(this).children().attr('name');
                    $scope.questionJSON.questions[questionIndex].correctAns = [];
                    
                    $(this).find('.sorting-answer').each(function (index, ele) {
                        var eleId = $(ele).attr('id');
                        var optionIndex = eleId.split('_')[2];                               
                        $scope.questionJSON.questions[questionIndex].correctAns.push( $scope.tempCorrectAnser[optionIndex] );
                    });
                    console.log( $scope.questionJSON.questions[questionIndex].correctAns );
                }
            });
            $(".sortable").disableSelection();
            $timeout(function(){
                $('.sorting-answer').css('height', $('.sorting-answer').height());
            },100);
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
                var optionLeft = 'left' + (index + 1) + (childindex + 1);
                var optionRight = 'right' + (index + 1) + (childindex + 1);
                CKEDITOR.instances[optionLeft].setReadOnly(false);
                CKEDITOR.instances[optionRight].setReadOnly(false);

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
                angular.forEach(ele.option, function (childEle, childindex) {
                    var optionLeft = 'left' + (index + 1) + (childindex + 1);
                    var optionRight = 'right' + (index + 1) + (childindex + 1);
                    $scope.questionJSON.questions[index].option[childindex].left = CKEDITOR.instances[optionLeft].getData();
                    $scope.questionJSON.questions[index].option[childindex].right = CKEDITOR.instances[optionRight].getData();

                });
                $scope.questionJSON.questions[index].ques = CKEDITOR.instances[questionName].getData();
            });

            var modalInstance = $modal.open({
                templateUrl: 'matchdragdropPre',
                controller: 'matchdragdropPreCtrl',
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
            angular.forEach(ele.option, function (childEle, childindex) {
                var optionLeft = 'left' + (index + 1) + (childindex + 1);
                var optionRight = 'right' + (index + 1) + (childindex + 1);
                setTimeout(function () {
                    CKEDITOR.inline(optionLeft, {extraPlugins: 'mathjax'});
                    CKEDITOR.inline(optionRight, {extraPlugins: 'mathjax'});
                }, 100);

            });

            if (CKEDITOR.instances[questionName]) {
                CKEDITOR.instances[questionName].destroy();
                setTimeout(function () {
                    CKEDITOR.inline(questionName, {extraPlugins: 'mathjax'});
                });

            } else {
                setTimeout(function () {
                    CKEDITOR.inline(questionName, {
                        extraPlugins: 'mathjax'
                    });
                }, 100);

            }

        });

    };

    setTimeout(function () {
        CKEDITOR.config.toolbar = $rootScope.MASTERS.editorConfig;
        CKEDITOR.inline('descriptionText', {extraPlugins: 'mathjax'});
        $scope.populateckeditor();

    }, 10);

});

app.controller('matchdragdropPreCtrl', function ( $scope, $modalInstance, questionPre, $timeout, $http, $modal ) {

    $scope.quesIndex = questionPre.questionindex;
    $scope.quesArray = questionPre.assignment;
    var isDialogOpen = false, myLines = [], svg = null;
	$scope.leftPersons = [];
	$scope.rightPersons = [];
	$scope.mappingResult = [];
    
    angular.forEach($scope.quesArray.questions[$scope.quesIndex].option, function(ele, index){
        $scope.leftPersons.push({id: index,name: ele.left,cls: 'draggable'});
        $scope.rightPersons.push({id: index,name: ele.right,cls: 'droppable'});
    });
    
    $scope.ok = function(){
        $modalInstance.dismiss('cancel');
    };
    
    $scope.cancel = function(){
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
    
    $scope.initialize = function(){
        // set up the drawing area from Body of the document
        //  -30px for the offset...
        $("#svgbasics").css("height", $("#pnlAllIn").height()).css("width", $("#pnlAllIn").width());
        
        // all draggable elements
        $("div .draggable").draggable({revert: true, snap: false,cursor: 'crosshair',
            start: function( event, ui ){
                $(this).addClass("custom-drag-ele");
            },
            stop: function ( event, ui ) {
                $(this).removeClass("custom-drag-ele");
            }
        });
        
        // all droppable elements
        $("div .droppable").droppable({hoverClass: "ui-state-hover",cursor: "move",
            drop: function(event, ui){
                
                // disable it so it can"t be used anymore		
                $(this).droppable("disable");
                                
                var sourceValue = $(ui.draggable).find("input:hidden").val();
                var targetValue = $(this).find("input:hidden").val();
                                
                // change the input element to contain the mapping target and source
                $(ui.draggable).find("input:hidden").val(sourceValue + "_" + targetValue);
                
                // disable it so it can"t be used anymore	
                $(ui.draggable).draggable("disable");
                
                svgDrawLine($(this), $(ui.draggable));
            }
        });
		
        svg = Raphael("svgbasics", $("#svgbasics").width(), $("#svgbasics").height());
    };
    
    $scope.clearMapping = function(){
             $("#leftPanel").find("input:hidden").each(function(){
        $(this).val($(this).val().split("_")[0]);
    });
            $("div .draggable").removeClass("ui-draggable-handle");
            $("div .draggable").removeClass("ui-draggable-disabled");
            $scope.svgClear();		
    };

    $scope.getMappingVal = function (){
            console.log($('#leftPanel').find("input:hidden"));
    };

    $scope.svgClear = function(){
        svg.clear();
    };
    
    function svgDrawLine(eTarget, eSource){
    
        // wait 1 sec before draw the lines, so we can get the position of the draggable
        $timeout(function(){
        
            var $source = eSource;
            var $target = eTarget;
            
            var originX = ($source.offset().left + $source.width() + 48) - $('#pnlAllIn').offset().left;
            var originY = ($source.offset().top + (($source.height() + 4 ) / 2)) - $('#pnlAllIn').offset().top;
            var endingX = ($target.offset().left + 46) - $('#pnlAllIn').offset().left;
            var endingY = ($target.offset().top + (($target.height() + 4 ) / 2)) - $('#pnlAllIn').offset().top;
            
            var space = 20;
            //var color = colours[random(9)];
            
            // draw lines
            // http://raphaeljs.com/reference.html#path
			console.log( originX, originY );			
            var a = "M" + originX + " " + originY + " L" + (originX + space) + " " + originY; // beginning
            var b = "M" + (originX + space) + " " + originY + " L" + (endingX - space) + " " + endingY; // diagonal line
            var c = "M" + (endingX - space) + " " + endingY + " L" + endingX + " " + endingY; // ending
            var all = a + " " + b + " " + c;
            
            /**/
             // log (to show in FF (with FireBug), Chrome and Safari)
             //console.log("New Line ----------------------------");
             //console.log("originX: " + originX + " | originY: " + originY + " | endingX: " + endingX + " | endingY: " + endingY + " | space: " + space );
             //console.log(all);
             /**/
            // write line
            myLines[myLines.length] = svg.path(all).attr({
                "stroke": 'red',
                "stroke-width": 2,
                "stroke-dasharray": ""
            });
            
        }, 1000);        
    }    
    setTimeout(function(){
        var tempheight = '';
        $.each( $($('#presetheight'+$scope.quesIndex).find('.person')), function( index, ele ){
            if( tempheight < $(ele).height()){
                tempheight = $(ele).height();
                $($('#presetheight'+$scope.quesIndex).find('.person')).css('min-height', tempheight);
            }                
        });
    });
    $timeout(function(){
        // initialize & setup everything
        $scope.initialize();        
    },1000);
    
});

