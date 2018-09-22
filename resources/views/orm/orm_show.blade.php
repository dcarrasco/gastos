@extends('common.app_layout')

@section('modulo')
<div class="row mt-md-4">
    <div class="col-md-10 my-md-2">
        <h4>
            {{ trans('orm.title_show') }}
            {!! $resource->getLabel() !!}
        </h4>
    </div>
    <div class="col-md-2 my-md-2 text-right">
        <a href="" class="btn btn-light border py-md-1" data-toggle="modal" data-target="#modalBorrar">
            <span class="fa fa-trash"></span>
        </a>
        <a href="{{ route($routeName.'.edit', [$resource->getName(), $resource->model()->getKey()]) }}" class="btn btn-primary border py-md-1">
            <span class="fa fa-edit"></span>
        </a>
    </div>
</div>

<div class="row">
<div class="col-md-12 my-md-2">
    <table class="table">
        <tbody>
        @foreach($resource->detailFields(request()) as $field)
            <tr>
                <td>
                    <div class="row">
                        <div class="col-md-3 pl-md-5 text-muted">
                            {{ $field->getName() }}
                        </div>
                        <div class="col-md-9">
                            {!! $field->getValue(request(), $resource->model()) !!}
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

</div> <!-- DIV   class="row" -->

@include('orm.orm_modal_delete')

@endsection
