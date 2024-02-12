<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\SalesPackageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $packagesName = optional($this->packages)->price;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'photo_ktp' => $this->photo_ktp,
            'photo_rumah' => $this->photo_rumah,
            'sales_package_id' => $this->sales_package_id,
            'package_name' => $this->packages ? $this->packages->title : null,
            'package_price' => $this->packages ? $this->packages->price : null,
            'order_status' => $this->order_status
        ];
    }
}
