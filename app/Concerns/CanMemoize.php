<?php

namespace App\Concerns;

trait CanMemoize
{
    protected $__memoized = [];

    /**
     * Return the same value for given key as the value that was
     * resolved the first time it was called with the same key.
     *
     * @param  string  $key
     * @param  mixed $value
     * @return mixed
     */
    protected function memoize($key, $value)
    {
        if (! array_key_exists($key, $this->__memoized)) {
            $this->__memoized[$key] = value($value);
        }

        return $this->__memoized[$key];
    }

    /**
     * Forget memoized value.
     *
     * @param  string  $key
     * @return $this
     */
    protected function forgetMemoized($key)
    {
        unset($this->__memoized[$key]);

        return $this;
    }
}
