<?php

namespace App\Concerns;

trait CanMemoize
{
    protected $__memoized = [];

    /**
     * Check if we have memoized value for given key.
     *
     * @param  string  $key
     * @return bool
     */
    protected function memoized($key)
    {
        return array_key_exists($key, $this->__memoized);
    }

    /**
     * Store value that needs to be memoized.
     *
     * @param  string  $key
     * @param  mixed $value
     * @return $this
     */
    protected function memoize($key, $value)
    {
        $this->__memoized[$key] = $value;

        return $this;
    }

    /**
     * Get memoized value.
     *
     * @param  string  $key
     * @return mixed
     */
    protected function recallMemoized($key)
    {
        if (! $this->memoized($key)) {
            return;
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
