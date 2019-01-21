/*
    Reflection.js
    Author: Sunny Rana
*/
;(function ($, window, document, undefined) {
    "use strict";

    var nullFunc = function () {},
        objectHolder = {};

    //internal functions
    function readyDom(iObj) {
        var holder = iObj.holder,
            option = iObj.option,
            matrix = option.matrix,
            margin = option.margin,
            radius = option.radius,
            html = ['<ul class="patt-wrap" style="padding:' + margin + 'px">'];
        for (var i = 0, ln = matrix[0] * matrix[1]; i < ln; i++) {
            html.push('<li class="patt-circ" style="margin:' + margin + 'px; width : ' + (radius * 2) + 'px; height : ' + (radius * 2) + 'px; -webkit-border-radius: ' + radius + 'px; -moz-border-radius: ' + radius + 'px; border-radius: ' + radius + 'px; "><div class="patt-dots"></div></li>');
        }
        html.push('</ul>');
        holder.html(html.join('')).css({
            'width': (matrix[1] * (radius * 2 + margin * 2) + margin * 2) + 'px',
            'height': (matrix[0] * (radius * 2 + margin * 2) + margin * 2) + 'px'
        });

        //select pattern circle
        iObj.pattCircle = iObj.holder.find('.patt-circ');

    }

    //return height and angle for lines
    function getLengthAngle(x1, x2, y1, y2) {
        var xDiff = x2 - x1,
            yDiff = y2 - y1;

        return {
            length: Math.ceil(Math.sqrt(xDiff * xDiff + yDiff * yDiff)),
            angle: Math.round((Math.atan2(yDiff, xDiff) * 180) / Math.PI)
        };
    }

    var startHandler = function (e, obj) {
            e.preventDefault();
            var iObj = objectHolder[obj.token];
			
            if (iObj.disabled) return;

            //check if pattern is visible or not
            if (!iObj.option.patternVisible) {
                iObj.holder.addClass('patt-hidden');
            }

            var touchMove = e.type == "touchstart" ? "touchmove" : "mousemove",
                touchEnd = e.type == "touchstart" ? "touchend" : "mouseup";

            //assign events
            $(this).on(touchMove + '.pattern-move', function (e) {
                moveHandler.call(this, e, obj);
            });
            $(document).one(touchEnd, function () {
                endHandler.call(this, e, obj);
            });
            //set pattern offset
            var wrap = iObj.holder.find('.patt-wrap'),
                offset = wrap.offset();
            iObj.wrapTop = offset.top;
            iObj.wrapLeft = offset.left;

            //reset pattern
            //obj.reset();

        },
        moveHandler = function (e, obj) {
            e.preventDefault();
			//console.log(e.currentTarget.id);
            var x = e.pageX || e.originalEvent.touches[0].pageX,
                y = e.pageY || e.originalEvent.touches[0].pageY,
                iObj = objectHolder[obj.token],
                li = iObj.pattCircle,
                patternAry = iObj.patternAry,
                lineOnMove = iObj.option.lineOnMove,
                posObj = iObj.getIdxFromPoint(x, y),
                idx = posObj.idx,
                pattId = iObj.mapperFunc(idx) || idx;


            if (patternAry.length > 0) {
                var laMove = getLengthAngle(iObj.lineX1, posObj.x, iObj.lineY1, posObj.y);
                iObj.line.css({
                    'width': (laMove.length) + 'px',
                    'transform': 'rotate(' + laMove.angle + 'deg)'
                });
            }

            if (idx) {
            	//console.log( iObj.lastPosObj, patternAry[patternAry.length-1], idx );
                //if (patternAry.indexOf(pattId) == -1) {
                if ( patternAry[patternAry.length-1] != idx ) {
                    var elm = $(li[idx - 1]);

                    //check and mark if any points are in middle of previous point and current point, if it does check them
                    if (iObj.lastPosObj) {
                        var lastPosObj = iObj.lastPosObj,
                            ip = lastPosObj.i,
                            jp = lastPosObj.j,
                            iDiff = Math.abs(posObj.i - ip),
                            jDiff = Math.abs(posObj.j - jp);

                        while (((iDiff == 0 && jDiff > 1) || (jDiff == 0 && iDiff > 1) || (jDiff == iDiff && jDiff > 1)) && !(jp == posObj.j && ip == posObj.i)) {
                            
                                var ipTemp = posObj.i - ip;
                                var jpTemp = posObj.j - jp;
                                if(ipTemp < 0){
                                    ip = iDiff ? Math.max(posObj.i, ip) - 1 : ip;
                                }else{
                                    ip = iDiff ? Math.min(posObj.i, ip) + 1 : ip;
                                }
                                if(jpTemp < 0){
                                    jp = jDiff ? Math.max(posObj.j, jp) - 1 : jp;
                                }else{
                                    jp = jDiff ? Math.min(posObj.j, jp) + 1 : jp;
                                }                            
                            
                            
                            
                            //ip = iDiff ? Math.min(posObj.i, ip) + 1 : ip;
                            //jp = jDiff ? Math.min(posObj.j, jp) + 1 : jp;
                            iDiff = Math.abs(posObj.i - ip);
                            jDiff = Math.abs(posObj.j - jp);

                            var nextIdx = (jp - 1) * iObj.option.matrix[1] + ip,
                                nextPattId = iObj.mapperFunc(nextIdx) || nextIdx;

                            if (patternAry.indexOf(nextPattId) == -1) {
                                $(li[nextIdx - 1]).addClass('hovered');
                                //push pattern on array
                                patternAry.push(nextPattId);
                            }

                        }
                    }

                    //add the current element on pattern
                    elm.addClass('hovered');
                    //push pattern on array
                    patternAry.push(pattId);

                    //add start point for line
                    var margin = iObj.option.margin,
                        radius = iObj.option.radius,
                        newX = ((posObj.i - 1) * (2 * margin + 2 * radius) + 2 * margin + radius + 4) ,
                        newY = ((posObj.j - 1) * (2 * margin + 2 * radius) + 2 * margin + radius + 4) ;
						
						//console.log( newX, newY, posObj.i,  margin, radius);

                    if (patternAry.length != 1) {
                        //to fix line
                        var lA = getLengthAngle(iObj.lineX1, newX, iObj.lineY1, newY);
                        iObj.line.css({
                            'width': (lA.length + 2) + 'px',
                            'transform': 'rotate(' + lA.angle + 'deg)'
                        });

                        if (!lineOnMove) iObj.line.show();
                    }

                    //to create new line
                    var line = $('<div class="patt-lines" style="top:' + (newY - 4) + 'px; left:' + (newX - 4) + 'px"></div>');
                    iObj.line = line;
                    iObj.lineX1 = newX;
                    iObj.lineY1 = newY;
                    //add on dom
                    iObj.holder.append(line);
                    if (!lineOnMove) iObj.line.hide();
                }
                iObj.lastPosObj = posObj;
                //console.log( patternAry );
            }


        },
        endHandler = function (e, obj) {
            e.preventDefault();
            var iObj = objectHolder[obj.token],
                pattern = iObj.patternAry.join('');

            //remove hidden pattern class and remove event
            iObj.holder.off('.pattern-move').removeClass('patt-hidden');

            if (!pattern) return;

            iObj.option.onDraw(pattern);

            //to remove last line
            iObj.line.remove();
            
            if (iObj.rightPattern) {
                if (pattern == iObj.rightPattern) {
                    iObj.onSuccess();
                } else {
                    iObj.onError();
                    obj.error();
                }
            }
            var sendObj = {
                'pattern' : iObj.patternAry,
                'holder' : iObj.holder
            }
            $( window ).trigger( "watch", sendObj );
            
        };

    function InternalMethods() {}

    InternalMethods.prototype = {
        constructor: InternalMethods,
        getIdxFromPoint: function (x, y) {
            var option = this.option,
                matrix = option.matrix,
                xi = x - this.wrapLeft,
                yi = y - this.wrapTop,
                idx = null,
                margin = option.margin,
                plotLn = option.radius * 2 + margin * 2,
                qsntX = Math.ceil(xi / plotLn),
                qsntY = Math.ceil(yi / plotLn),
                remX = xi % plotLn,
                remY = yi % plotLn;

            if (qsntX <= matrix[1] && qsntY <= matrix[0] && remX > margin * 2 && remY > margin * 2) {
                idx = (qsntY - 1) * matrix[1] + qsntX;
            }
            return {
                idx: idx,
                i: qsntX,
                j: qsntY,
                x: xi,
                y: yi
            };
        }
    };

    function Reflection( selector, option ) {
        window.questioncontent = option;
        var self = this,
            token = self.token = Math.random(),
            iObj = objectHolder[token] = new InternalMethods(),
            holder = iObj.holder = $(selector);
        //if holder is not present return
        if (holder.length == 0) return;

        iObj.object = self;
        Reflection.defaults.matrix = [window.questioncontent.ques.option.column, window.questioncontent.ques.option.row];
        Reflection.defaults.margin = window.questioncontent.ques.option.margin;
        Reflection.defaults.radius = window.questioncontent.ques.option.radius;
        
        option = iObj.option = $.extend({}, Reflection.defaults, option);
        readyDom(iObj);

        //add class on holder
        holder.addClass('patt-holder');

        //change offset property of holder if it does not have any property
        if (holder.css('position') == "static") holder.css('position', 'relative');
        
        if( window.questioncontent.addevent ){
            //assign event
            holder.on("touchstart mousedown", function (e) {
                self.allowdraw();
                startHandler.call(this, e, self);
            });
        } else {
            $('#'+holder[0].id).css('background-color', '#eeeeee');
            $('#'+holder[0].id).css('opacity', '0.5');
        }
        

        //handeling callback
        iObj.option.onDraw = option.onDraw || nullFunc;

        //adding a mapper function  
        var mapper = option.mapper;
        if (typeof mapper == "object") {
            iObj.mapperFunc = function (idx) {
                return mapper[idx];
            };
        } else if (typeof mapper == "function") {
            iObj.mapperFunc = mapper;
        } else {
            iObj.mapperFunc = nullFunc;
        }
	//var localArray = [36, 65, 80, 95, 110, 126, 127, 114, 100, 86, 71, 56, 40, 39, 53, 68, 83, 98, 114];
        if( window.questioncontent.action === 'ques' ){
            var tempPattern = window.questioncontent.ques.option.pattern;
            for( var i = 0; i < tempPattern.length; i++ ){
                this.setPattern(tempPattern[i]);
            }
        } else if( window.questioncontent.action === 'ans'  ) { 
            for( var i = 0; i < window.questioncontent.ques.correctAns.length; i++ ){
                this.setPattern(window.questioncontent.ques.correctAns[i]);
                
            }
        } else if( window.questioncontent.action === 'ans_correct'  ) {
            for( var i = 0; i < window.questioncontent.ques.ans_correct.length; i++ ){ console.log(window.questioncontent.ques.ans_correct)
                this.setPattern(window.questioncontent.ques.ans_correct[i]);
                
            }
        }
        //to delete from option object
        iObj.option.mapper = null;
		
    }

    Reflection.prototype = {
        constructor: Reflection,
        //method to set options after initializtion
        option: function (key, val) {
            var iObj = objectHolder[this.token],
                option = iObj.option;
            //for set methods
            if (!val) {
                return option[key];
            }
            //for setter
            else {
                option[key] = val;
                if (key == "margin" || key == "matrix" || key == "radius") {
                    readyDom(iObj);
                }
            }
        },
        //get drawn pattern as string
        getPattern: function () {
            return objectHolder[this.token].patternAry.join('');
        },
        //method to draw a pattern dynamically
        setPattern: function (pattern) {
            //console.log("render set pattern function...");
            var iObj = objectHolder[this.token],
                option = iObj.option,
                matrix = option.matrix,
                margin = option.margin,
                radius = option.radius;

            //allow to set password manually only when enable set pattern option is true
            if (!option.enableSetPattern) return;

            //this.reset();
            this.allowdraw();
            iObj.wrapLeft = 0;
            iObj.wrapTop = 0;

            for (var i = 0; i < pattern.length; i++) {
                var idx = pattern[i] - 1,
                    x = idx % matrix[1],
                    y = Math.floor(idx / matrix[1]),
                    pageX = x * (2 * margin + 2 * radius) + 2 * margin + radius,
                    pageY = y * (2 * margin + 2 * radius) + 2 * margin + radius;

                moveHandler.call(null, {
                    pageX: pageX,
                    pageY: pageY,
                    preventDefault: nullFunc,
                    originalEvent: {
                        touches: [{
                            pageX: pageX,
                            pageY: pageY
                        }]
                    }
                }, this);

            }
        },
        //to temprory enable disable plugin
        enable: function () {
            var iObj = objectHolder[this.token];
            iObj.disabled = false;
        },
        disable: function () {
            var iObj = objectHolder[this.token];
            iObj.disabled = true;
        },
        //reset pattern lock
        reset: function () {
            var iObj = objectHolder[this.token];
            //to remove lines
            iObj.pattCircle.removeClass('hovered');
            iObj.holder.find('.patt-lines').remove();

            //add/reset a array which capture pattern
            iObj.patternAry = [];

            //remove last Obj
            iObj.lastPosObj = null;

            //remove error class if added
            iObj.holder.removeClass('patt-error');

        },
        allowdraw: function () {
            var iObj = objectHolder[this.token];
            //to remove lines
            //iObj.pattCircle.removeClass('hovered');
            //iObj.holder.find('.patt-lines').remove();

            //add/reset a array which capture pattern
            iObj.patternAry = [];

            //remove last Obj
            iObj.lastPosObj = null;

            //remove error class if added
            //iObj.holder.removeClass('patt-error');

        },
        //to display error if pattern is not drawn correct
        error: function () {
            objectHolder[this.token].holder.addClass('patt-error');
        },
        //to check the drawn pattern against given pattern
        checkForPattern: function (pattern, success, error) {
            var iObj = objectHolder[this.token];
            iObj.rightPattern = pattern;
            iObj.onSuccess = success || nullFunc;
            iObj.onError = error || nullFunc;
        }
    };

    Reflection.defaults = {
        matrix: [15, 15],
        margin: 2,
        radius: 25,
        patternVisible: true,
        lineOnMove: true,
        enableSetPattern: true
    };

    window.Reflection = Reflection;
}(jQuery, window, document));