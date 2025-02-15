<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (ModelNotFoundException $e, Request $request) {
            return response()->json([
                'message' => 'العنصر الذي تبحث عنه غير موجود.',
            ], 404);
        });

        $exceptions->renderable(function (NotFoundHttpException $e, Request $request) {
            return response()->json([
                'message' => 'العنصر غير موجود',
            ], 404);
        });

        $exceptions->renderable(function (ValidationException $e, Request $request) {
            return response()->json([
                'message' => 'خطأ في التحقق من البيانات.',
                'errors' => $e->errors(),
            ], 422);
        });

        $exceptions->renderable(function (Exception $e, Request $request) {
            // التعامل مع باقي الاستثناءات العامة
            return response()->json([
                'message' => 'حدث خطأ غير متوقع.',
            ], 500);
        });
    })->create();
