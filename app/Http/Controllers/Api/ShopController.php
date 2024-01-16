
<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SuccessResource;
use App\Services\ShopService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\User\UpdateShopRequest;

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
            $shop_name = $request->validated()['shop_name'];
            $shop_description = $request->validated()['shop_description'];
            $shop = $this->shopService->updateShop($id, $shop_name, $shop_description);
            return (new SuccessResource([
                'message' => 'Shop information updated successfully.',
                'shop' => [
                    'user_id' => $id,
                    'shop_name' => $shop->name,
                    'shop_description' => $shop->address
                ]
            ]))->response();
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'success' => false], 500);
        }
    }
}
