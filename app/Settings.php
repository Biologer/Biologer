<?php

namespace App;

use Exception;

class Settings
{
    /**
     * The User instance.
     *
     * @var User
     */
    protected $user;

    /**
     * The list of settings.
     *
     * @var array
     */
    protected $settings = [];

    /**
     * Create a new settings instance.
     *
     * @param  \App\User  $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->settings = array_merge(
            $this->defaultSettings(),
            is_array($user->settings) ? $user->settings : []
        );
    }

    /**
     * Default settings.
     *
     * @return array
     */
    private function defaultSettings()
    {
        return [
            'data_license' => License::firstId(),
            'image_license' => License::firstId(),
            'language' => app()->getLocale(),
        ];
    }

    /**
     * Retrieve the given setting.
     *
     * @param  string $key
     * @return string
     */
    public function get($key)
    {
        return array_get($this->settings, $key);
    }

    /**
     * Create and persist a new setting.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function set($key, $value)
    {
        $this->settings[$key] = $value;

        $this->persist();
    }

    /**
     * Determine if the given setting exists.
     *
     * @param  string $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->settings);
    }

    /**
     * Retrieve an array of all settings.
     *
     * @return array
     */
    public function all()
    {
        return $this->settings;
    }

    /**
     * Merge the given attributes with the current settings.
     * But do not assign any new settings.
     *
     * @param  array  $attributes
     * @return mixed
     */
    public function merge(array $attributes)
    {
        $this->settings = array_merge(
            $this->settings,
            array_only($attributes, array_keys($this->settings))
        );

        $this->persist();

        return $this;
    }

    /**
     * Persist the settings.
     *
     * @return mixed
     */
    protected function persist()
    {
        return $this->user->update(['settings' => $this->settings]);
    }

    /**
     * Magic property access for settings.
     *
     * @param  string  $key
     * @return mixed
     *
     * @throws \Exception
     */
    public function __get($key)
    {
        if ($this->has($key)) {
            return $this->get($key);
        }

        throw new Exception("The {$key} setting does not exist.");
    }

    /**
     * Magick property set for settings.
     *
     * @param  string  $key
     * @param  mixed  $value
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Magick method to chek if setting is set.
     *
     * @param  string  $key
     * @return bool
     */
    public function __isset($key)
    {
        return $this->has($key);
    }
}
