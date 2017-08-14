<?php

namespace Tests\Unit\DynamicFields;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;

class DynamicFieldsValidationTest extends TestCase
{
    /** @test */
    function supported_fails_if_invalid_class_is_provided()
    {
        $validator = Validator::make([
            'test' => [
                'invalidField' => 'some value',
            ]
        ], [
            'test.*' => 'df_supported:gender'
        ]);

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('test.invalidField'));
    }

    /** @test */
    function support_passes_when_field_exists_and_is_provided_as_supporded()
    {
        $validator = Validator::make([
            'test' => [
                'gender' => 'male',
            ]
        ], [
            'test.*' => 'df_supported:gender'
        ]);

        $this->assertTrue($validator->passes());
        $this->assertFalse($validator->errors()->has('test.invalidField'));
    }

    /** @test */
    function valid_fails_if_given_value_does_not_pass_validation_defined_in_field_class()
    {
        $validator = Validator::make([
            'test' => [
                'gender' => 'invalidValue',
            ]
        ], [
            'test.*' => 'df_valid'
        ]);

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('test.gender'));
    }

    /** @test */
    function valid_passes_if_given_value_is_valid_according_to_field_validation_rules()
    {
        $validator = Validator::make([
            'test' => [
                'gender' => 'male',
            ]
        ], [
            'test.*' => 'df_valid'
        ]);

        $this->assertTrue($validator->passes());
        $this->assertFalse($validator->errors()->has('test.gender'));
    }
}
