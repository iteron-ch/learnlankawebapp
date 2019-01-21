@extends('front.layout._default')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
    @include('front.front.revision-leftbar') 
    <div class="right_section">
        <div class="rtop">&nbsp;</div>
        <div class="rmid">
            <div class="revisionEng_zone">
                <div class="revisionEng_topic_bg">
                    <div class="colMain"><a href="#">English</a></div>
                    <div class="colSpell">
                        <a href="/revision-subject">
                            <img src="images/spelling_icon.png" />
                            <span>Spelling</span>
                        </a>
                    </div>
                    <div class="colGrammer">
                        <a href="/revision-subject">
                            <img src="images/grammer_icon.png" />
                            <span>Grammatical terms</span>
                        </a>
                    </div>
                    <div class="colFunction">
                        <a href="/revision-subject">
                            <img src="images/function_sent_icon.png" />
                            <span>Functions of sentences</span>
                        </a>
                    </div>
                    <div class="colLink">
                        <a href="/revision-subject">
                            <img src="images/link_icon.png" />
                            <span>Combining<br>words, phrases<br>and clauses</span>
                        </a>
                    </div>
                    <div class="colVerb">
                        <a href="/revision-subject">
                            <img src="images/verb_icon.png" />
                            <span>Verb forms, tense<br>and consistency</span>
                        </a>
                    </div>
                    <div class="colPunctuation">
                        <a href="/revision-subject">
                            <img src="images/punctuation_icon.png" />
                            <span>Punctuation</span>
                        </a>
                    </div>
                    <div class="colVocab">
                        <a href="/revision-subject">
                            <img src="images/vocab_icon.png" />
                            <span>Vocabulary</span>
                        </a>
                    </div>
                    <div class="colStandEng">
                        <a href="/revision-subject">
                            <img src="images/stand_eng_icon.png" />
                            <span>Standard English <br>and formality</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="rbot">&nbsp;</div>
    </div>
    <div class="clear_fix"></div>
</div>

@stop