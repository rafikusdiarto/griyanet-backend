<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use App\Models\SalesPackage;
use Illuminate\Http\Request;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\SalesPackageResource;
use App\Http\Controllers\Api\BaseController as BaseController;

class SalesPackageController extends BaseController
{
    public function index()
    {
        try {
            $salesPackage = SalesPackage::all();
            return $this->sendResponse(SalesPackageResource::collection($salesPackage), "Successfully get data", 200);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required',
                'price' => 'required',
            ]);
            $salesPackage = SalesPackage::create([
                'title' => $request->title,
                'price' => $request->price,
            ]);
            return $this->sendResponse(new SalesPackageResource($salesPackage), 'salesPackage created successfully', 201);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required',
                'price' => 'required',
            ]);
            $salesPackage = SalesPackage::findOrFail($id);
            $salesPackage->update([
                'title' => $request->title,
                'price' => $request->price,
            ]);
            return $this->sendResponse(new SalesPackageResource($salesPackage), 'Successfully updated salesPackage', 202);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }
    }

    public function destroy($id)
    {
        try {
            $salesPackage = SalesPackage::findOrFail($id);
            $salesPackage->delete();
            return $this->sendResponse(new SalesPackageResource($salesPackage), 'Successfully deleted salesPackage', 203);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }
    }


    public function getCustomer()
    {
        try {
            $customer = Customer::with('packages')->latest()->get();
            // $customer = Customer::all();
            // dd($customer);
            return $this->sendResponse(CustomerResource::collection($customer), "Successfully get data", 200);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required',
                'phone_number' => 'required',
                'address' => 'required',
                'sales_package_id' => 'required|exists:sales_packages,id',
                'photo_ktp' => 'required|image|mimes:png,jpg,jpeg,webp|max:5120',
                'photo_rumah' => 'required|image|mimes:png,jpg,jpeg,webp|max:5120',
                'order_status' => 'required'
            ]);

            $customer = Customer::findOrFail($id);

            if ($request->hasFile('photo_ktp')) {
                if (file_exists(( $customer->photo_ktp))) {
                    unlink(( $customer->photo_ktp));
                }
                $file = $request->file('photo_ktp');
                $fileName = $file->getClientOriginalName();
                $file_ktp = 'ktp-img/' . $fileName;
                $file->move('ktp-img', $fileName);
            }else {
                $file_ktp = $customer->photo_ktp;
            }

            if ($request->hasFile('photo_rumah')) {
                if (file_exists(( $customer->photo_rumah))) {
                    unlink(( $customer->photo_rumah));
                }
                $file = $request->file('photo_rumah');
                $fileName = $file->getClientOriginalName();
                $file_rumah = 'rumah-img/' . $fileName;
                $file->move('rumah-img', $fileName);
            }else {
                $file_rumah = $customer->photo_ktp;
            }

            $customer->update([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'photo_ktp' => $file_ktp,
                'photo_rumah' => $file_rumah,
                'sales_package_id' => $request->sales_package_id,
                'order_status' => $request->order_status
            ]);
            return $this->sendResponse(new CustomerResource($customer), 'Successfully updated customer', 202);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }

    }
}
