@if($errors->any())
<div class="alert alert-danger style="display:show">
    @foreach($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
</div>
@endif