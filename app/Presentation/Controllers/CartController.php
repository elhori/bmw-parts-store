<?php

namespace App\Presentation\Controllers;

use App\Application\UseCases\CartUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private CartUseCase $cartUseCase;

    public function __construct(CartUseCase $cartUseCase)
    {
        $this->cartUseCase = $cartUseCase;
    }

    public function getCart(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $cart = $this->cartUseCase->getCartForUser($userId);

        return response()->json($cart);
    }

    public function addProductToCart(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer',
        ]);

        $result = $this->cartUseCase->addProductToCart($userId, $validated['product_id'], $validated['quantity']);

        if ($result == "Product added successfully.") {
            return response()->json(['message' => $result], 201);
        }

        return response()->json(['error' => $result], 400);
    }

    public function removeProductFromCart(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $result = $this->cartUseCase->removeProductFromCart($userId, $validated['product_id']);

        if ($result == "Product removed successfully.") {
            return response()->json(['message' => $result], 200);
        }

        return response()->json(['error' => $result], 400);
    }

    public function updateCartItemQuantity(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer',
        ]);

        $result = $this->cartUseCase->updateCartItemQuantity($userId, $validated['product_id'], $validated['quantity']);

        if ($result == "Product quantity updated successfully.") {
            return response()->json(['message' => $result], 200);
        }

        return response()->json(['error' => $result], 400);
    }
}
