<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CaseRequest extends Model
{
    use HasFactory;

    protected $table='case_requests';
    protected $guarded = [];
    protected static function boot()
    {
        parent::boot();

        // اعمال مرتب‌سازی پیش‌فرض
        static::addGlobalScope('defaultOrder', function ($builder) {
            $builder->orderByDesc('id');
        });
    }
    public function categories()
    {
        return $this->belongsToMany(CaseCategory::class, 'case_request_category', 'case_request_id', 'case_category_id');
    }
    // public function assignments()
    // {
    //     return $this->hasMany(CaseAssignment::class);
    // }
    public function assignments()
    {
        return $this->hasMany(CaseAssignment::class);
    }
    public function caseCategoryAnimal()
    {
        return $this->belongsTo(CaseCategoryAnimal::class, 'case_category_animal_id');
    }

    public function caseCategoryExpert()
    {
        return $this->belongsTo(CaseCategoryExpert::class, 'case_category_expert_id');
    }
    public function caseRequestFields()
{
    return $this->hasMany(CaseRequestFields::class);
}
public function caseFiles()
{
    return $this->hasMany(CaseFile::class);
}
    public function invoiceItem()
    {
        return $this->hasOne(InvoiceItem::class);
    }
    public function getTotalPrice()
    {
        if($this->caseCategoryExpert->refrence_index > 1){
            $expertPrice = $this->case_category_expert_id ? $this->caseCategoryExpert->price : 0;
            $case_category_id=$this->caseCategoryExpert->case_category_id;
            $CaseCategoryExperts=CaseCategoryExpert::where('case_category_id',$case_category_id)->get();
            $expertPrice = 0;

            foreach ($CaseCategoryExperts as $expert) {
                $expertPrice += $expert->price;
            }
        }
        else{
        $expertPrice = $this->case_category_expert_id ? $this->caseCategoryExpert->price : 0;

        }
        $animalPrice = $this->case_category_animal_id ? $this->caseCategoryAnimal->price : 0;
        $totalPrice = $animalPrice + $expertPrice;

        return $totalPrice;
       

    }


    public function getCommissionPrice()
    {
        $animalCategory = $this->caseCategoryAnimal;
        $animalCommissionPrice = $this->calculateCommission($animalCategory);
        $expertCategory = $this->caseCategoryExpert;



        $time_start = Carbon::parse($this->created_at);

        $time_end = Carbon::now();
        // dd($time_end);

        $difference = $time_end->diffInMinutes($time_start);

        $caseCategoryExpertCommission = CaseCategoryExpertCommission::where('case_expert_id', $expertCategory->id)
            ->where('time_end', '>=', $difference)
            ->first();
            // dd($caseCategoryExpertCommission);
        if($caseCategoryExpertCommission){
            $expertCommissionPrice = $this->calculateCommission($caseCategoryExpertCommission);

        }
        else{
            $expertCommissionPrice = $this->calculateCommission($expertCategory);
        }

        $totalCommissionPrice = $animalCommissionPrice + $expertCommissionPrice;

        return  $totalCommissionPrice;
    }

    private function calculateCommission($type)
    {
        if (!$type) {
            return 0;
        }

        $commissionType = $type->commission_type;

        if ($commissionType === 'percent') {
            $commission = ($type->commission_value / 100) * $type->price;
        } else {
            $commission = $type->commission_value;
        }

        return $commission;
    }

}
