@if(Auth::check())
    <?php
    $count = Auth::user()->newMessagesCount();
    if($count){?>
        <span>{!! $count !!}</span>
    <?php }
    ?>
@endif
