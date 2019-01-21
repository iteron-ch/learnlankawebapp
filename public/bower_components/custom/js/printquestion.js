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
var app = angular.module('printPopUp', ['ngAnimate', 'ngRoute']);
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
            $.each( $($('#presetheight'+quesId).find('.person')), function( index, ele ){
                if( tempheight <   $(ele).height()){
                    tempheight = $(ele).height();
                    $($('#presetheight'+quesId).find('.person')).css('min-height', tempheight);
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
    $scope.initData = function(data) { console.log(data);
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
app.controller('reflectionCtrl', function($scope, $rootScope, $timeout) {
    $scope.init = function(setIndex, questionindex) {
        $scope.setIndex = setIndex;
        $scope.quesIndex = questionindex;
    };

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

app.controller('symmetricCtrl', function($scope, $rootScope, $timeout) {
    $scope.init = function(setIndex, questionindex) {
        $scope.setIndex = setIndex;
        $scope.quesIndex = questionindex;
    };

    $timeout(function() {
        var tempVar = angular.copy($rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex]);
        tempVar.correctAns = [];
        var forDotsjoin = {
            'ques': tempVar,
            'action': 'ques',
            'addevent': false
        };
        var lock = new DotsJoin("#prepatternContainerSymetry_" + $rootScope.MASTERS.questions[$scope.setIndex].id + "_" + $scope.quesIndex, forDotsjoin);
    }, 100);
});
app.controller('joiningdotsCtrl', function($scope, $rootScope, $timeout) {
    $scope.init = function(setIndex, questionindex) {
        $scope.setIndex = setIndex;
        $scope.quesIndex = questionindex;
    };

    $timeout(function() {
        var tempVar = angular.copy($rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex]);
        tempVar.correctAns = [];
        var forDotsjoin = {
            'ques': tempVar
        };
        var lock = new DotsJoin("#prepatternContainer_" + $rootScope.MASTERS.questions[$scope.setIndex].id + "_" + $scope.quesIndex, forDotsjoin);
    }, 100);
});

app.controller('drawlineonimageCtrl', function($scope, $rootScope, $timeout) {
    $scope.init = function(setIndex, questionindex) {
        $scope.setIndex = setIndex;
        $scope.quesIndex = questionindex;
    };
    $scope.preCanvasRender = function() {
        var canvas = new fabric.Canvas('preCanvas_' + $rootScope.MASTERS.questions[$scope.setIndex].id + '_' + $scope.quesIndex, {selection: false});
        var canvas1 = new fabric.Canvas('preCanvasImg_' + $rootScope.MASTERS.questions[$scope.setIndex].id + '_' + $scope.quesIndex);
        var line, isDown, imageUrl;
        imageUrl = "/questionimg/" + $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].imgPath;

        fabric.Image.fromURL(imageUrl, function(oImg) {
            canvas1.add(oImg);
        });

        if ($rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].option.length) {
            angular.forEach($rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].option, function(eoption, ioption) {
                canvas.add(new fabric.Circle({radius: 5, fill: 'red', top: eoption.top, left: eoption.left}));
                canvas.item(ioption).selectable = false;
            });
        }

        canvas.on('mouse:down', function(o) {
            isDown = true;
            var pointer = canvas.getPointer(o.e);
            var points = [pointer.x, pointer.y, pointer.x, pointer.y];
            line = new fabric.Line(points, {
                strokeWidth: 5,
                fill: 'red',
                stroke: 'red',
                originX: 'center',
                originY: 'center'
            });
            canvas.add(line);
        });
        canvas.on('mouse:move', function(o) {
            if (!isDown)
                return;
            var pointer = canvas.getPointer(o.e);
            line.set({x2: pointer.x, y2: pointer.y});
            canvas.renderAll();
        });
        canvas.on('mouse:up', function(o) {
            isDown = false;
        });
    };

    $timeout(function() {
        $scope.preCanvasRender();
    }, 100);
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
            var r = canvas.getBoundingClientRect(),
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
            line.draw();
        }
    };

    $timeout(function() {
        $scope.setCanvasDataPre($rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].imgPath, $scope.canvasWidth, $scope.canvasHeigth);
    });
});



app.controller('matchdragdropCtrl', function($scope, $rootScope, $timeout) {
    var myLines = [], svg = [], myLinescorrectAns = [], svgcorrectAns = [];
    $scope.init = function(setIndex, questionindex) {
        $scope.setIndex = setIndex;
        $scope.quesIndex = questionindex;
        $scope.prefixid = $scope.setIndex + "_" + $scope.quesIndex;
    };

    $scope.initializecorrectAns = function() {
        // set up the drawing area from Body of the document
        console.log("calling function..");
        //myLinescorrectAns[parentIndex] = [];
        $("#correctAnssvgbasics" + $scope.prefixid).css("height", $("#correctAnspnlAllIn" + $scope.prefixid).height()).css("width", $("#correctAnspnlAllIn" + $scope.prefixid).width());

        // all draggable elements
        $("div .draggable").draggable({revert: true, snap: false, cursor: 'crosshair',
            start: function(event, ui) {
                $(this).addClass("custom-drag-ele");
            }, stop: function(event, ui) {
                $(this).removeClass("custom-drag-ele");
            }
        });

        // all droppable elements
        $("div .droppable").droppable({hoverClass: "ui-state-hover", cursor: "move",
            drop: function(event, ui) {
                // disable it so it can"t be used anymore		
                $(this).droppable("disable");
                var pindex = $(this).attr('id').split("_")[2];
                var optionindex = $(this).attr('id').split("_")[1];
                var sourceValue = $(ui.draggable).attr("id").split("_")[1];
                var targetValue = optionindex;
                // change the input element to contain the mapping target and source
                $(ui.draggable).find("input:hidden").val(sourceValue + "_" + targetValue);
                $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].correctAns[pindex][optionindex].source = sourceValue;
                $rootScope.MASTERS.questions[$scope.setIndex].questions[$scope.quesIndex].correctAns[pindex][optionindex].target = targetValue;
                // disable it so it can"t be used anymore	
                $(ui.draggable).draggable("disable");

                svgDrawLinecorrectAns($(this), $(ui.draggable), pindex);
            }
        });
        if ($('#correctAnssvgbasics' + $scope.prefixid).find('svg').length) {
            $('#correctAnssvgbasics' + $scope.prefixid).find('svg').remove();
        }
        Raphael("correctAnssvgbasics" + $scope.prefixid, $("#correctAnssvgbasics" + $scope.prefixid).width(), $("#correctAnssvgbasics" + $scope.prefixid).height());

    };
    $timeout(function() {
        $scope.initializecorrectAns();
    }, 1000);

    function svgDrawLinecorrectAns(eTarget, eSource, parentIndex) {
        // wait 1 sec before draw the lines, so we can get the position of the draggable
        $timeout(function() {
            var $source = eSource;
            var $target = eTarget;
            var originX = ($source.offset().left + $source.width() + 48) - $('#correctAnspnlAllIn' + parentIndex).offset().left;
            var originY = ($source.offset().top + (($source.height() + 4) / 2)) - $('#correctAnspnlAllIn' + parentIndex).offset().top;
            var endingX = ($target.offset().left + 46) - $('#correctAnspnlAllIn' + parentIndex).offset().left;
            var endingY = ($target.offset().top + (($target.height() + 4) / 2)) - $('#correctAnspnlAllIn' + parentIndex).offset().top;

            var space = 20;
            var a = "M" + originX + " " + originY + " L" + (originX + space) + " " + originY; // beginning
            var b = "M" + (originX + space) + " " + originY + " L" + (endingX - space) + " " + endingY; // diagonal line
            var c = "M" + (endingX - space) + " " + endingY + " L" + endingX + " " + endingY; // ending
            var all = a + " " + b + " " + c;
            myLinescorrectAns[parentIndex][myLinescorrectAns.length] = svgcorrectAns[parentIndex].path(all).attr({
                "stroke": 'red',
                "stroke-width": 2,
                "stroke-dasharray": ""
            });

        }, 1000);

    }

});


/*Filter: For pass trusted html and mathjax text render start*/
app.filter('to_trusted', function ($sce) {
    return function (text) {
        MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
        return $sce.trustAsHtml(text);
    };
});
app.filter('character',function(){
    return function(input){
        return String.fromCharCode(64 + parseInt(input,10)).toLowerCase();
    };
});
/*Filter: For pass trusted html and mathjax text render end*/

/*Filter: convert seconds to hours:minutes:seconds start*/
app.filter('duration', function( $sce ) {
    //Returns duration from secs in hh:mm:ss format.
      return function(secs) {
        var hr = Math.floor(secs / 3600);
	var min = Math.floor((secs - (hr * 3600))/60);
	var sec = secs - (hr * 3600) - (min * 60);
	//while (min.length < 2) {min = '0' + min;}
	//while (sec.length < 2) {sec = '0' + min;}
        if( min <= 9 ) {min = '0'+min;}
        if( hr <= 9 ) {hr = '0'+hr;}
        if( sec <= 9 ) {sec = '0'+sec;}        
	hr += ':';
	return $sce.trustAsHtml(hr+min+':'+sec);
    }
});
