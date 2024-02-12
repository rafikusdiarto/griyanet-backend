<?php

namespace App\Http\Controllers\Api;

use App\Models\SalesPackage;
use Illuminate\Http\Request;
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
}
