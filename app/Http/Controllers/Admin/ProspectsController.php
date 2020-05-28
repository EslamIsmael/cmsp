<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyProspectRequest;
use App\Http\Requests\StoreProspectRequest;
use App\Http\Requests\UpdateProspectRequest;
use App\Prospect;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ProspectsController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('prospect_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Prospect::query()->select(sprintf('%s.*', (new Prospect)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'prospect_show';
                $editGate      = 'prospect_edit';
                $deleteGate    = 'prospect_delete';
                $crudRoutePart = 'prospects';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->editColumn('files', function ($row) {
                if (!$row->files) {
                    return '';
                }

                $links = [];

                foreach ($row->files as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });

            $table->rawColumns(['actions', 'placeholder', 'files']);

            return $table->make(true);
        }

        return view('admin.prospects.index');
    }

    public function create()
    {
        abort_if(Gate::denies('prospect_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.prospects.create');
    }

    public function store(StoreProspectRequest $request)
    {
        $prospect = Prospect::create($request->all());

        if ($request->input('logo', false)) {
            $prospect->addMedia(storage_path('tmp/uploads/' . $request->input('logo')))->toMediaCollection('logo');
        }

        foreach ($request->input('files', []) as $file) {
            $prospect->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('files');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $prospect->id]);
        }

        return redirect()->route('admin.prospects.index');
    }

    public function edit(Prospect $prospect)
    {
        abort_if(Gate::denies('prospect_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.prospects.edit', compact('prospect'));
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

        if (count($prospect->files) > 0) {
            foreach ($prospect->files as $media) {
                if (!in_array($media->file_name, $request->input('files', []))) {
                    $media->delete();
                }
            }
        }

        $media = $prospect->files->pluck('file_name')->toArray();

        foreach ($request->input('files', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $prospect->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('files');
            }
        }

        return redirect()->route('admin.prospects.index');
    }

    public function show(Prospect $prospect)
    {
        abort_if(Gate::denies('prospect_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.prospects.show', compact('prospect'));
    }

    public function destroy(Prospect $prospect)
    {
        abort_if(Gate::denies('prospect_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $prospect->delete();

        return back();
    }

    public function massDestroy(MassDestroyProspectRequest $request)
    {
        Prospect::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('prospect_create') && Gate::denies('prospect_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Prospect();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
