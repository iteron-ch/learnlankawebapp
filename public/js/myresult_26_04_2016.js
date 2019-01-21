function Myresult() {
    var context = this;
}
Myresult.prototype = {
    initEvents: function(fun) {
        var context = this;
    },
    testAttemptResult: function(vars, selectedVal) {
        var context = this;
        $.ajax({
            url: vars['urlTestAttemptResult'] + selectedVal,
            method: 'GET',
            beforeSend: function() {

            },
            success: function(response) {
                $("#container_attempt_list").html(response);
            },
            error: function(xhr, textStatus, errorThrown) {
                //other stuff
            },
            complete: function() {

            }
        });
    },
    revisionAttemptResult: function(vars, selectedVal) {
        var context = this;
        $.ajax({
            url: vars['urlRevisionAttemptResult'] + selectedVal,
            method: 'GET',
            beforeSend: function() {

            },
            success: function(response) {
                $("#container_attempt_list").html(response);
            },
            error: function(xhr, textStatus, errorThrown) {
                //other stuff
            },
            complete: function() {

            }
        });
    },
    taskProgressChart: function(dom,dataPoints,title) {
        var options = {
            animationEnabled: true,
            data: [
                {
                    type: "line", //change it to spline, line, area, bar, pie, etc
                    dataPoints: [{y: 0}]
                }
            ],
             axisX: {
                labelFontSize: 14,
                minimum: 1,
                interval: 2,
                title: "Tests",
            },
            axisY: {
                labelFontSize: 14,
                maximum: 100,
                interval: 20,
                gridThickness: 0,
                suffix: "%",
                title: "Percentage",
                lineThickness:0,
                tickThickness:0,
            }
        };
        $.each(dataPoints, function(key, ele) {
            options.data[0].dataPoints.push({'y': parseFloat(ele)});
        });
        dom.CanvasJSChart(options);
    }
}