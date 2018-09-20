<div class="text-right bg-white">
    <div class="btn-group" role="group">
        <button id="button-filters" type="button" class="btn btn-light btn-sm dropdown-toggle m-md-2 border" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-filter"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="button-filters" style="min-width: 20em;">

            <h6 class="dropdown-header text-uppercase bg-light" href="#">{{ $perPageFilter->getLabel() }}</h6>
            @foreach($perPageFilter->options() as $option => $value)
                <a class="dropdown-item" href="{{ $perPageFilter->getOptionUrl(request(), $value) }}">
                    {{ $option }}
                    {!! $perPageFilter->getUrlMark(request(), $value) !!}
                </a>
            @endforeach

            @foreach($resource->filters(request()) as $filter)
                <h6 class="dropdown-header text-uppercase bg-light" href="#">{{ $filter->getLabel() }}</h6>
                @foreach($filter->options() as $option => $value)
                    <a class="dropdown-item" href="{{ $filter->getOptionUrl(request(), $value) }}">
                        {{ $option }}
                        {!! $filter->getUrlMark(request(), $value) !!}
                    </a>
                @endforeach
            @endforeach

        </div>
    </div>
</div>
