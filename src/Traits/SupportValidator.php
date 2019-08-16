<?php

namespace Noisim\Support\Traits;


use Illuminate\Http\Request;

trait SupportValidator
{
    /**
     * Run validation for a custom validator class.
     *
     * @param string $validatorClass
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function runValidator(string $validatorClass, Request $request)
    {
        $object = new $validatorClass();

        $validator = $this->getValidationFactory()->make($request->all(), $object->rules($request), $object->messages($request), $object->attributes());

        if (method_exists($object, 'withValidator')) {
            $object->withValidator($validator);
        }

        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }
    }
}