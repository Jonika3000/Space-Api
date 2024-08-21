<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Resources\Json\JsonResource;

interface EloquentRepositoryInterface
{
    public function index();
    public function store(FormRequest $request);
    public function show(Model $model): Model;
    public function update(FormRequest $request, Model $model);
    public function destroy(Model $model);
}
