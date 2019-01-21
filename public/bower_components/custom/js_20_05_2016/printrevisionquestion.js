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



app.controller('drawlineonimageCtrl', function($scope, $rootScope, $timeout) {
    $scope.init = function(setIndex, questionindex) {
        $scope.setIndex = setIndex;
        $scope.quesIndex = questionindex;
    };
   
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
