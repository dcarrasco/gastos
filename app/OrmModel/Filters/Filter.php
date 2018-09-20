<?php

namespace App\OrmModel\Filters;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

class Filter
{
    protected $parameterPrefix = 'filter_';

    public function apply(Request $request, $query, $value)
    {
        return $query;
    }

    public function options()
    {
        return [];
    }

    public function getName()
    {
        $fullName = explode('\\', get_class($this));
        $name = array_pop($fullName);

        return $name;
    }

    public function getLabel()
    {
        return str_replace('_', ' ', Str::snake($this->getName()));
    }

    public function getOptionUrl(Request $request, $value)
    {
        $parameters = ($this->isSet($request) and $this->getValue($request) == $value)
            ? [$this->getUrlParameter() => '']
            : [$this->getUrlParameter() => $value];

        $urlParameters = array_merge($request->all(), $parameters);

        return $request->fullUrlWithQuery($urlParameters);
    }

    public function getUrlParameter()
    {
        return $this->parameterPrefix.$this->getName();
    }

    public function getUrlMark(Request $request, $value)
    {
        if (is_null($this->getValue($request)))
        {
            return '';
        }

        return $this->getValue($request) == $value
            ? '<span class="fa fa-check"></span>'
            : '';
    }

    public function getValue(Request $request)
    {
        $value = $request->get($this->getUrlParameter());

        if (is_null($value) or $value === '') {
            return null;
        }

        return $value;
    }

    public function isSet(Request $request)
    {
        return $request->has($this->getUrlParameter()) and ! is_null($this->getValue($request));
    }

}
