<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreRespondenRequest;
use App\Http\Requests\UpdateRespondenRequest;
use App\Http\Resources\Admin\RespondenResource;
use App\Models\Responden;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RespondenApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('responden_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RespondenResource(Responden::all());
    }

    public function store(StoreRespondenRequest $request)
    {
        $responden = Responden::create($request->all());

        return (new RespondenResource($responden))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Responden $responden)
    {
        abort_if(Gate::denies('responden_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RespondenResource($responden);
    }

    public function update(UpdateRespondenRequest $request, Responden $responden)
    {
        $responden->update($request->all());

        return (new RespondenResource($responden))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Responden $responden)
    {
        abort_if(Gate::denies('responden_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $responden->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
