<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function getMessage(Request $request): string|null
    {
        $message = null;
        if ($request->query->has('message')) {
            $message = $request->query->get('message');
        }
        return $message;
    }
}
