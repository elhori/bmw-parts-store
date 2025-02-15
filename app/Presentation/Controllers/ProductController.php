<?php

namespace App\Presentation\Controllers;

use App\Application\UseCases\ProductUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    public function __construct(private ProductUseCase $productUseCase) {}

    public function index(Request $request): JsonResponse
    {
        if (!Gate::allows('view-products')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $perPage = $request->query('per_page', 10);
        return response()->json($this->productUseCase->getPaginated($perPage));
    }

    public function show(int $id): JsonResponse
    {
        if (!Gate::allows('view-products')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $product = $this->productUseCase->getById($id);
        return $product ? response()->json($product) : response()->json(['error' => 'Product not found'], 404);
    }

    public function search(string $searchTerm): JsonResponse
    {
        if (!Gate::allows('view-products')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($this->productUseCase->search($searchTerm));
    }

    public function store(Request $request): JsonResponse
    {
        if (!Gate::allows('create-products')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $product = $this->productUseCase->create($request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'nullable|integer|exists:categories,id',
        ]));

        return response()->json($product, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        if (!Gate::allows('edit-products')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $product = $this->productUseCase->update($id, $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'nullable|integer|exists:categories,id',
        ]));

        return response()->json($product);
    }

    public function destroy(int $id): JsonResponse
    {
        if (!Gate::allows('delete-products')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return $this->productUseCase->delete($id)
            ? response()->json(['message' => 'Deleted successfully'])
            : response()->json(['error' => 'Product not found'], 404);
    }
}
