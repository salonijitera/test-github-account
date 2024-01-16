
<?php

use App\Http\Requests\Auth\VerifyEmailRequest;
use App\Services\AuthService;
use App\Http\Resources\SuccessResource;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function verifyEmail(VerifyEmailRequest $request): JsonResponse
    {
        try {
            $response = $this->authService->verifyEmail($request->validated());
            return new SuccessResource($response);
        } catch (BadRequestHttpException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (NotFoundHttpException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
