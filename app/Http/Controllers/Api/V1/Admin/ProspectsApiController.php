<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreProspectRequest;
use App\Http\Requests\UpdateProspectRequest;
use App\Http\Resources\Admin\ProspectResource;
use App\Prospect;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProspectsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('prospect_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProspectResource(Prospect::all());
    }

    public function store(StoreProspectRequest $request)
    {
        $prospect = Prospect::create($request->all());

        if ($request->input('logo', false)) {
            $prospect->addMedia(storage_path('tmp/uploads/' . $request->input('logo')))->toMediaCollection('logo');
        }

        if ($request->input('files', false)) {
            $prospect->addMedia(storage_path('tmp/uploads/' . $request->input('files')))->toMediaCollection('files');
        }

        return (new ProspectResource($prospect))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Prospect $prospect)
    {
        abort_if(Gate::denies('prospect_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProspectResource($prospect);
    }

    public function update(UpdateProspectRequest $request, Prospect $prospect)
    {
        $prospect->update($request->all());

        if ($request->input('logo', false)) {
            if (!$prospect->logo || $request->input('logo') !== $prospect->logo->file_name) {
                $prospect->addMedia(storage_path('tmp/uploads/' . $request->input('logo')))->toMediaCollection('logo');
            }
        } elseif ($prospect->logo) {
            $prospect->logo->delete();
        }

        if ($request->input('files', false)) {
            if (!$prospect->files || $request->input('files') !== $prospect->files->file_name) {
                $prospect->addMedia(storage_path('tmp/uploads/' . $request->input('files')))->toMediaCollection('files');
            }
        } elseif ($prospect->files) {
            $prospect->files->delete();
        }

        return (new ProspectResource($prospect))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Prospect $prospect)
    {
        abort_if(Gate::denies('prospect_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $prospect->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
