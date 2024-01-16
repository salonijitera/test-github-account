
<?php

namespace App\Services;

use App\Models\Shop;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class ShopService
{
    public function updateShop(int $id, string $name, string $address): Shop
    {
        if (empty($name) || empty($address)) {
            throw new ValidationException('Name and address cannot be empty.');
        }

        $shop = Shop::findOrFail($id);

        $shop->name = $name;
        $shop->address = $address;

        if (!$shop->save()) {
            throw new \Exception('Failed to update shop information.');
        }

        return $shop;
    }
}
