<?php

namespace App\Infra\Repositories;

use App\Domain\Contract\ICartRepository;
use App\Domain\Entities\Cart;
use App\Domain\Entities\CartItem;
use App\Infra\Models\Product;
use Illuminate\Support\Facades\Cache;

class CartRepository implements ICartRepository
{
    public function getCartForUser(int $userId): Cart
    {
        return Cache::remember("cart_user_{$userId}", now()->addMinutes(30), function () use ($userId) {
            return new Cart($userId);
        });
    }

    public function addProductToCart(int $userId, int $productId, int $quantity): string
    {
        $product = Product::findOrFail($productId);

        if ($quantity > $product->stock) {
            return "Not enough stock for product {$product->name}. Available stock: {$product->stock}";
        }

        $cart = $this->getCartForUser($userId);

        $cartItem = new CartItem($productId, $quantity);
        $cart->addItem($cartItem);

        Cache::put("cart_user_{$userId}", $cart, now()->addMinutes(30));

        return "Product added successfully.";
    }

    public function removeProductFromCart(int $userId, int $productId): string
    {
        $cart = $this->getCartForUser($userId);

        $cart->removeItem($productId);

        Cache::put("cart_user_{$userId}", $cart, now()->addMinutes(30));

        return "Product removed successfully.";
    }

    public function updateCartItemQuantity(int $userId, int $productId, int $quantity): string
    {
        $product = Product::findOrFail($productId);

        $cart = $this->getCartForUser($userId);

        $existingItem = $cart->getItem($productId);
        $currentQuantity = $existingItem ? $existingItem->quantity : 0;

        $quantityDifference = $quantity - $currentQuantity;

        if ($quantityDifference > 0 && $quantityDifference > $product->stock) {
            return "Not enough stock for product {$product->name}. Available stock: {$product->stock}";
        }

        $cart->updateItemQuantity($productId, $quantity);

        Cache::put("cart_user_{$userId}", $cart, now()->addMinutes(30));

        return "Product quantity updated successfully.";
    }
}
