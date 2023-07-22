<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ControllerTraits\ApiTrait;
use App\Http\Controllers\ControllerTraits\CustomValidatesRequestsTrait;
use App\Traits\Paginatable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class ApiController extends BaseController
{
    use ApiTrait;
    use AuthorizesRequests;
    use CustomValidatesRequestsTrait;
    use DispatchesJobs;
    use Paginatable;
    use ValidatesRequests;
}
