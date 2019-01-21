function Test(paperAttempts, subjectPaper) {
    var context = this;
    context.paperAttempts = paperAttempts;
    context.subjectPaper = subjectPaper;
    this.initEvents();
}
Test.prototype = {
    initEvents: function(fun) {
        var context = this;
    },
    testAccordion: function() {
        $(".myTest .listTest .accord").click(function() {
            $(".myTest .listTest .accord").removeClass("listActive");
            $(this).addClass("listActive");
            $(this).parent().addClass("nobg");
            $(".mtContent").hide();
            $(".listActive + .mtContent").show();
        });
    },
    testTab: function() {
        $(".mtContent").on('click', '.tabBtn', function(e) {
            if ($(this).data('inactive')) {
                return false;
            }
            $(e.delegateTarget).find('.tabBtn').removeClass("active");
            $(this).toggleClass("active");
            $(".mtTabsCont").hide();
            $(".tabBtn.active + .mtTabsCont").show();
        });
    },
    testPaperData: function(vars) {
        var context = this;
        var subjectPaper = context.subjectPaper
        $(".listTest").on('click', '.test_list', function(e) {
            e.preventDefault();
            var task = $(this).data('task');
            console.log(task);
            if ($("#paperContent-" + task['id']).is(':empty')) {console.log(task);
                var selectedSubjectPaper = task['subject'] == vars['english'] ? subjectPaper.English : subjectPaper.Math;
                if (task.student_test_attempt_id) {
                    $.ajax({
                        url: vars['ajaxUrlPaper'] + task['encstudent_test_attempt_id'],
                        method: 'GET',
                        beforeSend: function() {

                        },
                        success: function(response) {
                            context.displaySubjectPaper(selectedSubjectPaper, task, response, vars);
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            //other stuff
                        },
                        complete: function() {

                        }
                    });
                } else {
                    //show default
                    task.url = vars['urlAttemptPaper'] + task['encId'],
                    context.displaySubjectPaperDefault(selectedSubjectPaper, task);
                }
            }
        })
    },
    displaySubjectPaperDefault: function(selectedSubjectPaper, elementData) {
        var context = this;
        var paperAttempts = context.paperAttempts;
        var html = '';
        $.each(paperAttempts, function(key, val) {
            html += '<li class="mtTabs">';
            if (key == 1) {
                html += '<div class="tabBtn active">' + val + '</div>';
                html += '<div class="mtTabsCont active">';
                var k = 1;
                $.each(selectedSubjectPaper, function(key_1, val_1) {
                    if (k == 1) {
                        var clspost = ''; 
                    }else{
                        var clspost = 'noattempt';
                    }
                    html += '<div class="attemptCol incompleted '+clspost+'">';
                    html += '<img src="../images/paper_icon.png" class="paperIcon" />';
                    html += '<div class="paperName"><span>' + val_1.name + '</span><span>(' + (val_1.time/60) + ' mins)</span></div>';
                    html += '<div class="aStatus">&nbsp;</div>';
                    if (k == 1) {
                        html += '<div class="aBtn"><a href="' + elementData['url'] + '">Start</a></div>';
                    }else{
                        html += '<div class="aBtn">Start</div>';
                    }
                    html += '</div>';
                    k++;
                });
                html += '</div>';
            } else {
                html += '<div class="tabBtn" data-inactive="true">' + val + '</div>';
            }
            html += '</li>';
        });
        $("#paperContent-" + elementData['id']).html(html);
    },
    displaySubjectPaper: function(selectedSubjectPaper, elementData, attemptStatus, vars) {
        var context = this;
        var paperAttempts = context.paperAttempts;
        var html = '';
        $.each(paperAttempts, function(key, val) {
            var isActive = key == 1 ? 'active' : '';
            var istabActive = parseInt(attemptStatus[key]['tabactive']) == 1 ? '' : 'data-inactive="true"';
            html += '<li class="mtTabs">';
            html += '<div class="tabBtn ' + isActive + '" ' + istabActive + '>' + val + '</div>';
            html += '<div class="mtTabsCont ' + isActive + '">';
            html += context.renderSubjectPaper(selectedSubjectPaper, attemptStatus[key]['papers'], vars);
            html += '</div>';
            html += '</li>';
        });
        $("#paperContent-" + elementData['id']).html(html);
    },
    renderSubjectPaper: function(selectedSubjectPaper, paperAttemptStatus, vars) {
        var context = this;
        var html = '';
        $.each(selectedSubjectPaper, function(key, val) {
            var statusCls = '';
            var statusLavel = '';
            var linkLavel = '';
            var attemptLink = '';
            var linkLavelCls = 'aBtn';
            if (parseInt(paperAttemptStatus[key].status) == '3') {
                statusCls = 'completed';
                statusLavel = 'Completed';
                linkLavel = 'View Results';
                attemptLink =  vars['urlAttemptResult'] + paperAttemptStatus[key].id;
            } else {
                statusLavel = '&nbsp;';
                if (paperAttemptStatus[key].clickable) {
                    if (parseInt(paperAttemptStatus[key].status) == '1') {
                        statusLavel = 'In Progress';
                    }
                    else {
                        statusLavel = '&nbsp;';
                    }
                     statusCls = 'incompleted';
                    linkLavel = 'Start';
                    attemptLink = vars['urlAttemptAttempt'] + paperAttemptStatus[key].id;
                }else{
                    statusLavel = '&nbsp;';
                    statusCls = 'incompleted noattempt';
                    linkLavelCls = 'aBtn';
                    linkLavel = 'Start';
                    attemptLink = '';
                }
            }
            html += '<div class="attemptCol ' + statusCls + '">';
            html += '<img src="../images/paper_icon.png" class="paperIcon" />';
            html += '<div class="paperName"><span>' + val.name + '</span><span>(' + (val.time/60) + ' mins)</span></div>';
            html += '<div class="aStatus">' + statusLavel + '</div>';
            if(attemptLink != ''){
                html += '<div class="'+linkLavelCls+'"><a href="' + attemptLink + '">' + linkLavel + '</a></div>';
            }else{
                html += '<div class="'+linkLavelCls+'">' + linkLavel + '</div>';
            }
            html += '</div>';
        });
        return html;
    }
}