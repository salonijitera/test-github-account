
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\UpdateShopRequest;
use App\Http\Resources\SuccessResource;
use App\Services\ShopService;
use Illuminate\Http\JsonResponse;

class ShopController extends Controller
{
    protected ShopService $shopService;

    public function __construct(ShopService $shopService)
    {
        $this->shopService = $shopService;
    }

    public function update(UpdateShopRequest $request, $id): JsonResponse
    {
        try {
            $this->shopService->updateShop($id, $request->validated()['name'], $request->validated()['address']);
            return (new SuccessResource('Shop information updated successfully.'))->response();
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'success' => false], 500);
        }
    }
}
