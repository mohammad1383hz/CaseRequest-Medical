<?php

namespace App\Http\Controllers\V1\Admin\Currency;
use App\Http\Controllers\Controller;

use App\Http\Resources\Admin\CouponCollection;
use App\Http\Resources\Admin\CouponResource;

use App\Http\Resources\Panel\CurrencyCollection;
use App\Http\Resources\Panel\CurrencyResource;
use App\Models\Currency;
use App\Models\FinancialAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CurrencyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:currency.index'])->only('index');
        $this->middleware(['permission:currency.store'])->only('store');
        $this->middleware(['permission:currency.show'])->only('show');
        $this->middleware(['permission:currency.update'])->only('update');
        $this->middleware(['permission:currency.destroy'])->only('destroy');

    }
    public function index(Request $request)
    {
        $sortBy = $request->query('sort_by', 'id'); // Default to sorting by 'id' if not specified
        $currency = Currency::orderBy($sortBy, 'desc')->get();
        return new CurrencyCollection($currency);
    }
    public function show(Currency $currency){
        return new CurrencyResource($currency);

    }

    public function store(Request $request){
        try {
            $validated = $request->validate([
                'symbol'=> 'required',
                'name'=> 'required',
               
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $currency=Currency::create([

            'symbol'=>$request['symbol'],
            'name'=>$request['name'],

        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$currency]], 200);
    }

    public function update(Request $request,Currency $currency){
        try {
            $validated = $request->validate([
                'symbol'=> 'required',
                'name'=> 'required',
               
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $currency->update([
            'symbol'=>$request['symbol'],
            'name'=>$request['name'],
        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$currency]], 200);
    }
    public function destroy(Currency $currency){
        $currency->delete();
        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);

    }
    }







