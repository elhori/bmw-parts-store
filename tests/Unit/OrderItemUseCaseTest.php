<?php

namespace Tests\Unit;

use App\Application\UseCases\OrderItemUseCase;
use App\Domain\Contract\IOrderItemRepository;
use App\Domain\Entities\OrderItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Mockery;
use PHPUnit\Framework\TestCase;

class OrderItemUseCaseTest extends TestCase
{
    public function testGetAllReturnsOrderItems()
    {
        $orderItemRepositoryMock = Mockery::mock(IOrderItemRepository::class);

        $orderItemRepositoryMock->shouldReceive('getAll')
            ->once()
            ->andReturn([new OrderItem(1, 1, 1, 2, 50.0)]);

        $useCase = new OrderItemUseCase($orderItemRepositoryMock);

        $orderItems = $useCase->getAll();
        $this->assertCount(1, $orderItems);
        $this->assertInstanceOf(OrderItem::class, $orderItems[0]);
    }

    public function testGetPaginatedReturnsPaginatedOrderItems()
    {
        $orderItemRepositoryMock = Mockery::mock(IOrderItemRepository::class);

        $paginationMock = Mockery::mock(LengthAwarePaginator::class);
        $paginationMock->shouldReceive('count')->andReturn(5);
        $paginationMock->shouldReceive('items')->andReturn([new OrderItem(1, 1, 1, 2, 50.0)]);

        $orderItemRepositoryMock->shouldReceive('getPaginated')
            ->once()
            ->andReturn($paginationMock);

        $useCase = new OrderItemUseCase($orderItemRepositoryMock);

        $pagination = $useCase->getPaginated(10);
        $this->assertInstanceOf(LengthAwarePaginator::class, $pagination);
        $this->assertCount(1, $pagination->items());
    }

    public function testGetByIdReturnsOrderItem()
    {
        $orderItemRepositoryMock = Mockery::mock(IOrderItemRepository::class);

        $orderItemRepositoryMock->shouldReceive('getById')
            ->once()
            ->with(1)
            ->andReturn(new OrderItem(1, 1, 1, 2, 50.0));

        $useCase = new OrderItemUseCase($orderItemRepositoryMock);

        $orderItem = $useCase->getById(1);
        $this->assertInstanceOf(OrderItem::class, $orderItem);
    }

    public function testGetByIdReturnsNullIfOrderItemNotFound()
    {
        $orderItemRepositoryMock = Mockery::mock(IOrderItemRepository::class);

        $orderItemRepositoryMock->shouldReceive('getById')
            ->once()
            ->with(999)
            ->andReturn(null);

        $useCase = new OrderItemUseCase($orderItemRepositoryMock);

        $orderItem = $useCase->getById(999);
        $this->assertNull($orderItem);
    }

    // TODO: need to test failure cases
    public function testCreateReturnsCreatedOrderItem()
    {
        $orderItemRepositoryMock = Mockery::mock(IOrderItemRepository::class);

        $orderItemRepositoryMock->shouldReceive('create')
            ->once()
            ->andReturn("Successfully created order");

        $useCase = new OrderItemUseCase($orderItemRepositoryMock);

        $orderItemData = [
            'order_id' => 1,
            'product_id' => 1,
            'quantity' => 2,
            'price' => 50.0,
        ];

        $orderItem = $useCase->create($orderItemData);

        $this->assertEquals("Successfully created order", $orderItem);
    }

    // TODO: need to test failure cases
    public function testUpdateReturnsUpdatedOrderItem()
    {
        $orderItemRepositoryMock = Mockery::mock(IOrderItemRepository::class);

        $orderItemRepositoryMock->shouldReceive('update')
            ->once()
            ->andReturn("Successfully updated order item");

        $useCase = new OrderItemUseCase($orderItemRepositoryMock);

        $orderItemData = [
            'order_id' => 1,
            'product_id' => 1,
            'quantity' => 3,
            'price' => 75.0,
        ];

        $orderItem = $useCase->update(1, $orderItemData);

        $this->assertEquals("Successfully updated order item", $orderItem);
    }

    public function testDeleteReturnsTrueWhenSuccessful()
    {
        $orderItemRepositoryMock = Mockery::mock(IOrderItemRepository::class);

        $orderItemRepositoryMock->shouldReceive('delete')
            ->once()
            ->with(1)
            ->andReturn(true);

        $useCase = new OrderItemUseCase($orderItemRepositoryMock);

        $result = $useCase->delete(1);
        $this->assertTrue($result);
    }

    public function testDeleteReturnsFalseWhenFailed()
    {
        $orderItemRepositoryMock = Mockery::mock(IOrderItemRepository::class);

        $orderItemRepositoryMock->shouldReceive('delete')
            ->once()
            ->with(999)
            ->andReturn(false);

        $useCase = new OrderItemUseCase($orderItemRepositoryMock);

        $result = $useCase->delete(999);
        $this->assertFalse($result);
    }
}
