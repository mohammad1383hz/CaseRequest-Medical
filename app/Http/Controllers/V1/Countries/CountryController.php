<?php

namespace App\Http\Controllers\V1\Countries;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CountryCollection;
use App\Http\Resources\Admin\CountryResource;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['permission:coupon.index'])->only('index');
        // $this->middleware(['permission:coupon.store'])->only('store');
        // $this->middleware(['permission:coupon.show'])->only('show');
        // $this->middleware(['permission:coupon.update'])->only('update');
        // $this->middleware(['permission:coupon.destroy'])->only('destroy');

    }
   
public function index(Request $request)
{
  
    $countries = Country::all();
    return new CountryCollection($countries);
}
    public function show($country){
        $country=Country::where("calling_code", $country)->first();
        return new CountryResource($country);

    }

  
    }







