<?php

namespace Noisim\Support\Classes;

use Illuminate\Http\Request;

abstract class Validator
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @param Request $request
     * @return array
     */
    public abstract function rules(Request $request);

    /**
     * Get the error messages for the defined validation rules.
     *
     * @param Request $request
     * @return array
     */
    public function messages(Request $request)
    {
        return [];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [];
    }
}