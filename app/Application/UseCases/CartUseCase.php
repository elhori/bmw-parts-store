<?php

namespace App\Application\UseCases;

use App\Domain\Contract\ICartRepository;

class CartUseCase
{
    private ICartRepository $cartRepository;

    public function __construct(ICartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function getCartForUser(int $userId)
    {
        return $this->cartRepository->getCartForUser($userId);
    }

    public function addProductToCart(int $userId, int $productId, int $quantity)
    {
        return $this->cartRepository->addProductToCart($userId, $productId, $quantity);
    }

    public function removeProductFromCart(int $userId, int $productId)
    {
        return $this->cartRepository->removeProductFromCart($userId, $productId);
    }

    public function updateCartItemQuantity(int $userId, int $productId, int $quantity)
    {
        return $this->cartRepository->updateCartItemQuantity($userId, $productId, $quantity);
    }
}
