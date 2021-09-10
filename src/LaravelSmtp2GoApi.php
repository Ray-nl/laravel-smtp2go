<?php

namespace RayNl\LaravelSmtp2go;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RayNl\LaravelSmtp2go\Exceptions\LaravelSmtp2GoFilterNotAvailableException;
use RayNl\LaravelSmtp2go\Exceptions\LaravelSmtp2GoNoApiKeyException;
use RayNl\LaravelSmtp2go\Exceptions\LaravelSmtp2GoNoNextRecordsException;

class LaravelSmtp2GoApi
{
    protected array $request = [];

    protected array $search = [];

    protected string $sort = 'email_ts';

    protected string $sort_dir = 'asc';

    protected ?Collection $content = null;

    protected $searchFilters = [];
    protected $filters = [];

    public function hasNext() : bool
    {
        if ($this->content) {
            return Arr::get($this->content, 'data.continue_token') !== null;
        }
        return false;
    }

    public function next()
    {
        if (Arr::get($this->content, 'data.continue_token')) {
            $this->request['continue_token'] = Arr::get($this->content, 'data.continue_token');

            return $this->get();
        }
        return false;
    }

    public function getContent() : Collection
    {
        return $this->content;
    }

    public function sort($column, $direction = 'desc')
    {
        $this->sort = $column;
        $this->sort_dir = $direction;

        return $this;
    }

    public function limit($limit)
    {
        $this->request['limit'] = $limit;

        return $this;
    }

    protected function post($path)
    {
        $path = Str::startsWith($path, '/') && Str::endsWith(config('smtp2go.endpoint'), '/') ? substr($path, 1) : $path; // Remove starting slash if endpoint has trailing slash

        $response = Http::post(config('smtp2go.endpoint') . $path, $this->getRequestData());

        if ($response->ok()) {
            $this->content = new Collection($response->json());

            return $this;
        } else {
            throw $response->toException();
        }
    }

    private function getRequestData() : array
    {
        if (empty(config('smtp2go.api_key'))) {
            throw new LaravelSmtp2GoNoApiKeyException();
        }
        $this->request['api_key'] = config('smtp2go.api_key');
        $this->request['filter_query'] = implode(' AND ', $this->search);
        $this->request['sort'] = $this->sort;
        $this->request['sort_dir'] = $this->sort_dir;

        return $this->request;
    }

    public function where($field, $value) : LaravelSmtp2GoApi
    {
        if (in_array($field, $this->filters)) {
            $this->request[$field] = $value;
        } elseif (in_array($field, $this->searchFilters)) {
            $this->search[] = $field.':\''.$value.'\'';
        } else {
            throw new LaravelSmtp2GoFilterNotAvailableException($field);
        }

        return $this;
    }

}
