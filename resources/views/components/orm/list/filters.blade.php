<div class="row">
    <div class="col-12 text-right bg-white py-1 px-2 border-bottom rounded-top-lg">
        <div class="btn-group" role="group">
            <button id="button-filters" type="button" class="btn btn-light btn-sm dropdown-toggle my-1 mx-2 border font-weight-bold" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <x-heroicon.filter />
                {{ $resource->countAppliedFilters(request()) ?: '' }}
            </button>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="button-filters" style="min-width: 20em;">
                @foreach(array_merge([$perPageFilter], $resource->filters(request())) as $filter)
                    <h6 class="dropdown-header text-uppercase bg-light" href="#">{{ $filter->getLabel() }}</h6>
                    @foreach($filter->options() as $option => $value)
                        <a class="dropdown-item" href="{{ $filter->getOptionUrl(request(), $value) }}">
                            {{ $option }}
                            {{ $filter->getUrlMark(request(), $value) }}
                        </a>
                    @endforeach
                @endforeach
            </div>

        </div>
    </div>
</div>
