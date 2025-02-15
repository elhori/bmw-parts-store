<?php

namespace App\Presentation\Controllers;

use App\Application\UseCases\UserUseCase;
use App\Infra\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(private UserUseCase $userUseCase) {}

    public function index(Request $request): JsonResponse
    {
        try {
            if (!Gate::allows('view-users')) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $perPage = $request->query('per_page', 10);
            return response()->json($this->userUseCase->getPaginated($perPage));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        if (!Gate::allows('view-users')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = $this->userUseCase->getById($id);
        return $user ? response()->json($user) : response()->json(['error' => 'User not found'], 404);
    }

    public function search(string $searchTerm): JsonResponse
    {
        if (!Gate::allows('view-users')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($this->userUseCase->search($searchTerm));
    }

    public function store(Request $request): JsonResponse
    {
        if (!Gate::allows('create-users')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = $this->userUseCase->create($request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]));

        return response()->json($user, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        if (!Gate::allows('edit-users')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = $this->userUseCase->update($id, $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
        ]));

        return response()->json($user);
    }

    public function destroy(int $id): JsonResponse
    {
        if (!Gate::allows('delete-users')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return $this->userUseCase->delete($id)
            ? response()->json(['message' => 'Deleted successfully'])
            : response()->json(['error' => 'User not found'], 404);
    }

    public function changePassword(Request $request, int $id): JsonResponse
    {
        if (!Gate::allows('edit-users')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'password' => 'required|string|min:8',
        ]);

        $success = $this->userUseCase->changePassword($id, Hash::make($validatedData['password']));

        if (!$success) {
            return response()->json(['error' => 'Failed to update password'], 500);
        }

        return response()->json(['message' => 'Password updated successfully']);
    }

    public function changeRoleToAdmin(int $id): JsonResponse
    {
        if (!Gate::allows('edit-users')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $success = $this->userUseCase->changeRoleToAdmin($id);

        return $success
            ? response()->json(['message' => 'User role changed to Admin successfully'])
            : response()->json(['error' => 'Failed to change role'], 500);
    }

    public function changeRoleToManager(int $id): JsonResponse
    {
        if (!Gate::allows('edit-users')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $success = $this->userUseCase->changeRoleToManager($id);

        return $success
            ? response()->json(['message' => 'User role changed to Manager successfully'])
            : response()->json(['error' => 'Failed to change role'], 500);
    }
}
