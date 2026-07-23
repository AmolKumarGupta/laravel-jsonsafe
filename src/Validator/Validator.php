<?php

namespace Amol\LaravelJsonSafe\Validator;

use Amol\LaravelJsonSafe\JsonSafeable;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Opis\JsonSchema\Errors\ErrorFormatter;
use Opis\JsonSchema\Helper;
use Opis\JsonSchema\Validator as JsonSchemaValidator;

class Validator
{
    public static function validate(JsonSafeable $jsonsafeable, Model $model, string $key): void
    {
        $methodName = ModelMethodResolver::resolve($key);
        if (! ModelMethodResolver::doesResolveMethodExists($model, $methodName)) {
            return;
        }

        $rawSchema = $model->{$methodName}();
        /** @var object $schema */
        $schema = Helper::toJSON($rawSchema);

        $rawData = $jsonsafeable->toArray();
        $data = Helper::toJSON($rawData);
        $validator = new JsonSchemaValidator(max_errors: 50);
        $validationResult = $validator->validate($data, $schema);

        if ($validationResult->hasError() && $errors = $validationResult->error()) {
            $formatter = new ErrorFormatter;
            $list = $formatter->formatKeyed($errors);

            $message = json_encode($list);
            throw new Exception(message: $message ?: 'Invalid Json');
        }
    }
}
