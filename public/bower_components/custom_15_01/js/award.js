/**
 * Created by sunny.rana on 17-11-2015.
 */
'use strict';

/**
 * @ngdoc function
 * @name awardCreate.controller:awardCtrl
 * @description
 * # awardCtrl
 * Controller of the awardCreate module
 */

var app = angular.module('awardCreate', ['ngAnimate', 'ngRoute', 'ui.bootstrap', 'flash']);

/*Config: Here we set routing start*/
app.config(function ($modalProvider, $interpolateProvider) {
    $interpolateProvider.startSymbol('[%');
    $interpolateProvider.endSymbol('%]');
    //modal popup turn it off globally here!.
    $modalProvider.options.animation = true;
});
/*Config: Here we set routing End*/

/*Controller: Create award section start*/
app.controller('awardCtrl', function ( $scope, $rootScope, $timeout, $modal, $serverRequest, $sce ) {
 
    /*Drop down default value set here.*/
    $rootScope.contentSetting = {};
    $rootScope.contentSetting = {
        'font':'',
        'size':['10','12','14','16','18','20', '22', '24', '26', '28', '36', '48']
    };
    $scope.awardData = '';
    
    $scope.setAwardValues = function( editAwardObj, metaData ){
        $scope.awardData = editAwardObj;
        $rootScope.contentSetting.font = metaData.awardFontStyle;
        if( $scope.awardData.id ) {
            $rootScope.awardJson = angular.copy( $scope.awardData );
        } else {
            /*Award default JSON value set here.*/
            $rootScope.awardJson = {
                'title' : '',
                'image' : '',
                'status' : 'Active',
                'content' : '',
                'position' : [
                        {'label' : 'Student Name', 'visible' : true, 'font': 'fira-sans-bold-italic', 'size' : '10', 'xPos' : 10, 'yPos' : 10},
                        {'label' : 'Strand', 'visible' : true, 'font': 'fira-sans-bold-italic', 'size' : '10', 'xPos' : 10, 'yPos' : 10},
                        {'label' : 'Sub Strand', 'visible' : true, 'font': 'fira-sans-bold-italic', 'size' : '10', 'xPos' : 10, 'yPos' : 10},
                        {'label' : 'Date', 'visible' : true, 'font': 'fira-sans-bold-italic', 'size' : '10', 'xPos' : 10, 'yPos' : 10},
                        {'label' : 'School', 'visible' : true, 'font': 'fira-sans-bold-italic', 'size' : '10', 'xPos' : 10, 'yPos' : 10},
                        {'label' : 'Signature', 'visible' : true, 'font': 'fira-sans-bold-italic', 'size' : '10', 'xPos' : 10, 'yPos' : 10}
                    ]
            };
        }
    };
    
    
    
    $rootScope.api = {
        'addaward' : '/studentaward',
        'awardimage' : '/studentaward/uploadimage'
         
    };
    
    $rootScope.MASTERS = {};
    $rootScope.MASTERS.keywords = {};
    
    $scope.submitAwardData = function(){
      $serverRequest.postData.saveAwardData( angular.copy( $rootScope.awardJson ) );
    };
    
    $scope.autoimageBind = function(){
        if( $rootScope.awardJson ) {
            if( $rootScope.awardJson.id && $rootScope.awardJson.image ){
                return $sce.trustAsHtml('<img src="/uploads/studentawards/'+$rootScope.awardJson.image+'"/>');
            } else {
                return '';
            }
        }        
        
    };
    
    $scope.bindClick = function(e){
        var data = new FormData();
        var file = typeof $(e)[0].files[0] === 'undefined' ? '' : $(e)[0].files[0];
        data.append('file', file);
        data.append('dimension', [595,842]);
        data.append('selectedImg', $rootScope.awardJson.image);
        $.ajax({
            url: $rootScope.api['awardimage'],
            type: 'POST',
            method : 'POST',
            data: data,
            cache: false,
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            success: function( data, textStatus, jqXHR ){
                $rootScope.awardJson.image = data.filename;
                $scope.$digest();
            }, error: function( jqXHR, textStatus, errorThrown ){
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
                // STOP LOADING SPINNER
            }
        });
    };
    
    $scope.setPosition = function( index ){
          var modalInstanceConfirm = $modal.open({
                templateUrl: 'awardSetPopup',
                controller: 'awardSetPopupCtrl',
                size: 'lg',
                scope: $scope,
                resolve: {
                    positionset: function () {
                        return {
                            'awardJson': $rootScope.awardJson,
                            'positionIndex': index
                        };
                    }
                }
           });

        modalInstanceConfirm.result.then(function () {
            console.log('Modal close at: ' + new Date(), $rootScope.awardJson);
        }, function () {
            console.log('Modal dismissed at: ' + new Date());
        });
    };
    
    $timeout(function(){
       $('#awardrendering').css('display','block'); 
    },100);
    
});
/*Controller: Create award section end*/

app.controller( 'awardSetPopupCtrl', function( $scope, $modalInstance, positionset, $timeout, $sce, $rootScope ){
    $scope.positionIndex = positionset.positionIndex;
    $scope.awardJson = positionset.awardJson;
    $scope.containerwidth = '';
    $scope.containerheight = '';
    
    $scope.done = function () {
        var optionSel = $('#option'+$scope.positionIndex);                
        $rootScope.awardJson.position[$scope.positionIndex].yPos = parseInt( optionSel.css('top'), 10 );
        $rootScope.awardJson.position[$scope.positionIndex].xPos = parseInt( optionSel.css('left'), 10 );
        $modalInstance.close();
    };
    
    $scope.responsiveEle = function(){         
        $(".draggable").draggable({containment: '#quesDrag'+$scope.positionIndex }); 
    };
    
    $scope.imageBind = function(){
        return $sce.trustAsHtml('<img id="image" src="/uploads/studentawards/'+$scope.awardJson.image+'"/>');
        //return $sce.trustAsHtml('<img id="image" src="http://londonce.lan/questionimg/Fs3dzFFVGd_1447748003.jpg"/>');
    };
    
    $timeout(function(){
        $scope.containerwidth = $('#image').width();
        $scope.containerheight = $('#image').height();
        $scope.responsiveEle();
    },1000);
});


/*Service: For post award data start*/
app.service( '$serverRequest', function ( $rootScope, $http ) {
    var $serverRequest = this;
    $serverRequest.postData = {
        
        saveAwardData: function ( json ){
             
            $http ({
                method: 'POST',
                url: $rootScope.api['addaward'],
                data: json,
                headers: {
                    'Content-Type': 'application/json'
                }
            }).success ( function ( data, status, headers, config ) {
                if( data.studentawardReference ){
                    window.location.href = window.location.href.split('studentaward')[0]+'/studentaward ';  
                }
                
            }).error ( function ( data, status, headers, config ) {});
        }
    };
    
});
/*Service: For post award data end*/
