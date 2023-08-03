<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customer\CustomerCreateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Show the profile for a given user.
     */
    public function create(CustomerCreateRequest $request)
    {

        dd($request);
    }
}
