<?php

namespace App\Presentation\Controllers;

use App\Application\UseCases\OrderUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    public function __construct(private OrderUseCase $orderUseCase) {}

    public function index(Request $request): JsonResponse
    {
        if (!Gate::allows('view-orders')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $perPage = $request->query('per_page', 10);
        return response()->json($this->orderUseCase->getPaginated($perPage));
    }

    public function show(int $id): JsonResponse
    {
        if (!Gate::allows('view-orders')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $order = $this->orderUseCase->getById($id);
        return $order ? response()->json($order) : response()->json(['error' => 'Order not found'], 404);
    }

    public function store(Request $request): JsonResponse
    {
        if (!Gate::allows('create-orders')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $order = $this->orderUseCase->create($request->validate([
            'status' => 'required|string',
            'total_price' => 'required|numeric',
        ]) + ['user_id' => auth()->id()]);

        return response()->json($order, 201);
    }

    public function destroy(int $id): JsonResponse
    {
        if (!Gate::allows('delete-orders')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return $this->orderUseCase->delete($id)
            ? response()->json(['message' => 'Deleted successfully'])
            : response()->json(['error' => 'Order not found'], 404);
    }
}
