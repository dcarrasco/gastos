<div class="row">
    <div class="col-12 text-right bg-white py-2 px-2 border-bottom rounded-top-lg">
        <div class="btn-group" role="group">
            <button id="button-filters" type="button" class="btn btn-light btn-sm dropdown-toggle m-2 border font-weight-bold" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <svg style="fill: #888" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20"><path class="heroicon-ui" d="M2.3 7.7A1 1 0 0 1 2 7V3a1 1 0 0 1 1-1h18a1 1 0 0 1 1 1v4a1 1 0 0 1-.3.7L15 14.42V17a1 1 0 0 1-.3.7l-4 4A1 1 0 0 1 9 21v-6.59l-6.7-6.7zM4 4v2.59l6.7 6.7a1 1 0 0 1 .3.71v4.59l2-2V14a1 1 0 0 1 .3-.7L20 6.58V4H4z"/></svg>

                {{ $resource->countAppliedFilters(request()) ?: '' }}
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
</div>
