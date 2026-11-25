<?php

namespace App\Http\Controllers;

use App\Http\Requests\Select2Request;

class Select2Controller extends Controller
{
    public function index(Select2Request $request)
    {
        $modelClass = config('select2.models.'.$request->validated('model'));

        return $modelClass::getSelectList($request->validated('search'), $request->context());
    }
}
