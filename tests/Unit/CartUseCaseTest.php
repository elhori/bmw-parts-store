<?php

namespace Tests\Unit;

use App\Application\UseCases\CartUseCase;
use App\Domain\Contract\ICartRepository;
use App\Domain\Entities\Cart;
use Mockery;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Cache;

class CartUseCaseTest extends TestCase
{
    public function testGetCartForUserReturnsCart()
    {
        $cartRepositoryMock = Mockery::mock(ICartRepository::class);

        $cartRepositoryMock->shouldReceive('getCartForUser')
            ->once()
            ->with(1)
            ->andReturn(new Cart(1));

        $useCase = new CartUseCase($cartRepositoryMock);

        $cart = $useCase->getCartForUser(1);

        $this->assertInstanceOf(Cart::class, $cart);
    }

    public function testAddProductToCartAddsProductSuccessfully()
    {
        $cartRepositoryMock = Mockery::mock(ICartRepository::class);

        Cache::shouldReceive('put')->once()->with('cart_user_1', Mockery::type(Cart::class), Mockery::any());

        $cartRepositoryMock->shouldReceive('addProductToCart')
            ->once()
            ->with(1, 1, 2)
            ->andReturn("Product added successfully.");

        $useCase = new CartUseCase($cartRepositoryMock);

        $message = $useCase->addProductToCart(1, 1, 2);

        $this->assertEquals("Product added successfully.", $message);
    }

    public function testRemoveProductFromCartRemovesProductSuccessfully()
    {
        $cartRepositoryMock = Mockery::mock(ICartRepository::class);
        Cache::shouldReceive('put')->once()->with('cart_user_1', Mockery::type(Cart::class), Mockery::any());

        $cartRepositoryMock->shouldReceive('removeProductFromCart')
            ->once()
            ->with(1, 1)
            ->andReturn("Product removed successfully.");

        $useCase = new CartUseCase($cartRepositoryMock);

        $message = $useCase->removeProductFromCart(1, 1);

        $this->assertEquals("Product removed successfully.", $message);
    }

    public function testUpdateCartItemQuantityUpdatesQuantitySuccessfully()
    {
        $cartRepositoryMock = Mockery::mock(ICartRepository::class);
        Cache::shouldReceive('put')->once()->with('cart_user_1', Mockery::type(Cart::class), Mockery::any());

        $cartRepositoryMock->shouldReceive('updateCartItemQuantity')
            ->once()
            ->with(1, 1, 3)
            ->andReturn("Product quantity updated successfully.");

        $useCase = new CartUseCase($cartRepositoryMock);

        $message = $useCase->updateCartItemQuantity(1, 1, 3);

        $this->assertEquals("Product quantity updated successfully.", $message);
    }

    public function testAddProductToCartReturnsErrorWhenNotEnoughStock()
    {
        $cartRepositoryMock = Mockery::mock(ICartRepository::class);
        Cache::shouldReceive('put')->once()->with('cart_user_1', Mockery::type(Cart::class), Mockery::any());

        $cartRepositoryMock->shouldReceive('addProductToCart')
            ->once()
            ->with(1, 1, 1000)
            ->andReturn("Not enough stock for product Test Product. Available stock: 100");

        $useCase = new CartUseCase($cartRepositoryMock);

        $message = $useCase->addProductToCart(1, 1, 1000);

        $this->assertEquals("Not enough stock for product Test Product. Available stock: 100", $message);
    }

    public function testUpdateCartItemQuantityReturnsErrorWhenNotEnoughStock()
    {
        $cartRepositoryMock = Mockery::mock(ICartRepository::class);
        Cache::shouldReceive('put')->once()->with('cart_user_1', Mockery::type(Cart::class), Mockery::any());

        $cartRepositoryMock->shouldReceive('updateCartItemQuantity')
            ->once()
            ->with(1, 1, 1000)
            ->andReturn("Not enough stock for product Test Product. Available stock: 100");

        $useCase = new CartUseCase($cartRepositoryMock);

        $message = $useCase->updateCartItemQuantity(1, 1, 1000);

        $this->assertEquals("Not enough stock for product Test Product. Available stock: 100", $message);
    }
}
