<?php

namespace App\Exceptions\Formatters;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Optimus\Heimdal\Formatters\ExceptionFormatter;

class ModelNotFoundExceptionFormatter extends ExceptionFormatter
{
    public function format(JsonResponse $response, Exception $e, array $reporterResponses)
    {
        parent::format($response, $e, $reporterResponses);

        if ($e instanceof ModelNotFoundException) {
            $data = $response->getData(true);
            $data['message'] = 'Resource not found';
            $response->setData($data);
            $response->setStatusCode(404);
        }

        return $response;
    }
}