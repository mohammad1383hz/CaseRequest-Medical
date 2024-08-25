<?php

namespace App\Http\Controllers\V1\Panel\Currency;
use App\Http\Controllers\Controller;
use App\Http\Resources\Panel\CurrencyCollection;
use App\Http\Resources\Panel\UserResource;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class CurrencyController extends Controller
{


    
public function index(Request $request)
{
    $perPage = $request->query('per_page', 10); // Default to 10 items per page if not specified
    $currency = Currency::paginate($perPage);
    return new CurrencyCollection($currency);
}

    }







