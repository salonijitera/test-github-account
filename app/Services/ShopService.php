
<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class ShopService
{
    public function updateShop(int $userId, string $shopName, string $shopDescription): User
    {
        if (empty($shopName)) {
            throw new ValidationException('Shop name is required.');
        }
        if (empty($shopDescription)) {
            throw new ValidationException('Shop description is required.');
        }

        $user = User::with('shop')->findOrFail($userId);

        if (!$user->shop) {
            throw new ModelNotFoundException('User not found.');
        }

        $user->shop->name = $shopName;
        $user->shop->description = $shopDescription;

        if (!$user->shop->save()) {
            throw new \Exception('Failed to update shop information.');
        }

        return $user->shop;
    }
}
