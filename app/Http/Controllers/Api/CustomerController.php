<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Resources\CustomerResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Api\BaseController as BaseController;

class CustomerController extends BaseController
{
    public function index()
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

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'phone_number' => 'required',
                'address' => 'required',
                'sales_package_id' => 'required|exists:sales_packages,id',
                'photo_ktp' => 'required|image|mimes:png,jpg,jpeg,webp|max:5120',
                'photo_rumah' => 'required|image|mimes:png,jpg,jpeg,webp|max:5120'
            ]);

            $file = $request->file('photo_ktp');
            $fileName = $file->getClientOriginalName();
            $file_ktp = 'ktp-img/' . $fileName;
            $file->move('ktp-img', $fileName);

            $file = $request->file('photo_rumah');
            $fileName = $file->getClientOriginalName();
            $file_rumah = 'rumah-img/' . $fileName;
            $file->move('rumah-img', $fileName);

            $customer = Customer::create([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'photo_ktp' => $file_ktp,
                'photo_rumah' => $file_rumah,
                'order_status' => "pending",
                'sales_package_id' => $request->sales_package_id,
                // 'image' => url("/images/cus$customer/{$fileName}")
            ]);
            return $this->sendResponse(new CustomerResource($customer), 'customer created successfully', 201);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required',
                'phone_number' => 'required',
                'address' => 'required',
                'sales_package_id' => 'required|exists:sales_packages,id',
                'photo_ktp' => 'required|image|mimes:png,jpg,jpeg,webp|max:5120',
                'photo_rumah' => 'required|image|mimes:png,jpg,jpeg,webp|max:5120'
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
                'sales_package_id' => $request->sales_package_id
            ]);
            return $this->sendResponse(new CustomerResource($customer), 'Successfully updated customer', 202);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }
    }

    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);

            $customer->delete();
            return $this->sendResponse(new CustomerResource($customer), 'Successfully deleted customer', 203);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }
    }
}
