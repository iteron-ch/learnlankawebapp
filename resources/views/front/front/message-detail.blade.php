@extends('front.layout._default')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
    @include('front.front.message-leftbar')
    <div class="right_section">
        <div class="rtop">&nbsp;</div>
        <div class="rmid">
            <div id="content-l" class="msg_content">
                <div class="msgSubject_reply">
                    <div class="msgSubject msg_blue">Hey, dude! Whazzzzup?</div>
                    <div class="msgReply">Reply</div>
                </div>
                <div class="msg_table">
                    <ul class="from">
                        <li class="msg_icon1">
                            <span class="msg_text msg_blue">admin@satscompanion.com</span>
                            <span>Today, 10:45 am</span>
                        </li>
                    </ul>
                </div>
                <div class="msgBody">
                    Hi,<br><br>

                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.<br><br><br>

                    Cheres,<br>
                    Admin
                </div>
                <div class="back_btn">
                    <a href="#">Back</a>
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