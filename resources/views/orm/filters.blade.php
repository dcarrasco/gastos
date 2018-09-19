<div class="text-right">
    <div class="btn-group" role="group">
        <button id="button-filters" type="button" class="btn btn-light btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-filter"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="button-filters" style="min-width: 20em;">

            <h6 class="dropdown-header text-uppercase" href="#">{{ $perPageFilter->getLabel() }}</h6>
            @foreach($perPageFilter->options() as $option => $value)
                <a class="dropdown-item" href="{{ $perPageFilter->getOptionUrl(request(), $value) }}">
                    {!! $perPageFilter->getUrlMark(request(), $value) !!}
                    {{ $option }}
                </a>
            @endforeach

            @foreach($resource->filters() as $filter)
                <div class="dropdown-divider"></div>
                <h6 class="dropdown-header text-uppercase" href="#">{{ $filter->getLabel() }}</h6>
                @foreach($filter->options() as $option => $value)
                    <a class="dropdown-item" href="{{ $filter->getOptionUrl(request(), $value) }}">
                        {!! $filter->getUrlMark(request(), $value) !!}
                        {{ $option }}
                    </a>
                @endforeach
            @endforeach

        </div>
    </div>
</div>
