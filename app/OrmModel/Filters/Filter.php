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
        if ($this->isSet($request) and $this->getUrlValue($request) == $value) {
            $parameters = [$this->getUrlParameter() => ''];
        }
        else {
            $parameters = [$this->getUrlParameter() => $value];
        }

        $urlParameters = array_merge($request->all(), $parameters);

        return $request->fullUrlWithQuery($urlParameters);
    }

    public function getUrlParameter()
    {
        return $this->parameterPrefix.$this->getName();
    }

    public function getUrlMark(Request $request, $value)
    {
        if (is_null($this->getUrlValue($request)))
        {
            return '';
        }

        return $this->getUrlValue($request) == $value
            ? '<span class="fa fa-check"></span>'
            : '';
    }

    public function getUrlValue(Request $request)
    {
        if (is_null($request->get($this->getUrlParameter())) or $request->get($this->getUrlParameter()) === '') {
            return null;
        }

        return $request->get($this->getUrlParameter());
    }

    public function isSet(Request $request)
    {
        return $request->has($this->getUrlParameter()) and ! is_null($this->getUrlValue($request));
    }

}
