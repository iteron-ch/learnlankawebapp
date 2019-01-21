/**
 * Created by sunny.rana on 14-12-2015.
 */
'use strict';
/**
 * @ngdoc function
 * @name printPopUp.controller:MainCtrl
 * @description
 * # printPopUpCtrl
 * Controller of the printPopUp
 */
var app = angular.module('printPopUp', ['ngAnimate', 'ngRoute','easypiechart']);
/*Config: Here we set routing start*/
app.config(function($interpolateProvider) {
    $interpolateProvider.startSymbol('[%');
    $interpolateProvider.endSymbol('%]');
});

/*Config: Here we set routing End*/
app.directive('myRepeatDirective', function() { 
  return function(scope, element, attrs) {
    if (scope.$last){
        setTimeout(function(){ 
            var quesId= element[0].id.split('_')[2];
            var tempheight = '';
            $.each( $($('#correctAnssetheight'+quesId).find('.person')), function( index, ele ){
                if( tempheight <   $(ele).height()){
                    tempheight = $(ele).height();
                    $($('#correctAnssetheight'+quesId).find('.person')).css('min-height', tempheight);
                }                
            });            
        });      
    }
  };
});

/*Controller: Print Question section start*/
app.controller('printPopUpCtrl', function($scope, $rootScope) {
    $scope.dragObjArray = ['.', ',', ':', ';', '?', '!', " ' ", '"', '_', '-', '...', '(', ')'];
    $rootScope.MASTERS = {};
    $scope.initData = function(data) {
        console.log(data);
        $scope.checkMode = data.mode;
        $rootScope.MASTERS.questions = data.questions;
    };
    $scope.printPage = function(id) {
        document.title = "www.satscompanion.com"
        $("#print_btn").hide();
        window.print();
        $("#print_btn").show();
        document.title = "SATS"
    };


    $scope.preCanvasRender = function(quesId) {
        console.log(quesId);
        var canvas = new fabric.Canvas('preCanvas' + quesId, {selection: false});
        var canvas1 = new fabric.Canvas('preCanvasImg' + quesId);
        var line, isDown, imageUrl;
        imageUrl = "/questionimg/" + $rootScope.MASTERS.questions[quesId].imgPath;

        fabric.Image.fromURL(imageUrl, function(oImg) {

        });
    };
});
app.controller('singleChoiceCtrl', function($scope, $rootScope, $timeout) {
    $scope.radioCheck = [];
    $scope.init = function(setIndex, questionindex) {
        $scope.setIndex = setIndex;
        $scope.quesIndex = questionindex;
    };
    $timeout(function() {
       angular.forEach($rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].option, function(childEle, childindex) {
            $scope.radioCheck[$scope.setIndex+$scope.quesIndex+childindex] = false;
            if (childindex == $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].correctAns) {
                $scope.radioCheck[$scope.setIndex+$scope.quesIndex+childindex] = true;
            }
        });
    });
});

app.controller('booleanCtrl', function($scope, $rootScope, $timeout) {
    $scope.radioBooleanCheck = [];
    $scope.init = function(setIndex, questionindex) {
        $scope.setIndex = setIndex;
        $scope.quesIndex = questionindex;
    };
    $timeout(function() {
       angular.forEach($rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].option, function(childEle, childindex) {
            $scope.radioBooleanCheck[$scope.setIndex+$scope.quesIndex+childindex] = false;
            if (childindex == $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].correctAns[0].main) {
                $scope.radioBooleanCheck[$scope.setIndex+$scope.quesIndex+childindex] = true;
            }
        });
    },1000);
});

app.controller('singlemultipleentryCtrl', function($scope, $rootScope, $timeout) {
    $scope.radioCheck = [];
    $scope.init = function(setIndex, questionindex) {
        $scope.setIndex = setIndex;
        $scope.quesIndex = questionindex;
    };
    $timeout(function() {
        if($rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].questype == 'single')
        {
            angular.forEach($rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].correctAns.option, function(childEle, childindex) {
                angular.forEach(childEle, function(fieldEle, fieldindex) {
                    //console.log(fieldEle.title , $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].headercol , childindex,fieldEle.title ,$rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].headerrow, fieldindex);
                    if((fieldEle.title && !$rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].headercol && childindex == 0) || (fieldEle.title && !$rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].headerrow && fieldindex == 0)){
                        $scope.radioCheck[$scope.setIndex+$scope.quesIndex+childindex+fieldindex] = false;
                        if (fieldindex == fieldEle.checkvalue) {
                            $scope.radioCheck[$scope.setIndex+$scope.quesIndex+childindex+fieldindex] = true;
                        }
                        //console.log(childindex+'_'+fieldindex,$scope.radioCheck[$scope.setIndex+$scope.quesIndex+childindex+fieldindex]);
                    }
                    
                });
                
            });
        }
    });
});

app.controller('fillintheBlanksCtrl', function($scope, $rootScope, $timeout) {
    $scope.init = function(setIndex, questionindex) {
        $scope.setIndex = setIndex;
        $scope.quesIndex = questionindex;
    };

    $timeout(function() {
        var objQues = $.parseHTML($rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].ques);
        if ($(objQues).find('input').length) {
            angular.forEach($(objQues).find('input'), function(ele, index) {
                $($('#filltheblankscorrectAns_' + $scope.setIndex + $scope.quesIndex).find('input')[index]).val($rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].correctAns[index].value);
            });
        }
    })
});
app.controller('selectliteracyCtrl', function($scope, $rootScope, $timeout) {
    $scope.init = function(setIndex, questionindex) {
        $scope.setIndex = setIndex;
        $scope.quesIndex = questionindex;
    };

    $timeout(function() {
        var tempindex = 0;
        angular.forEach($rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].option, function(childEle, childindex) {
            if ($rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].option[childindex].ischeck) {
                var answerID = 'Correctanswer_' + $scope.setIndex + $scope.quesIndex + childindex;
                $('#' + answerID).val($rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].correctAns[tempindex]);
                tempindex = tempindex + 1;
            }
        });
    });
});
app.controller('numberwordselectCtrl', function($scope, $rootScope, $timeout) {
    $scope.init = function(setIndex, questionindex) {
        $scope.setIndex = setIndex;
        $scope.quesIndex = questionindex;
    };

    $timeout(function() {
        angular.forEach($rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].option.optionvalue, function(optionEle, optionIndex) {
            var result = $.grep($rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].correctAns, function(e) {
                return e === optionEle.value;
            });
            if (result.length !== 0) {
                $('#correctAns' + $scope.setIndex + '_' + $scope.quesIndex + '_' + optionIndex).addClass($rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].option.draw + '-cls');
            }
        });
    });
});

app.controller('reflectionCtrl', function($scope, $rootScope, $timeout) {
    $scope.init = function(setIndex, questionindex) {
        $scope.setIndex = setIndex;
        $scope.quesIndex = questionindex;
    };

    $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].ans_correct = $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].correctAns;
    $timeout(function() {
        $(window).trigger('resize');
        var forDotsjoin = {
            'ques': $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex],
            'action': 'ques',
            'addevent': false

        };
        var lock = new Reflection("#setpatternContainer_ques_" + $scope.setIndex + $scope.quesIndex, forDotsjoin);
        if ($rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].correctAns) {
            var lockcorrectAns = new Reflection("#correctAnssetpatternContainer_ques_" + $scope.setIndex + $scope.quesIndex, forDotsjoin);
        }
        forDotsjoin.action = 'ans';
        forDotsjoin.addevent = true;
        var lock = new Reflection("#setpatternAnswer_ans_" + $scope.setIndex + $scope.quesIndex, forDotsjoin);
    });
    
    $timeout(function() {
        var forDotsjoin = {
            'ques': $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex],
            'action': 'ques',
            'addevent': false
        }
        var lock = new Reflection("#prepatternContainer_ques_" + $rootScope.MASTERS.questions[$scope.setIndex].id + "_" + $scope.quesIndex, forDotsjoin);
        forDotsjoin.action = '';
        forDotsjoin.addevent = false;
        var lock = new Reflection("#prepatternAnswer_ans_" + $rootScope.MASTERS.questions[$scope.setIndex].id + "_" + $scope.quesIndex, forDotsjoin);
    }, 100);    
});
app.controller('joiningdotsCtrl', function($scope, $rootScope, $timeout) {
    $scope.init = function(setIndex, questionindex) {
        $scope.setIndex = setIndex;
        $scope.quesIndex = questionindex;
    };

    $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].ans_correct = $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].correctAns;
    $timeout(function() {
        $(window).trigger('resize');
        
       var forDotsjoinCorrectAns = {
            'ques': $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex],
            'userresponse' : $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].correctAns,
            'action': 'student'
        }
        var lockCorrectAns = new DotsJoin("#patternContainerCorrectAns"+ $scope.setIndex + $scope.quesIndex, forDotsjoinCorrectAns );
    });
    
});
app.controller('symmetricCtrl', function($scope, $rootScope, $timeout) {
    $scope.percentrCorrectAns = [];
    $scope.init = function(setIndex, questionindex) {
        $scope.setIndex = setIndex;
        $scope.quesIndex = questionindex;
    };
    
    
    
    $timeout(function(){
        $(window).trigger('resize');   
        var forDotsjoin = {
            'ques': $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex],
            'action': 'ques',
            'addevent' : false
        };
        var lockCorrectAns = new Reflection("#patternContainerCorrectAns"+$scope.setIndex+$scope.quesIndex, forDotsjoin ); 
    });
});

app.controller('matchdragdropCtrl', function($scope, $rootScope, $timeout) {
    $scope.init = function(setIndex, questionindex) {
        $scope.setIndex = setIndex;
        $scope.quesIndex = questionindex;
    };
    var myLinescorrectAns, svgcorrectAns;
    
    $scope.leftPersonscorrectAns = [];
    $scope.rightPersonscorrectAns = [];
    angular.forEach($rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].option, function(ele, childIndex){
        $scope.leftPersonscorrectAns.push({id: 0,name: ele.left,cls: 'draggable'});
        $scope.rightPersonscorrectAns.push({id: 0,name: ele.right,cls: 'droppable'});
    }); 
    $timeout(function(){
        console.log( "calling initialize...correctAns" );
        $scope.initializecorrectAns('edit');  
    },1000);
    $timeout(function(){
        $(window).trigger('resize');        
    });
    $scope.initializecorrectAns = function(mode ){
        // set up the drawing area from Body of the document
        console.log("calling function..");
        myLinescorrectAns = [];
        $("#correctAnssvgbasics"+$scope.setIndex+$scope.quesIndex).css("height", $("#correctAnspnlAllIn"+$scope.setIndex+$scope.quesIndex).height()).css("width", $("#correctAnspnlAllIn"+$scope.setIndex+$scope.quesIndex).width());

        // all draggable elements
        $("div .draggable").draggable({revert:true, snap: false, cursor: 'crosshair',
            start: function( event, ui ){
                $(this).addClass("custom-drag-ele");
            },stop: function ( event, ui ) {
                $(this).removeClass("custom-drag-ele");
            }
        });

        // all droppable elements
        $("div .droppable").droppable({hoverClass: "ui-state-hover",cursor: "move",
            drop: function(event, ui){    
                // disable it so it can"t be used anymore		
                $(this).droppable("disable");
                var pindex = $(this).attr('id').split("_")[2];                
                var optionindex = $(this).attr('id').split("_")[1]; 
                var sourceValue = $(ui.draggable).attr("id").split("_")[1];
                var targetValue = optionindex;
                // change the input element to contain the mapping target and source
                $(ui.draggable).find("input:hidden").val(sourceValue + "_" + targetValue);
                $rootScope.renderQuestion[$scope.currentQues-1].correctAns[pindex][optionindex].source = sourceValue;
                $rootScope.renderQuestion[$scope.currentQues-1].correctAns[pindex][optionindex].target = targetValue;
                // disable it so it can"t be used anymore	
                $(ui.draggable).draggable("disable");

                svgDrawLinecorrectAns($(this), $(ui.draggable));
            }
        });
        if( $('#correctAnssvgbasics'+$scope.setIndex+$scope.quesIndex).find('svg').length ){
            $('#correctAnssvgbasics'+$scope.setIndex+$scope.quesIndex).find('svg').remove();
        }
        svgcorrectAns = Raphael("correctAnssvgbasics"+$scope.setIndex+$scope.quesIndex, $("#correctAnssvgbasics"+$scope.setIndex+$scope.quesIndex).width(), $("#correctAnssvgbasics"+$scope.setIndex+$scope.quesIndex).height());
        if( mode === 'edit'){
            angular.forEach( $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].option, function (optionEle, optionIndex) {        
                var userresponsetext = $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].correctAns[optionIndex];
                if( userresponsetext.source && userresponsetext.target ){
                    var sourceValue = $('#correctAnsl_'+userresponsetext.source+'_'+$scope.setIndex+$scope.quesIndex);
                    var targetValue = $('#correctAnsr_'+userresponsetext.target+'_'+$scope.setIndex+$scope.quesIndex);
                    sourceValue.find("input:hidden").val(userresponsetext.source + "_" + userresponsetext.target);
                    svgDrawLinecorrectAns(targetValue, sourceValue);
                }
            });
        }
        
    };
    function svgDrawLinecorrectAns(eTarget, eSource){
        // wait 1 sec before draw the lines, so we can get the position of the draggable
        $timeout(function(){
            var $source = eSource;
            var $target = eTarget;
            var originX = ($source.offset().left + $source.width() + 48) - $('#correctAnspnlAllIn'+$scope.setIndex+$scope.quesIndex).offset().left;
            var originY = ($source.offset().top + (($source.height() + 4 ) / 2)) - $('#correctAnspnlAllIn'+$scope.setIndex+$scope.quesIndex).offset().top;
            var endingX = ($target.offset().left + 46) - $('#correctAnspnlAllIn'+$scope.setIndex+$scope.quesIndex).offset().left;
            var endingY = ($target.offset().top + (($target.height() + 4 ) / 2)) - $('#correctAnspnlAllIn'+$scope.setIndex+$scope.quesIndex).offset().top;
            
            var space = 20;            
            var a = "M" + originX + " " + originY + " L" + (originX + space) + " " + originY; // beginning
            var b = "M" + (originX + space) + " " + originY + " L" + (endingX - space) + " " + endingY; // diagonal line
            var c = "M" + (endingX - space) + " " + endingY + " L" + endingX + " " + endingY; // ending
            var all = a + " " + b + " " + c;
            myLinescorrectAns[myLinescorrectAns.length] = svgcorrectAns.path(all).attr({
                "stroke": 'red',
                "stroke-width": 2,
                "stroke-dasharray": ""
            });
            
        }, 1000);  
        
    }
});

app.controller('insertliteracyCtrl', function($scope, $rootScope, $timeout) {
    $scope.dragObjArray = ['.', ',', ':', ';', '?', '!', " ' ", '"', '_', '-', '...', '(', ')'];
    $scope.percentrCorrectAns = [];
    $scope.init = function(setIndex, questionindex) {
        $scope.setIndex = setIndex;
        $scope.quesIndex = questionindex;
    };
    
    $timeout(function(){
        angular.forEach($('#textEditorCorrectAns'+$scope.setIndex+$scope.quesIndex).find('input[name^="qus_ans'+$scope.setIndex+$scope.quesIndex+'"]'), function (ele, indexChild) {
            $($('#textEditorCorrectAns'+$scope.setIndex+$scope.quesIndex).find('input[name^="qus_ans'+$scope.setIndex+$scope.quesIndex+'"]')[indexChild]).val($rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].correctAns[indexChild]);            
        });       
    });
    $scope.bindHtmlText = function( text){
        return text.split(' ').join('&nbsp;<input type="text" name="qus_ans'+$scope.setIndex+$scope.quesIndex+'" class="droppable insert-literacy-text drop-inputbox" >&nbsp;') ;
    };
});

app.controller('dragdropCtrl', function($scope, $rootScope, $timeout) {
    $scope.init = function(setIndex, questionindex) {
        $scope.setIndex = setIndex;
        $scope.quesIndex = questionindex;
    };
    
    $scope.bindDropObject = function(opIndex){ 
        var ansobject = $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].correctAns;
        if ( ansobject.length ){
            return ansobject[opIndex];
        } else {
            return '';
        }
    };
    
    $scope.getActiveClass = function( opIndex){
        var ansobject = $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].correctAns;
        if ( ansobject.length ){
            if( ansobject[opIndex] ){
                return 'activedropbox';
            }else {
                return '';
            }
        }
    };
    
    $scope.bindDropObjectSortable = function( optionIndex){
        var ansobject = $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].correctAns;
        if ( ansobject.length ){
            return ansobject[optionIndex];
        } else {
            return $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].option.optionvalue[optionIndex].value;
        }
    };
    
});
app.controller('drawlineonimageCtrl', function($scope, $rootScope, $timeout) {
    $scope.percentrCorrectAns = [];
    $scope.init = function(setIndex, questionindex) {
        $scope.setIndex = setIndex;
        $scope.quesIndex = questionindex;
    };
    $scope.setanswerCanvasRenderCorrectAns = function(){
        var canvas = new fabric.Canvas('setansCanvasCorrectAns'+$scope.setIndex+$scope.quesIndex, { selection: false });
	var canvas1 = new fabric.Canvas('setansCanvasImgCorrectAns'+$scope.setIndex+$scope.quesIndex);
	var line, isDown, imageUrl, isMove;
        var correctpoints, correctset;
        imageUrl = "/questionimg/"+$rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].imgPath;
	fabric.Image.fromURL(imageUrl, function(oImg) {
	  canvas1.add(oImg);
	});	
        
        if( $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].option.length ){
             angular.forEach($rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].option, function (eoption, ioption) {
               canvas.add(new fabric.Circle({ radius: 5, fill: 'red', top: eoption.top, left: eoption.left }));                
               canvas.item(ioption).selectable = false;
            });            
        }
        
        if( $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].correctAns.length ){
            angular.forEach($rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].correctAns, function (uoption, iuoption) {
                line = new fabric.Line(uoption.points, {
                    strokeWidth: 5,
                    fill: 'red',
                    stroke: 'red',
                    originX: 'center',
                    originY: 'center'
                });
                canvas.add(line);

                line.set(uoption.set);
                canvas.renderAll();
            }); 
            
        }
    };
    $timeout(function(){
        $(window).trigger('resize');        
         $scope.setanswerCanvasRenderCorrectAns();
    });
});

app.controller('measurementCtrl', function($scope, $rootScope, $timeout) {
    $scope.init = function(setIndex, questionindex) {
        $scope.setIndex = setIndex;
        $scope.quesIndex = questionindex;
    };

    $scope.canvasWidth = '600';
    $scope.canvasHeigth = '500';
    $scope.stopdragging = false;

    /*Set canvas data here*/
    $scope.setCanvasDataPre = function(src, width, height) {
        var canvas = document.getElementById('measurementCanvasPre_' + $rootScope.MASTERS.questions[$scope.setIndex].id + '_' + $scope.quesIndex);
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

            this.draw = function() {
                ctx.beginPath();
                ctx.moveTo(me.y1, me.x1);
                ctx.lineTo(me.y2, me.x2);
                ctx.stroke();
            }
        }
        img.onload = start;
        img.src = '/questionimg/' + src;
        img.height = height + 'px';
        img.width = width + 'px';
        function start() {
            var element = '';
            element = $('measurementCanvasPre_' + $rootScope.MASTERS.questions[$scope.setIndex].id + '_' + $scope.quesIndex);
            ctx.drawImage(img, 0, 0, canvas.height, canvas.width);
            updateLine({r: {'top': 0, 'left': 0}});
            canvas.addEventListener('mousemove', function(e) {
                if (!$scope.stopdragging) {
                    updateLine(e);
                }
            });
            element.unbind().bind('click', function(e) {
                $scope.stopdragging = !$scope.stopdragging;
            });
        }
        function updateLine(e) {
            
var r = canvas.getBoundingClientRect();
            ctx.rect(0,0,canvas.width, canvas.height);
            ctx.fillStyle="#fff";
            ctx.fill();
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            if( $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].view === 'vertical' ){
               var x = $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].correctAns;
               var y = 0;                
                $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].correctAns = x;
                line.x1 = x;
                line.y1 = 0;
                line.x2 = x;
                line.y2 = canvas.height;
            } else {
               var x = 0;
               var y = $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].correctAns;                                
                $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].correctAns = y;
                line.x1 = 0;
                line.y1 = y;
                line.x2 = canvas.width;
                line.y2 = y;
            }
            //console.log( line );
            line.draw();                      
            /*var r = canvas.getBoundingClientRect(),
                    x = e.clientY - r.top,
                    y = e.clientX - r.left;
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            if ($rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].view === 'vertical') {
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
            line.draw();*/
        }
    };

    $timeout(function() {
        $scope.setCanvasDataPre($rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].imgPath, $scope.canvasWidth, $scope.canvasHeigth);
    });
});

app.controller('piechartCtrl', function($scope, $rootScope, $timeout) {
    $scope.percentrCorrectAns = [];
    $scope.init = function(setIndex, questionindex) {
        $scope.setIndex = setIndex;
        $scope.quesIndex = questionindex;
    };
    $scope.percentrCorrectAns[$scope.setIndex+$scope.quesIndex] = $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].correctAns[0].value; 
    $timeout(function(){
        $(window).trigger('resize');        
    });
});
/*Filter: For pass trusted html and mathjax text render start*/
app.filter('to_trusted', function($sce) {
    return function(text) {
        MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
        return $sce.trustAsHtml(text);
    };
});
app.filter('character', function() {
    return function(input) {
        return String.fromCharCode(64 + parseInt(input, 10)).toLowerCase();
    };
});
/*Filter: For pass trusted html and mathjax text render end*/

/*Filter: convert seconds to hours:minutes:seconds start*/
app.filter('duration', function($sce) {
    //Returns duration from secs in hh:mm:ss format.
    return function(secs) {
        var hr = Math.floor(secs / 3600);
        var min = Math.floor((secs - (hr * 3600)) / 60);
        var sec = secs - (hr * 3600) - (min * 60);
        //while (min.length < 2) {min = '0' + min;}
        //while (sec.length < 2) {sec = '0' + min;}
        if (min <= 9) {
            min = '0' + min;
        }
        if (hr <= 9) {
            hr = '0' + hr;
        }
        if (sec <= 9) {
            sec = '0' + sec;
        }
        hr += ':';
        return $sce.trustAsHtml(hr + min + ':' + sec);
    }
});
