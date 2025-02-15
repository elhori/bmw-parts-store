<?php

namespace App\Presentation\Controllers;

use App\Application\UseCases\CategoryUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    public function __construct(private CategoryUseCase $categoryUseCase) {}

    public function index(Request $request): JsonResponse
    {
        if (!Gate::allows('view-categories')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $perPage = $request->query('per_page', 10);
        return response()->json($this->categoryUseCase->getPaginated($perPage));
    }

    public function show(int $id): JsonResponse
    {
        if (!Gate::allows('view-categories')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $category = $this->categoryUseCase->getById($id);
        return $category ? response()->json($category) : response()->json(['error' => 'Category not found'], 404);
    }

    public function search(string $searchTerm): JsonResponse
    {
        if (!Gate::allows('view-categories')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($this->categoryUseCase->search($searchTerm));
    }

    public function store(Request $request): JsonResponse
    {
        if (!Gate::allows('create-categories')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $category = $this->categoryUseCase->create($request->validate([
            'name' => 'required|string',
        ]));

        return response()->json($category, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        if (!Gate::allows('edit-categories')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $category = $this->categoryUseCase->update($id, $request->validate([
            'name' => 'required|string',
        ]));

        return response()->json($category);
    }

    public function destroy(int $id): JsonResponse
    {
        if (!Gate::allows('delete-categories')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return $this->categoryUseCase->delete($id)
            ? response()->json(['message' => 'Deleted successfully'])
            : response()->json(['error' => 'Category not found'], 404);
    }
}
