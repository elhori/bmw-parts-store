<?php

namespace App\Presentation\Controllers;

use App\Application\UseCases\OrderItemUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrderItemController extends Controller
{
    public function __construct(private OrderItemUseCase $orderItemUseCase) {}

    public function index(Request $request): JsonResponse
    {
        if (!Gate::allows('view-order-items')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $perPage = $request->query('per_page', 10);
        return response()->json($this->orderItemUseCase->getPaginated($perPage));
    }

    public function show(int $id): JsonResponse
    {
        if (!Gate::allows('view-order-items')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $orderItem = $this->orderItemUseCase->getById($id);
        return $orderItem ? response()->json($orderItem) : response()->json(['error' => 'Order item not found'], 404);
    }

    public function store(Request $request): JsonResponse
    {
        if (!Gate::allows('manage-order-items')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $orderItem = $this->orderItemUseCase->create($validatedData);

        if (str_contains($orderItem, "Not enough stock")) {
            return response()->json(['error' => $orderItem], 400);
        }

        return response()->json(['message' => $orderItem], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        if (!Gate::allows('manage-order-items')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $orderItem = $this->orderItemUseCase->update($id, $validatedData);

        if (str_contains($orderItem, "Not enough stock")) {
            return response()->json(['error' => $orderItem], 400);
        }

        return response()->json(['message' => $orderItem], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        if (!Gate::allows('manage-order-items')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return $this->orderItemUseCase->delete($id)
            ? response()->json(['message' => 'Deleted successfully'])
            : response()->json(['error' => 'Order item not found'], 404);
    }
}
