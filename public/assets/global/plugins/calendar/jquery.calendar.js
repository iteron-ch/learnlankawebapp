/*
 *	jQuery FullCalendar Extendable Plugin
 *	An Ajax (PHP - Mysql - jquery) script that extends the functionalities of the fullcalendar plugin
 *  Dependencies: 
 *   - jquery
 *   - jquery Ui
 *   - jquery Fullcalendar
 *   - Twitter Bootstrap
 *  Author: Paulo Regina
 *  Website: www.pauloreg.com
 *  Contributions: Patrik Iden, Jan-Paul Kleemans, Bob Mulder
 *	Version 1.5.8, February - 2014
 *	Released Under Envato Regular or Extended Licenses
 */
 
(function(t,n){t.fn.extend({FullCalendarExt:function(n){var r={calendarSelector:"#calendar",timeFormat:"H:mm - {H:mm}",ajaxJsonFetch:"includes/cal_events.php",ajaxUiUpdate:"includes/cal_update.php",ajaxEventSave:"includes/cal_save.php",ajaxEventQuickSave:"includes/cal_quicksave.php",ajaxEventDelete:"includes/cal_delete.php",ajaxEventEdit:"includes/cal_edit_update.php",ajaxEventExport:"includes/cal_export.php",modalViewSelector:"#cal_viewModal",modalEditSelector:"#cal_editModal",modalQuickSaveSelector:"#cal_quickSaveModal",formAddEventSelector:"form#add_event",formFilterSelector:"form#filter-category select",formSearchSelector:"form#search",formEditEventSelector:"form#edit_event",successAddEventMessage:"Successfully Added Event",successDeleteEventMessage:"Successfully Deleted Event",successUpdateEventMessage:"Successfully Updated Event",failureAddEventMessage:"Failed To Add Event",failureDeleteEventMessage:"Failed To Delete Event",failureUpdateEventMessage:"Failed To Update Event",visitUrl:"Visit Url:",titleText:"Title:",descriptionText:"Description:",categoryText:"Category:",defaultColor:"#587ca3",monthNames:["January","February","March","April","May","June","July","August","September","October","November","December"],monthNamesShort:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],dayNames:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],dayNamesShort:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],today:"today",month:"month",week:"week",day:"day",weekType:"agendaWeek",dayType:"agendaDay",editable:true,disableDragging:false,disableResizing:false,ignoreTimezone:true,lazyFetching:true,filter:true,quickSave:true,firstDay:0,gcal:false,version:"modal",quickSaveCategory:"",savedRedirect:"index.php",removedRedirect:"index.php",updatedRedirect:"index.php"};var n=t.extend(r,n);var i=n;if(i.version=="modal"&&i.gcal==false){t(i.calendarSelector).fullCalendar({timeFormat:i.timeFormat,header:{left:"prev,next",center:"title",right:"month,"+i.weekType+","+i.dayType},monthNames:i.monthNames,monthNamesShort:i.monthNamesShort,dayNames:i.dayNames,dayNamesShort:i.dayNamesShort,buttonText:{today:i.today,month:i.month,week:i.week,day:i.day},editable:i.editable,disableDragging:i.disableDragging,disableResizing:i.disableResizing,ignoreTimezone:i.ignoreTimezone,firstDay:i.firstDay,lazyFetching:i.lazyFetching,selectable:i.quickSave,selectHelper:i.quickSave,select:function(e,n,r){calendar.quickModal(e,n,r);t(i.calendarSelector).fullCalendar("unselect")},eventSources:[{url:i.ajaxJsonFetch,allDayDefault:false}],eventDrop:function(e){t.post(i.ajaxUiUpdate,e,function(e){})},eventResize:function(e){t.post(i.ajaxUiUpdate,e,function(e){})},eventRender:function(e,t){t.attr("data-toggle","modal");t.attr("onclick",'calendar.openModal("'+e.title+'","'+escape(e.description)+'","'+e.url+'","'+e.id+'","'+e.start+'","'+e.end+'");')}});calendar.openModal=function(e,n,r,s,o,u){calendar.title=e;calendar.description=n;calendar.url=r;calendar.id=s;calendar.eventStart=o;calendar.eventEnd=u;if(calendar.url==="undefined"){t(".modal-body").html(unescape(calendar.description))}else{t(".modal-body").html(unescape(calendar.description)+"<br /><br />"+i.visitUrl+' <a href="'+calendar.url+'">'+calendar.url+"</a>")}t(".modal-header").html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>'+"<h4>"+calendar.title+"</h4>");t(i.modalViewSelector).modal("show");t(".modal-footer").delegate('[data-option="edit"]',"click",function(e){t(i.modalViewSelector).modal("hide");if(calendar.url==="undefined"){t(".modal-header").html('<form id="event_title_e"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><label>'+i.titleText+" </label>"+'<input type="text" class="input-block-level" name="title_update" value="'+calendar.title+'"></form>');t(".modal-body").html('<form id="event_description_e"><label>'+i.descriptionText+" </label>"+'<textarea class="input-block-level" name="description_update">'+unescape(calendar.description)+"</textarea></form>")}else{t(".modal-header").html('<form id="event_title_e"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><label>'+i.titleText+" </label>"+'<input type="text" class="input-block-level" name="title_update" value="'+calendar.title+'"></form>');t(".modal-body").html('<form id="event_description_e"><label>'+i.descriptionText+" </label>"+'<textarea class="input-block-level" name="description_update">'+unescape(calendar.description)+"</textarea><label>Url: </label>"+'<input type="text" class="input-block-level" name="url_update" value="'+calendar.url+'"></form>')}t(i.modalEditSelector).modal("show");t(i.modalEditSelector).on("hidden",function(){});t(".modal-footer").delegate('[data-dismiss="modal"]',"click",function(e){e.preventDefault()});e.preventDefault()})};t(".modal-footer").delegate('[data-option="save"]',"click",function(e){var n=t("form#event_title_e").serializeArray();var r=t("form#event_description_e").serializeArray();var i=t("form#event_description_e").serializeArray();calendar.update(calendar.id,n[1],r,i);e.preventDefault()});t(".modal-footer").delegate('[data-option="remove"]',"click",function(e){calendar.remove(calendar.id);e.preventDefault()});t(".modal-footer").delegate('[data-option="export"]',"click",function(e){calendar.exportIcal(calendar.id,calendar.title,calendar.description,calendar.eventStart,calendar.eventEnd,calendar.url);e.preventDefault()});calendar.quickModal=function(n,r,s){t(".modal-header").html('<form id="event_title"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><label>'+i.titleText+" </label>"+'<input type="text" class="input-block-level" name="title" value=""></form>');t(".modal-body").html('<form id="event_description"><label>'+i.descriptionText+" </label>"+'<textarea class="input-block-level" name="description"></textarea>'+i.quickSaveCategory+"</form>");t(i.modalQuickSaveSelector).modal("show");calendar.start=n;calendar.end=r;calendar.allDay=s;t(".modal-footer").delegate('[data-option="quickSave"]',"click",function(e){var n=t("form#event_title").serialize();var r=t("form#event_description").serialize();calendar.quickSave(n,r,calendar.start,calendar.end,calendar.allDay);e.preventDefault()});e.preventDefault()};calendar.quickSave=function(n,r,s,o,u){var a=t.fullCalendar.formatDate(s,"yyyy-MM-dd");var f=t.fullCalendar.formatDate(s,"HH:mm");var l=t.fullCalendar.formatDate(o,"yyyy-MM-dd");var c=t.fullCalendar.formatDate(o,"HH:mm");var h="title="+n+"&description="+r+"&start_date="+a+"&start_time="+f+"&end_date="+l+"&end_time="+c+"&url=false&color="+i.defaultColor+"&allDay="+u;t.post(i.ajaxEventQuickSave,h,function(e){if(e==1||e==""||e==true){t(i.modalQuickSaveSelector).modal("hide");t(i.calendarSelector).fullCalendar("refetchEvents")}else{alert(e)}});e.preventDefault()};calendar.save=function(){t(i.formAddEventSelector).on("submit",function(e){t.post(i.ajaxEventSave,t(this).serialize(),function(e){if(e==1){alert(i.successAddEventMessage);document.location.reload()}else{alert(i.failureAddEventMessage)}});e.preventDefault()})};calendar.remove=function(e){var n="id="+e;t.post(i.ajaxEventDelete,n,function(e){if(e==""){t(i.modalViewSelector).modal("hide");t(i.calendarSelector).fullCalendar("refetchEvents")}else{alert(i.failureDeleteEventMessage)}})};calendar.update=function(e,n,r,s){if(calendar.url==="undefined"){var o="id="+e+"&title="+n.value+"&description="+r[1].value}else{var o="id="+e+"&title="+n.value+"&description="+r[2].value+"&url="+s[3].value}t.post(i.ajaxEventEdit,o,function(e){if(e==""){t(i.modalEditSelector).modal("hide");t(i.calendarSelector).fullCalendar("refetchEvents")}else{alert(i.failureUpdateEventMessage)}})};calendar.exportIcal=function(e,n,r,s,o,u){var a=t.fullCalendar.formatDate(t.fullCalendar.parseDate(s),"yyyy-MM-dd HH:mm:ss");var f=t.fullCalendar.formatDate(t.fullCalendar.parseDate(o),"yyyy-MM-dd HH:mm:ss");var l="method=export&id="+e+"&title="+n+"&description="+r+"&start_date="+a+"&end_date="+f+"&url="+u;t.post(i.ajaxEventExport,l,function(n){t(i.modalViewSelector).modal("hide");window.location="includes/Event-"+e+".ics";var r="id="+e;t.post(i.ajaxEventExport,r,function(){})})}}else{if(i.gcal==true){i.weekType="";i.dayType=""}t(i.calendarSelector).fullCalendar({timeFormat:i.timeFormat,header:{left:"prev,next",center:"title",right:"month,"+i.weekType+","+i.dayType},monthNames:i.monthNames,monthNamesShort:i.monthNamesShort,dayNames:i.dayNames,dayNamesShort:i.dayNamesShort,buttonText:{today:i.today,month:i.month,week:i.week,day:i.day},editable:i.editable,disableDragging:i.disableDragging,disableResizing:i.disableResizing,ignoreTimezone:i.ignoreTimezone,firstDay:i.firstDay,eventSources:[{url:i.ajaxJsonFetch,allDayDefault:false}],eventDrop:function(e){t.post(i.ajaxUiUpdate,e,function(e){})},eventResize:function(e){t.post(i.ajaxUiUpdate,e,function(e){})}});calendar.save=function(){t(i.formAddEventSelector).on("submit",function(e){t.post(i.ajaxEventSave,t(this).serialize(),function(e){if(e==1){alert(i.successAddEventMessage);window.location=i.savedRedirect}else{alert(i.failureAddEventMessage)}});e.preventDefault()})};calendar.remove=function(e){var n="id="+e;t.post(i.ajaxEventDelete,n,function(e){if(e==""){alert(i.successDeleteEventMessage);window.location=i.removedRedirect}else{alert(i.failureDeleteEventMessage)}})};calendar.update=function(e){var n=t(i.formEditEventSelector).serialize()+"&id="+e;t(i.formEditEventSelector).on("submit",function(e){t.post(i.ajaxEventEdit,n,function(e){if(e==""){alert(i.successUpdateEventMessage);window.location=i.updatedRedirect}else{alert(i.failureUpdateEventMessage)}});e.preventDefault()})}}if(i.filter==true){t(i.formFilterSelector).on("change",function(e){selected_value=t(this).val();construct="filter="+selected_value;t.post("includes/loader.php",construct,function(e){t(i.calendarSelector).fullCalendar("refetchEvents")});e.preventDefault()})}if(i.filter==true){t(i.formSearchSelector).keypress(function(e){if(e.which==13){s();e.preventDefault()}});t(i.formSearchSelector+" button").on("click",function(e){s()});function s(){value=t(i.formSearchSelector+" input").val();construct="search="+value;t.post("includes/loader.php",construct,function(e){t(i.calendarSelector).fullCalendar("refetchEvents")})}}}})})(jQuery);var calendar={}