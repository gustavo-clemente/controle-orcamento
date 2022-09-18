<?php

namespace App\Trait;

use JsonSchema\Validator;

trait SchemaValidation
{
    protected static function assertJsonSchema($schema, $data)
    {
        $validator = new Validator();
        $validator->validate($data, $schema);

        self::assertTrue($validator->isValid(), 'Failed on schema validation');
    }
}