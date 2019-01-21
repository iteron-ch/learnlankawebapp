function Main(optSelectLevel) {
    var context = this;
    context.successResponse = "";
    context.input = '';
    context.formId = '';
    context.errorMessage = '';
    context.files = '';
    context.dateFormat = '';
    context.zipCodeData = '';
    context.optSelectLevel = optSelectLevel;
    //console.log(context.input);
    this.initEvents();
}
Main.prototype = {
    initEvents: function(fun) {
        var context = this;
    },
    deleteRecord: function(dataParam) {
        var eleObj = dataParam['eleObj'];
        var oTable = dataParam['oTable'];
        var id = eleObj.data('id');
        bootbox.confirm({
            message: dataParam['confirmMsg'],
            callback: function(result) {
                if (result) {
                    $.ajax({
                        url: dataParam['url'],
                        method: 'POST',
                        data: {id: id},
                        beforeSend: function() {

                        },
                        success: function(returnData) {
                            //delete row and reload table
                            oTable
                                    .row(eleObj.parents('tr'))
                                    .remove()
                                    .draw();
                            //show success message
                            Metronic.alert({
                                type: 'success', // alert's type
                                message: dataParam['successMsg'], // alert's message
                            });
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            //other stuff
                        },
                        complete: function() {

                        }
                    });
                }
            }
        });
    },
    showModelIframe: function(eleObj) {
        var frameSrc = eleObj.data('remote');
        var modal_iframe = $("#modal-iframe");
        modal_iframe.on('show.bs.modal', function() {
            $(this).find('.modal-body body').css({
                'background-color': 'white !important'
            });
            $('iframe').attr({src: frameSrc, allowfullscreen: true, webkitallowfullscreen: true, mozallowfullscreen: true});
        });
        modal_iframe.on('hide.bs.modal', function() {
            $('iframe').attr("src", "");
        });
        modal_iframe.modal({show: true})
    },
    jsonDropDown: function(jsonObj, ddObj, selected) {
        var context = this;
        ddObj.html('');
        var opt = '<option value="">' + context.optSelectLevel + '</option>';
        if (jsonObj) {
            jsonDataObj = context.sortJsonData(jsonObj);
            $.each(jsonDataObj, function(key, obj) {
                var optSelected = selected == obj.k ? 'selected="selected"' : '';
                opt += '<option value="' + obj.k + '" ' + optSelected + '>' + obj.v + '</option>';
            });
        }
        ddObj.html(opt).select2();
    },
    makeDropDownJsonData: function(jsonData, ddObj, jsonIndex, selected) {
        var context = this;
        ddObj.html('');
        var jsonObj = jsonData[jsonIndex];
        var opt = '<option value="">' + context.optSelectLevel + '</option>';
        if (jsonObj) {
            jsonDataObj = context.sortJsonData(jsonObj);
            $.each(jsonDataObj, function(key, obj) {
                var optSelected = selected == obj.k ? 'selected="selected"' : '';
                opt += '<option value="' + obj.k + '" ' + optSelected + '>' + obj.v + '</option>';
            });
        }
        ddObj.html(opt).select2();
    },
    makeDropDownJsonDataMultiple: function(jsonData, ddObj, jsonIndex, selected) {
        var context = this;
        ddObj.html('');
        var jsonObj = jsonData[jsonIndex];
        var opt = '';
        if (jsonObj) {
            jsonDataObj = context.sortJsonData(jsonObj);
            $.each(jsonDataObj, function(key, obj) {
                var optSelected = selected == obj.k ? 'selected="selected"' : '';
                opt += '<option value="' + obj.k + '" ' + optSelected + '>' + obj.v + '</option>';
            });
        }
        ddObj.html(opt).select2();
    },
    getQuestionSetOpt: function(questionSets, selected) {
        var context = this;
        var ddObj = $("#question_set");
        var kSt = $("#key_stage").val();
        var yGr = $("#year_group").val();
        var sub = $("#subject").val();
        ddObj.html('');
        var opt = '<option value="">' + context.optSelectLevel + '</option>';
        if (questionSets && kSt != '' && yGr != '' && sub != '') {
            $.each(questionSets, function(key, val) {
                if (questionSets[key]['ks_id'] == kSt && questionSets[key]['year_group'] == yGr && questionSets[key]['subject'] == sub) {
                    var optSelected = selected == questionSets[key]['id'] ? 'selected="selected"' : '';
                    opt += '<option value="' + questionSets[key]['id'] + '" ' + optSelected + '>' + questionSets[key]['set_name'] + '</option>';
                }
            });
        }
        ddObj.html(opt).select2();
    },
    getPaperOpt: function(jsonData, ddObj, jsonIndex, selected) {
        var context = this;
        ddObj.html('');
        var jsonObj = jsonData[jsonIndex];
        var opt = '<option value="">' + context.optSelectLevel + '</option>';
        if (jsonObj) {
            jsonDataObj = context.sortJsonData(jsonObj);
            $.each(jsonDataObj, function(key, val) {
                console.log(val);
                var optSelected = selected == val.k ? 'selected="selected"' : '';
                opt += '<option value="' + val.k + '" ' + optSelected + '>' + val.v.name + '</option>';
            });
        }
        ddObj.html(opt).select2();
    },
    makeOptJsonData: function(jsonData, ddObj, findParam, optionParam, selected) {
        var context = this;
        ddObj.html('');
        var opt = '<option value="">' + context.optSelectLevel + '</option>';
        if (jsonData) {
            $.each(jsonData, function(key, val) {
                if (jsonData[key][findParam.key] == findParam.value) {
                    var optSelected = selected == jsonData[key][optionParam.key] ? 'selected="selected"' : '';
                    opt += '<option value="' + jsonData[key][optionParam.key] + '" ' + optSelected + '>' + jsonData[key][optionParam.value] + '</option>';
                }
            });
        }
        ddObj.html(opt).select2();
    },
    toggleOther: function(elements, otherVal) {
        $.each(elements, function(key, ele) {
            $("#" + ele).change(function() {
                if ($("#other-" + $(this).attr('id')).is(':visible'))
                {
                    $("#other-" + $(this).attr('id')).hide();
                }
                if ($(this).val() == otherVal) {
                    $("#other-" + $(this).attr('id')).show();
                }
            });
        });
    },
    sortJsonData: function(jsonObj) {
        var temp = [];
        $.each(jsonObj, function(key, value) {
            temp.push({v: value, k: key});
        });
        temp.sort(function(a, b) {
            if (a.v > b.v) {
                return 1
            }
            if (a.v < b.v) {
                return -1
            }
            return 0;
        });
        return temp;
    },
    getQuestionSetOptReward: function(questionSets, selected) {
        var context = this;
        var ddObj = $("#question_set");
        var sub = $("#subject").val();
        ddObj.html('');
        var opt = '<option value="">' + context.optSelectLevel + '</option>';
        if (questionSets && sub != '') {
            $.each(questionSets, function(key, val) {
                if (questionSets[key]['subject'] == sub) {
                    var optSelected = selected == questionSets[key]['id'] ? 'selected="selected"' : '';
                    opt += '<option value="' + questionSets[key]['id'] + '" ' + optSelected + '>' + questionSets[key]['set_name'] + '</option>';
                }
            });
        }
        ddObj.html(opt).select2();
    },
    getGroupClassStudentForTask: function(vars) {
        var context = this;
        if (vars.selection != '' && vars.key_stage != '' && vars.year_group != '')
        {
            var url = vars.selection == 'Group' ? vars.urlGroupStudent : vars.urlClassStudent;
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    key_stage: vars.key_stage,
                    year_group: vars.year_group
                },
                success: function(data) {
                    $("#student_container").show();
                    var html = '';
                    $.each(data, function(key, val) {
                        html += '<optgroup label="' + val.name + '">';

                        $.each(val.student, function(childkey, childval) {
                            selected = vars.selectedStudent.indexOf(childkey) != '-1' ? 'selected="selected"' : '';
                            html += '<option value="' + key + '-' + childkey + '" ' + selected + '>' + childval + '</option>';
                        });
                        html += '</optgroup>';
                    });
                    $("#classstudents").html(html).multiselect({
                        autoOpen: false,
                        noneSelectedText: "Select Student",
                        open: function()
                        {
                            $("input[type='search']:first").focus();
                        },
                        click: function(e) {
                            context.multiSelectValidationSlave($(this),$("#classstudents_selected"),$("#taskfrm"));
                        },
                        checkAll: function() {
                            context.multiSelectValidationSlave($(this),$("#classstudents_selected"),$("#taskfrm"));
                        },
                        uncheckAll: function() {
                            context.multiSelectValidationSlave($(this),$("#classstudents_selected"),$("#taskfrm"));
                        },
                    }).multiselectfilter();
                    $("#classstudents").multiselect('refresh');
                }
            });
        }
    },
    multiSelectValidationSlave: function($this,slaveEle,frmObj) {
        var context = this;
        if ($this.multiselect("widget").find("input:checked").length) {
            slaveEle.val(true);
        } else {
            slaveEle.val("");
        }
        frmObj.validate().element(slaveEle);
    }

}


$("#reset").click(function() {
    setTimeout("$('#search-form').submit();", "400");
    setTimeout("$('#search-form select').val('').select2();", "400");
});
$("#city").keyup(function() {
    var min_length = 1;
    var keyword = $('#city').val();
    if (keyword.length >= min_length) {
        $.ajax({
            url: "/getcities",
            type: 'POST',
            data: {keyword: keyword},
            success: function(data) {
                $('#city_list_id').show();
                $('#city_list_id').html(data);
            }
        });
    } else {
        $('#city_list_id').hide();
    }
});
$("#country").on("change", function() {
    if ($('select[name=country]').val() != 'GB') {
        $('#county').val('-1').select2();
    }
    else {
        $('#county').val("").select2();
    }
});
function sansAccent(str) {
    var accent = [
        /[\300-\306]/g, /[\340-\346]/g, // A, a
        /[\310-\313]/g, /[\350-\353]/g, // E, e
        /[\314-\317]/g, /[\354-\357]/g, // I, i
        /[\322-\330]/g, /[\362-\370]/g, // O, o
        /[\331-\334]/g, /[\371-\374]/g, // U, u
        /[\321]/g, /[\361]/g, // N, n
        /[\307]/g, /[\347]/g // C, c
    ];
    var noaccent = ['A', 'a', 'E', 'e', 'I', 'i', 'O', 'o', 'U', 'u', 'N', 'n', 'C', 'c'];
    for (var i = 0; i < accent.length; i++) {
        str = str.replace(accent[i], noaccent[i]);
    }
    return str;
}

function set_city(item) {
    $('#city').val(item);
    $('#city_list_id').hide();
}