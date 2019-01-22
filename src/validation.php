<?php

use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\StatusCode;
use Respect\Validation\Validator as v;

if (! function_exists('validator')) {
    function validator(...$rules) {
        return function (Request $request, Response $response, callable $next) use (&$rules) {
            try {
                v::allOf(...$rules)->assert($request->getParsedBody());

                return $next($request, $response);
            } catch (NestedValidationException $nestedException) {
                $violations = [];

                foreach ($nestedException as $exception) {
                    if (in_array($exception->guessId(), ['allOf', 'oneOf', 'noneOf'])) {
                        continue;
                    }

                    $violations[$exception->getName()][] = $exception->getMessage();
                }

                return $response
                    ->withStatus(StatusCode::HTTP_NOT_ACCEPTABLE)
                    ->withJson([
                        'errorCode' => 'PASSED_INVALID_DATA',
                        'violations' => $violations,
                    ]);
            }
        };
    }
}
