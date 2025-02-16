<?php

namespace Tests\Unit;

use App\Application\UseCases\OrderUseCase;
use App\Domain\Contract\IOrderRepository;
use App\Domain\Entities\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Mockery;
use PHPUnit\Framework\TestCase;

class OrderUseCaseTest extends TestCase
{
    public function testGetAllReturnsOrders()
    {
        $orderRepositoryMock = Mockery::mock(IOrderRepository::class);

        $orderRepositoryMock->shouldReceive('getAll')
            ->once()
            ->andReturn([new Order(1, 1, 'pending', 100.0)]);

        $useCase = new OrderUseCase($orderRepositoryMock);

        $orders = $useCase->getAll();
        $this->assertCount(1, $orders);
        $this->assertInstanceOf(Order::class, $orders[0]);
    }

    public function testGetPaginatedReturnsPaginatedOrders()
    {
        $orderRepositoryMock = Mockery::mock(IOrderRepository::class);

        $paginationMock = Mockery::mock(LengthAwarePaginator::class);
        $paginationMock->shouldReceive('count')->andReturn(5);
        $paginationMock->shouldReceive('items')->andReturn([new Order(1, 1, 'pending', 100.0)]);

        $orderRepositoryMock->shouldReceive('getPaginated')
            ->once()
            ->andReturn($paginationMock);

        $useCase = new OrderUseCase($orderRepositoryMock);

        $pagination = $useCase->getPaginated(10);
        $this->assertInstanceOf(LengthAwarePaginator::class, $pagination);
        $this->assertCount(1, $pagination->items());
    }

    public function testGetByIdReturnsOrder()
    {
        $orderRepositoryMock = Mockery::mock(IOrderRepository::class);

        $orderRepositoryMock->shouldReceive('getById')
            ->once()
            ->with(1)
            ->andReturn(new Order(1, 1, 'pending', 100.0));

        $useCase = new OrderUseCase($orderRepositoryMock);

        $order = $useCase->getById(1);
        $this->assertInstanceOf(Order::class, $order);
    }

    public function testGetByIdReturnsNullIfOrderNotFound()
    {
        $orderRepositoryMock = Mockery::mock(IOrderRepository::class);

        $orderRepositoryMock->shouldReceive('getById')
            ->once()
            ->with(999)
            ->andReturn(null);

        $useCase = new OrderUseCase($orderRepositoryMock);

        $order = $useCase->getById(999);
        $this->assertNull($order);
    }

    public function testCreateReturnsCreatedOrder()
    {
        $orderRepositoryMock = Mockery::mock(IOrderRepository::class);

        $orderRepositoryMock->shouldReceive('create')
            ->once()
            ->andReturn(new Order(null, 1, 'pending', 100.0));

        $useCase = new OrderUseCase($orderRepositoryMock);

        $orderData = [
            'user_id' => 1,
            'status' => 'pending',
            'total_price' => 100.0
        ];

        $order = $useCase->create($orderData);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals(1, $order->userId);
        $this->assertEquals('pending', $order->status);
        $this->assertEquals(100.0, $order->totalPrice);
    }

    public function testDeleteReturnsTrueWhenSuccessful()
    {
        $orderRepositoryMock = Mockery::mock(IOrderRepository::class);

        $orderRepositoryMock->shouldReceive('delete')
            ->once()
            ->with(1)
            ->andReturn(true);

        $useCase = new OrderUseCase($orderRepositoryMock);

        $result = $useCase->delete(1);
        $this->assertTrue($result);
    }

    public function testDeleteReturnsFalseWhenFailed()
    {
        $orderRepositoryMock = Mockery::mock(IOrderRepository::class);

        $orderRepositoryMock->shouldReceive('delete')
            ->once()
            ->with(999)
            ->andReturn(false);

        $useCase = new OrderUseCase($orderRepositoryMock);

        $result = $useCase->delete(999);
        $this->assertFalse($result);
    }
}
