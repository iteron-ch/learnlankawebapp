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
app.config(function ($interpolateProvider) {
    $interpolateProvider.startSymbol('[%');
    $interpolateProvider.endSymbol('%]');
});
/*Config: Here we set routing End*/

/*Controller: Print Question section start*/
app.controller('printPopUpCtrl', function ($scope, $rootScope) {


    $rootScope.MASTERS = {};
    /*$rootScope.MASTERS.questions = [{
     "id": 1074,
     "attempt_id": 729,
     "attempstatus": "skipped",
     "questiontype": "2",
     "description": "",
     "descvisible": "False",
     "questions": [{
     "ques": "<p>Which of the following are examples of Standard English?</p>\n",
     "option": [{
     "option": "<p>You are here.</p>\n"
     }, {
     "option": "<p>You is here.</p>\n"
     }, {
     "option": "<p>You were here.</p>\n"
     }, {
     "option": "<p>You was here.</p>\n"
     }],
     "correctmark": [{
     "val": 2,
     "marks": 1
     }],
     "mark": 1,
     "questiontype": "2"
     }],
     "userresponse": [
     [{
     "ischeck": false
     }, {
     "ischeck": false
     }, {
     "ischeck": false
     }, {
     "ischeck": false
     }]
     ],
     "num_question": 1
     }, {
     "id": 1075,
     "attempt_id": 730,
     "attempstatus": "skipped",
     "questiontype": "2",
     "description": "",
     "descvisible": "False",
     "questions": [{
     "ques": "<p>Which of the following are examples of Standard English?</p>\n",
     "option": [{
     "option": "<p>You are here.</p>\n"
     }, {
     "option": "<p>You is here.</p>\n"
     }, {
     "option": "<p>You were here.</p>\n"
     }, {
     "option": "<p>You was here.</p>\n"
     }],
     "correctmark": [{
     "val": 2,
     "marks": 1
     }],
     "mark": 1,
     "questiontype": "2"
     }],
     "userresponse": [
     [{
     "ischeck": false
     }, {
     "ischeck": false
     }, {
     "ischeck": false
     }, {
     "ischeck": false
     }]
     ],
     "num_question": 2
     }, {
     "id": 1076,
     "attempt_id": 731,
     "attempstatus": "skipped",
     "questiontype": "2",
     "description": "",
     "descvisible": "False",
     "questions": [{
     "ques": "<p>Which of the following are examples of Standard English?</p>\n",
     "option": [{
     "option": "<p>I did the washing.</p>\n"
     }, {
     "option": "<p>I done the washing.</p>\n"
     }, {
     "option": "<p>Sarah writ a letter. Which of the following are examples of Standard English? Which of the following are examples of Standard English? Which of the following are examples of Standard English?</p>\n"
     }, {
     "option": "<p>Sarah wrote a letter. Sarah wrote a letter. Sarah wrote a letter. Sarah wrote a letter. Sarah wrote a letter.</p>\n"
     }],
     "correctmark": [{
     "val": 2,
     "marks": 1
     }],
     "mark": 1,
     "questiontype": "2"
     }],
     "userresponse": [
     [{
     "ischeck": false
     }, {
     "ischeck": false
     }, {
     "ischeck": false
     }, {
     "ischeck": false
     }]
     ],
     "num_question": 3
     }];*/
    $rootScope.MASTERS.questions = [{
            "id": 1557,
            "attempt_id": null,
            "attempstatus": "new",
            "questiontype": "1",
            "description": "<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.</p>\n",
            "descvisible": "True",
            "questions": [{
                    "ques": "<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.?</p>\n",
                    "option": [{
                            "option": "<p>Gaurav</p>\n"
                        }, {
                            "option": "<p>Nitesh</p>\n"
                        }, {
                            "option": "<p>Sunny</p>\n"
                        }],
                    "correctmark": [{
                            "val": 1,
                            "marks": 1
                        }],
                    "mark": 1,
                    "questiontype": 1,
                    "correctAns": '0'
                }],
            "num_question": 11
        }, {
            "id": 1545,
            "attempt_id": 804,
            "attempstatus": "skipped",
            "questiontype": "28",
            "description": "",
            "descvisible": "True",
            "questions": [{
                    "ques": "<p>This is new measurement question 1</p>\n",
                    "option": {
                        "value": "33oV4M6x0R_1449584128.png",
                        "protractor": "",
                        "noofoption": 1
                    },
                    "correctmark": [{
                            "val": 1,
                            "marks": 1
                        }],
                    "mark": 1,
                    "questiontype": 28,
                    "correctAns": [{
                            "label": "A", "value": "Tree"
                        }]
                }, {
                    "ques": "<p>What is your company name?</p>\n",
                    "option": {
                        "value": "nA6mLHD9zu_1450347836.png",
                        "protractor": "",
                        "noofoption": 1
                    },
                    "correctmark": [{
                            "val": 1,
                            "marks": 1
                        }],
                    "mark": 1,
                    "questiontype": 28,
                    "correctAns": [{
                            "label": "Name", "value": "ICREON"
                        }]
                }],
            "userresponse": [
                [{"label": "A", "value": ""}],
                [{"label": "B", "value": ""}]
            ],
            "num_question": 1
        }, {
            "id": 1546,
            "attempt_id": 805,
            "attempstatus": "skipped",
            "questiontype": "28",
            "description": "<p>asdfasdf asdf</p>\n",
            "descvisible": "True",
            "questions": [{
                    "ques": "<p>asdfadf asdfasd fasd asdf asdf asdf</p>\n",
                    "option": {
                        "value": "gXo24MSNyS_1450348350.png", "protractor": "", "noofoption": 1
                    },
                    "correctmark": [{"val": 1, "marks": 1}],
                    "mark": 1,
                    "questiontype": 28,
                    "correctAns": [{
                            "label": "Angle", "value": "45"
                        }]
                }],
            "userresponse": [
                [{
                        "label": "Angle",
                        "value": ""
                    }]
            ],
            "num_question": 2
        }, {
            "id": 1076,
            "attempt_id": 731,
            "attempstatus": "skipped",
            "questiontype": "2",
            "description": "",
            "descvisible": "False",
            "questions": [{
                    "ques": "<p>Which of the following are examples of Standard English?</p>\n",
                    "option": [{
                            "option": "<p>I did the washing.</p>\n"
                        }, {
                            "option": "<p>I done the washing.</p>\n"
                        }, {
                            "option": "<p>Sarah writ a letter. Which of the following are examples of Standard English? Which of the following are examples of Standard English? Which of the following are examples of Standard English?</p>\n"
                        }, {
                            "option": "<p>Sarah wrote a letter. Sarah wrote a letter. Sarah wrote a letter. Sarah wrote a letter. Sarah wrote a letter.</p>\n"
                        }],
                    "correctmark": [{
                            "val": 2,
                            "marks": 1
                        }],
                    "mark": 1,
                    "questiontype": "2",
                    "correctAns": [{"ischeck": true}, {"ischeck": false}, {"ischeck": false}, {"ischeck": false}]
                }],
            "userresponse": [
                [{
                        "ischeck": false
                    }, {
                        "ischeck": false
                    }, {
                        "ischeck": false
                    }, {
                        "ischeck": false
                    }]
            ],
            "num_question": 3
        }];
    
    $scope.initData = function( data ){
        $scope.checkMode = data.mode;
    };
    
    $scope.printPage = function (id) {
        window.print();
    };
});
/*Controller: Print Question section end*/

app.filter('to_trusted', function ($sce) {
    return function (text) {
        //   MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
        return $sce.trustAsHtml(text);
    };
});

