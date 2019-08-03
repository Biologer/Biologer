<?php

namespace Tests;

trait CustomAssertArraySubset
{
    protected function customAssertArraySubset(array $expectedSubset, array $actualArray)
    {
        foreach ($expectedSubset as $key => $value) {
            if (is_array($value)) {
                $this->customAssertArraySubset($value, $actualArray[$key]);
            } else {
                $this->assertArrayHasKey($key, $actualArray);
                $this->assertEquals($value, $actualArray[$key]);
            }
        }
    }
}
