<div class="modal fade" id="modalBorrar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h5 class="modal-title" id="exampleModalCenterTitle">
                Borrar {{ $resource->getLabel() }}
            </h5>
            <p>
                {!! trans('orm.delete_confirm', [
                    'model' => $resource->getLabel(),
                    'item' => $resource->title()
                ]) !!}
            </p>
        </div>

        <div class="modal-footer bg-light">
            <button type="button" class="btn btn-light border text-shadow" data-dismiss="modal">
                {{ trans('orm.button_cancel') }}
            </button>
            {!! Form::open(['url' => route($routeName.'.destroy', [$resource->getName(), $modelId]), 'id' => 'formDelete']) !!}
            {!! method_field('DELETE')!!}
            <button type="submit" class="btn btn-danger text-shadow" name="borrar" value="borrar">
                <span class="fa fa-trash-o"></span>
                {{ trans('orm.button_delete') }}
            </button>
            {!! Form::close() !!}
        </div>
    </div>
    </div>
</div>
