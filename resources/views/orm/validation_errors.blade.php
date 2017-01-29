@if($errors->any())
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <p>
        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
        <strong>ERROR DE VALIDACION</strong>
    </p>
    <ul>
        @foreach($errors->all() as $error)
        <li>{!! $error !!}</li>
        @endforeach
    </ul>
</div>
@endif