<?php

namespace App\Domain\Contract;

use App\Domain\Entities\Cart;

interface ICartRepository
{
    public function getCartForUser(int $userId): Cart;
    public function addProductToCart(int $userId, int $productId, int $quantity): string;
    public function removeProductFromCart(int $userId, int $productId): string;
    public function updateCartItemQuantity(int $userId, int $productId, int $quantity): string;
}
