@if(Auth::check())
    <?php
    $count = Auth::user()->newMessagesCount();
    if($count){?>
        <span class="badge badge-danger">{!! $count !!}</span>
    <?php }
    ?>
@endif
