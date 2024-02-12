<?php

namespace App\Models;

use App\Models\SalesPackage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function packages()
    {
        return $this->belongsTo(SalesPackage::class, 'sales_package_id');
    }
}
