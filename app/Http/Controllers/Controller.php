<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @method void authorizeResource(string|array $model, string|array|null $parameter = null, array $options = [], \Illuminate\Http\Request|null $request = null)
 * @method \Illuminate\Auth\Access\Response authorize(mixed $ability, mixed $arguments = [])
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests;
}
