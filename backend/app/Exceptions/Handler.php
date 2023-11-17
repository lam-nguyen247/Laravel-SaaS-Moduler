<?php

namespace App\Exceptions;

use App\Mail\ErrorNotificationMail;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\MultipleRecordsFoundException;
use Illuminate\Database\RecordsNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    public const HTTP_TOKEN_MISMATCHED = 419;

    private array $notSendErrorNotificationMailList = [
        AuthenticationException::class,
        AuthorizationException::class,
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        MultipleRecordsFoundException::class,
        RecordsNotFoundException::class,
        SuspiciousOperationException::class,
        TokenMismatchException::class,
        ValidationException::class,
    ];

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        parent::register();
        $this->reportable(function (Throwable $e) {
            if (config('app.debug')) {
                // Output detailed errors in the log when debugging
                logs()->error($e->getMessage(), [$e->getTraceAsString()]);
            }
            if (config('exception.notification_mail.enabled') && $this->shouldSendErrorNotificationMail($e)) {
                // Output it to the log as well (if error email sending fails, it will be difficult to investigate the cause)
                logs()->error('[Error email sent]' . $e->getMessage(), [$e->getTraceAsString()]);
                // Send error notification email
                $this->sendErrorNotificationMail($e);
            }
        });
    }

    protected function shouldSendErrorNotificationMail(Throwable $e): bool
    {
        return is_null(Arr::first($this->notSendErrorNotificationMailList, function ($type) use ($e) {
            return $e instanceof $type;
        }));
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request   $request
     * @param  Throwable $e
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e): Response
    {
        if ($request->is('api/*')) {
            // Request API
            return $this->renderApi($request, $e);
        }

        // Others (screen)
        return parent::render($request, $e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request           $request
     * @param  Request|Throwable $e
     * @return Response
     *
     * @throws Throwable
     */
    private function renderApi(Request $request, Request|Throwable $e): Response
    {
        // 404 Not Found
        if ($this->isHttpException($e)) {
            //@phpstan-ignore-next-line
            if ($e->getStatusCode() === Response::HTTP_NOT_FOUND) {
                return $this->generateErrorJsonResponse(Response::HTTP_NOT_FOUND);
            }
        }

        // Conflict
        if ($e instanceof ConflictHttpException) {
            return $this->generateErrorJsonResponse(Response::HTTP_CONFLICT);
        }

        // Method Not Allowed
        if ($e instanceof MethodNotAllowedHttpException) {
            return $this->generateErrorJsonResponse(Response::HTTP_METHOD_NOT_ALLOWED);
        }

        // Token mismatch
        if ($e instanceof TokenMismatchException) {
            return $this->generateErrorJsonResponse(self::HTTP_TOKEN_MISMATCHED, 'Token mismatched');
        }

        // Too Many Attempts
        if ($e instanceof ThrottleRequestsException) {
            return $this->generateErrorJsonResponse(Response::HTTP_TOO_MANY_REQUESTS);
        }

        // validation errorはそのまま返却
        if ($e instanceof ValidationException) {
            return parent::render($request, $e);
        }

        // 認証エラー
        if ($e instanceof AuthenticationException) {
            return $this->generateErrorJsonResponse(Response::HTTP_UNAUTHORIZED);
        }

        // 認可エラー
        if ($e instanceof AuthorizationException) {
            return $this->generateErrorJsonResponse(Response::HTTP_FORBIDDEN);
        }

        // Bad Request
        if ($e instanceof BadRequestHttpException) {
            return $this->generateErrorJsonResponse(Response::HTTP_BAD_REQUEST);
        }

        // その他のエラーは全て500扱い
        return $this->generateErrorJsonResponse(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    private function sendErrorNotificationMail(Throwable $e): void
    {
        Mail::send(new ErrorNotificationMail($e));
    }

    private function generateErrorJsonResponse(int $statusCode, string $message = null): JsonResponse
    {
        $message = $message ?: Response::$statusTexts[$statusCode];
        $data = [
            'status_code' => $statusCode,
            'message' => $message,
        ];

        return response()->json($data, $statusCode);
    }
}
