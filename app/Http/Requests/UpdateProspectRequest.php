<?php

namespace App\Http\Requests;

use App\Prospect;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateProspectRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('prospect_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email,' . $this->prospect->user->id]
        ];
    }
}
