<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

use App\Traits\ApiResponser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {//exception error when data were no sent in a request
            return $this->convertValidationExceptionToResponse($exception, $request);
        }
        if ($exception instanceof ModelNotFoundException) {//exception error when are not instances with x id in database
            $modelo = class_basename($exception->getModel());
            return $this->errorResponse("No existe ninguna instancia de {$modelo} con el id espeficico", 404);
        }
        if ($exception instanceof AuthenticationException) {// exception error in autentication
            return $this->unauthenticated($request, $exception);
        }
        if ($exception instanceof AuthorizationException){// exception error when user does not have Authorization
            return $this->errorResponse('No posee permisos para ejecutar esta acción', 403);
        }
        if ($exception instanceof NotFoundHttpException){// exception error when URL is not found
            return $this->errorResponse('No se encontro la URL especificada', 404);
        }
        if ($exception instanceof MethodNotAllowedHttpException){// exception error when Method is not allowed
            return $this->errorResponse("El método ".$request->method()." especificado en la petición no es válido", 405);
        }
        if ($exception instanceof HttpException){// exception error to general proposes 
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }
        if ($exception instanceof QueryException){// exception error to Query exception  
            //dd($exception) nos ayuda a depurar valores, ver(Manejando la Eliminacion de recursos Relacionados)
            $code = $exception->errorInfo[1];
            if ($code == 1451){
                return $this->errorResponse('No se puede eliminar de forma permanente el recurso porque está relacionado con algún otro.', 409);
            }
            
        }
        
        if (config('app.debug')){
            return parent::render($request, $exception);
        }
        
        return $this->errorResponse('Falla inesperada. Intente luego',500);
        
    }
 
 
 
    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();
 
        return $this->errorResponse($errors, 422);
    }
    /**
     * convert an autentication exception into an unauthenticated response.
     * 
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Auth\AuthenticationException $exception
     * @return \Illuminate\Http\Response
     */

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->errorResponse('No autenticated', 401);
    }
 

}
