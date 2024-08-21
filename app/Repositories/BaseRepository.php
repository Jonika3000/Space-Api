<?php

namespace App\Repositories;
use App\Interfaces\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class BaseRepository implements EloquentRepositoryInterface
{
    protected $model;

    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function index()
    {
       return $this->model::all();
    }

    public function store(FormRequest $request): Model {
        $validatedData = $request->validated();
        return $this->model::create($validatedData);
    }

    public function show(Model $model): Model
    {
        return $model;
    }

    public function update(FormRequest $request, Model $model)
    {
        $model->update($request->validated());
    }

    public function destroy(Model $model)
    {
        $model->delete();
    }

}
