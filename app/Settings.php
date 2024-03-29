<?php

namespace App;

use Exception;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class Settings implements Arrayable
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
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;

        $this->loadSettings(is_array($user->settings) ? $user->settings : []);
    }

    /**
     * Load initial settings using values set by user to override defaults.
     *
     * @param  array  $userSettings
     * @return void
     */
    private function loadSettings(array $userSettings)
    {
        $settings = $this->defaultSettings();

        foreach (Arr::dot($userSettings) as $key => $value) {
            Arr::set($settings, $key, $value);
        }

        $this->settings = $settings;
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
            'image_license' => ImageLicense::firstId(),
            'language' => app()->getLocale(),
            'notifications' => [
                'field_observation_approved' => [
                    'database' => true,
                    'mail' => false,
                ],
                'field_observation_edited' => [
                    'database' => true,
                    'mail' => false,
                ],
                'field_observation_moved_to_pending' => [
                    'database' => true,
                    'mail' => false,
                ],
                'field_observation_marked_unidentifiable' => [
                    'database' => true,
                    'mail' => false,
                ],
                'field_observation_for_approval' => [
                    'database' => true,
                    'mail' => false,
                ],
            ],
            'default_stage_adult' => false,
        ];
    }

    /**
     * Retrieve the given setting.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return Arr::get($this->settings, $key, $default);
    }

    /**
     * Create and persist a new setting.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function set($key, $value)
    {
        Arr::set($this->settings, $key, $value);

        $this->persist();
    }

    /**
     * Determine if the given setting exists.
     *
     * @param  string  $key
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
            Arr::only($attributes, array_keys($this->settings))
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
     * @return void
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

    public function toArray()
    {
        return [
            'data_license' => (int) $this->data_license,
            'image_license' => (int) $this->image_license,
            'language' => $this->language,
            'notifications' => [
                'field_observation_approved' => [
                    'database' => (bool) $this->notifications['field_observation_approved']['database'],
                    'mail' => (bool) $this->notifications['field_observation_approved']['mail'],
                ],
                'field_observation_edited' => [
                    'database' => (bool) $this->notifications['field_observation_edited']['database'],
                    'mail' => (bool) $this->notifications['field_observation_edited']['mail'],
                ],
                'field_observation_moved_to_pending' => [
                    'database' => (bool) $this->notifications['field_observation_moved_to_pending']['database'],
                    'mail' => (bool) $this->notifications['field_observation_moved_to_pending']['mail'],
                ],
                'field_observation_marked_unidentifiable' => [
                    'database' => (bool) $this->notifications['field_observation_marked_unidentifiable']['database'],
                    'mail' => (bool) $this->notifications['field_observation_marked_unidentifiable']['mail'],
                ],
                'field_observation_for_approval' => [
                    'database' => (bool) $this->notifications['field_observation_for_approval']['database'],
                    'mail' => (bool) $this->notifications['field_observation_for_approval']['mail'],
                ],
            ],
            'default_stage_adult' => (bool) $this->default_stage_adult,
        ];
    }
}
