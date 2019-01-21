@extends('front.layout._default')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
    @include('front.front.test-leftbar') 
    <div class="right_section">
        <div class="rtop">&nbsp;</div>
        <div class="rmid">
            <div id="content-l" class="mytask_content">
                <div class="myTest">
                    <ul>
                        <li class="mt_list nobg">
                            <div class="accord listActive">
                                <div class="test_icon1">&nbsp;</div>
                                <h4>1. Lorem ipsum dolor sit amet, consectetur adipiscing elit</h4>
                                <p>8 Jul, 2015</p>
                                <div class="percentScored below100">90%</div>
                                <span>&nbsp;</span>
                            </div>

                            <div class="mtContent">
                                <ul>
                                    <li class="mtTabs">
                                        <div class="tabBtn active">Attempt 1</div>
                                        <div class="mtTabsCont active">
                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 1</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 1</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol incompleted">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 1</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">&nbsp;</div>
                                                <div class="aBtn">View Results</div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="mtTabs">
                                        <div class="tabBtn">Attempt 2</div>
                                        <div class="mtTabsCont">
                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 11</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 11</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol incompleted">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 11</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">&nbsp;</div>
                                                <div class="aBtn">View Results</div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="mtTabs">
                                        <div class="tabBtn">Attempt 3</div>
                                        <div class="mtTabsCont">
                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 111</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 111</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol incompleted">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 111</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">&nbsp;</div>
                                                <div class="aBtn">View Results</div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="mt_list ">
                            <div class="accord">
                                <div class="test_icon2">&nbsp;</div>
                                <h4>1. Lorem ipsum dolor sit amet, consectetur adipiscing elit</h4>
                                <p>8 Jul, 2015</p>
                                <div class="percentScored below70">60%</div>
                                <span>&nbsp;</span>
                            </div>

                            <div class="mtContent">
                                <ul>
                                    <li class="mtTabs">
                                        <div class="tabBtn active">Attempt 1</div>
                                        <div class="mtTabsCont active">
                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 1</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 1</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol incompleted">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 1</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">&nbsp;</div>
                                                <div class="aBtn">View Results</div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="mtTabs">
                                        <div class="tabBtn">Attempt 2</div>
                                        <div class="mtTabsCont">
                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 11</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 11</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol incompleted">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 11</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">&nbsp;</div>
                                                <div class="aBtn">View Results</div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="mtTabs">
                                        <div class="tabBtn">Attempt 3</div>
                                        <div class="mtTabsCont">
                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 111</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 111</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol incompleted">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 111</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">&nbsp;</div>
                                                <div class="aBtn">View Results</div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="mt_list ">
                            <div class="accord">
                                <div class="test_icon3">&nbsp;</div>
                                <h4>1. Lorem ipsum dolor sit amet, consectetur adipiscing elit</h4>
                                <p>8 Jul, 2015</p>
                                <div class="percentScored below50">40%</div>
                                <span>&nbsp;</span>
                            </div>

                            <div class="mtContent">
                                <ul>
                                    <li class="mtTabs">
                                        <div class="tabBtn active">Attempt 1</div>
                                        <div class="mtTabsCont active">
                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 1</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 1</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol incompleted">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 1</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">&nbsp;</div>
                                                <div class="aBtn">View Results</div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="mtTabs">
                                        <div class="tabBtn">Attempt 2</div>
                                        <div class="mtTabsCont">
                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 11</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 11</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol incompleted">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 11</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">&nbsp;</div>
                                                <div class="aBtn">View Results</div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="mtTabs">
                                        <div class="tabBtn">Attempt 3</div>
                                        <div class="mtTabsCont">
                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 111</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 111</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol incompleted">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 111</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">&nbsp;</div>
                                                <div class="aBtn">View Results</div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="mt_list ">
                            <div class="accord">
                                <div class="test_icon4">&nbsp;</div>
                                <h4>1. Lorem ipsum dolor sit amet, consectetur adipiscing elit</h4>
                                <p>8 Jul, 2015</p>
                                <div class="percentScored below20">20%</div>
                                <span>&nbsp;</span>
                            </div>

                            <div class="mtContent">
                                <ul>
                                    <li class="mtTabs">
                                        <div class="tabBtn active">Attempt 1</div>
                                        <div class="mtTabsCont active">
                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 1</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 1</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol incompleted">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 1</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">&nbsp;</div>
                                                <div class="aBtn">View Results</div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="mtTabs">
                                        <div class="tabBtn">Attempt 2</div>
                                        <div class="mtTabsCont">
                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 11</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 11</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol incompleted">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 11</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">&nbsp;</div>
                                                <div class="aBtn">View Results</div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="mtTabs">
                                        <div class="tabBtn">Attempt 3</div>
                                        <div class="mtTabsCont">
                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 111</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 111</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol incompleted">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 111</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">&nbsp;</div>
                                                <div class="aBtn">View Results</div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="mt_list ">
                            <div class="accord">
                                <div class="test_iconD">&nbsp;</div>
                                <h4>1. Lorem ipsum dolor sit amet, consectetur adipiscing elit</h4>
                                <p>8 Jul, 2015</p>
                                <div class="percentScored">&nbsp;</div>
                                <span>&nbsp;</span>
                            </div>

                            <div class="mtContent">
                                <ul>
                                    <li class="mtTabs">
                                        <div class="tabBtn active">Attempt 1</div>
                                        <div class="mtTabsCont active">
                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 1</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 1</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol incompleted">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 1</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">&nbsp;</div>
                                                <div class="aBtn">View Results</div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="mtTabs">
                                        <div class="tabBtn">Attempt 2</div>
                                        <div class="mtTabsCont">
                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 11</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 11</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol incompleted">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 11</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">&nbsp;</div>
                                                <div class="aBtn">View Results</div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="mtTabs">
                                        <div class="tabBtn">Attempt 3</div>
                                        <div class="mtTabsCont">
                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 111</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 111</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol incompleted">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 111</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">&nbsp;</div>
                                                <div class="aBtn">View Results</div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="mt_list ">
                            <div class="accord">
                                <div class="test_iconD">&nbsp;</div>
                                <h4>1. Lorem ipsum dolor sit amet, consectetur adipiscing elit</h4>
                                <p>8 Jul, 2015</p>
                                <div class="percentScored">&nbsp;</div>
                                <span>&nbsp;</span>
                            </div>

                            <div class="mtContent">
                                <ul>
                                    <li class="mtTabs">
                                        <div class="tabBtn active">Attempt 1</div>
                                        <div class="mtTabsCont active">
                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 1</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 1</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol incompleted">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 1</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">&nbsp;</div>
                                                <div class="aBtn">View Results</div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="mtTabs">
                                        <div class="tabBtn">Attempt 2</div>
                                        <div class="mtTabsCont">
                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 11</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 11</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol incompleted">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 11</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">&nbsp;</div>
                                                <div class="aBtn">View Results</div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="mtTabs">
                                        <div class="tabBtn">Attempt 3</div>
                                        <div class="mtTabsCont">
                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 111</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 111</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol incompleted">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 111</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">&nbsp;</div>
                                                <div class="aBtn">View Results</div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="mt_list ">
                            <div class="accord">
                                <div class="test_iconD">&nbsp;</div>
                                <h4>1. Lorem ipsum dolor sit amet, consectetur adipiscing elit</h4>
                                <p>8 Jul, 2015</p>
                                <div class="percentScored">&nbsp;</div>
                                <span>&nbsp;</span>
                            </div>

                            <div class="mtContent">
                                <ul>
                                    <li class="mtTabs">
                                        <div class="tabBtn active">Attempt 1</div>
                                        <div class="mtTabsCont active">
                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 1</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 1</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol incompleted">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 1</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">&nbsp;</div>
                                                <div class="aBtn">View Results</div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="mtTabs">
                                        <div class="tabBtn">Attempt 2</div>
                                        <div class="mtTabsCont">
                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 11</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 11</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol incompleted">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 11</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">&nbsp;</div>
                                                <div class="aBtn">View Results</div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="mtTabs">
                                        <div class="tabBtn">Attempt 3</div>
                                        <div class="mtTabsCont">
                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 111</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 111</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol incompleted">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 111</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">&nbsp;</div>
                                                <div class="aBtn">View Results</div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="mt_list ">
                            <div class="accord">
                                <div class="test_iconD">&nbsp;</div>
                                <h4>1. Lorem ipsum dolor sit amet, consectetur adipiscing elit</h4>
                                <p>8 Jul, 2015</p>
                                <div class="percentScored">&nbsp;</div>
                                <span>&nbsp;</span>
                            </div>

                            <div class="mtContent">
                                <ul>
                                    <li class="mtTabs">
                                        <div class="tabBtn active">Attempt 1</div>
                                        <div class="mtTabsCont active">
                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 1</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 1</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol incompleted">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 1</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">&nbsp;</div>
                                                <div class="aBtn">View Results</div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="mtTabs">
                                        <div class="tabBtn">Attempt 2</div>
                                        <div class="mtTabsCont">
                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 11</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 11</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol incompleted">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 11</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">&nbsp;</div>
                                                <div class="aBtn">View Results</div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="mtTabs">
                                        <div class="tabBtn">Attempt 3</div>
                                        <div class="mtTabsCont">
                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 111</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol completed">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 111</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">Completed</div>
                                                <div class="aBtn">View Results</div>
                                            </div>

                                            <div class="attemptCol incompleted">
                                                <img src="images/paper_icon.png" class="paperIcon" />
                                                <div class="paperName">
                                                    <span>Paper 111</span>
                                                    <span>(45 mins)</span>
                                                </div>
                                                <div class="aStatus">&nbsp;</div>
                                                <div class="aBtn">View Results</div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="rbot">&nbsp;</div>
    </div>
    <div class="clear_fix"></div>
</div>
<!-- END PAGE CONTENT-->
@stop
@section('scripts')
<script>

    $(document).ready(function () {
        $(".e1").select2();
        $(".qa_table .qa_progress .qa_pbar").each(function () {
            val = $(this).data("value");
            $(this).animate({
                width: val
            }, 1500);
        });

        $(".myTest .mt_list .accord").click(function () {
            $(".myTest .mt_list .accord").removeClass("listActive");
            $(this).addClass("listActive");
            $(this).parent().addClass("nobg");
            $(".mtContent").hide();
            $(".listActive + .mtContent").show();
        });

        $(".mtContent .mtTabs .tabBtn").click(function () {
            $(".mtContent .mtTabs .tabBtn").removeClass("active");
            $(this).toggleClass("active");
            $(".mtTabsCont").hide();
            $(".tabBtn.active + .mtTabsCont").show();
        });
    });
</script>
@stop 