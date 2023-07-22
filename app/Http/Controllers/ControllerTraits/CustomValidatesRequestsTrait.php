<?php

namespace App\Http\Controllers\ControllerTraits;

use Illuminate\Foundation\Http\FormRequest;

trait CustomValidatesRequestsTrait
{
    /**
     * Sometimes there is a need to lock some DB entities during or before validation to be sure,
     * that nothing changed between validation and update process.
     * This method can be used inside DB transaction and do mostly same as FormRequestServiceProvider do.
     *
     * @param mixed ...$validatableEntities
     */
    protected function validateRequest(string $formRequestClass, ...$validatableEntities): FormRequest
    {
        $request = $formRequestClass::createFrom(request())
            ->setContainer(app())
            ->setRedirector(app('redirect'));

        if (method_exists($request, 'setValidatableEntities')) {
            $request->setValidatableEntities(...$validatableEntities);
        }

        $request->validateResolved();

        return $request;
    }
}
