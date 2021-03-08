<?php

namespace CodeShopping\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductPhotoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return !$this->route('photo')? $this->createRules() : $this->updateRules();
    }

    private function createRules()
    {
        return [
//            'photos' => ['required', 'array'],
//            'photos.*' => ['required', 'image', 'max:". (3 * 1024)],
        ];
    }

    private function updateRules()
    {
        return [
//            'newPhoto' => ['required', 'image', 'max:". (3 * 1024)],
//            'photos.*' => ['required', 'image']
        ];
    }
}
