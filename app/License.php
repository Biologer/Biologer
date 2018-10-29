<?php

namespace App;

use Illuminate\Contracts\Support\Arrayable;

class License implements Arrayable
{
    const CC_BY_SA = 10;
    const OPEN = 11;
    const CC_BY_NC_SA = 20;
    const PARTIALLY_OPEN = 30;
    const CLOSED_FOR_A_PERIOD = 35;
    const CLOSED = 40;

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $link;

    /**
     * List of attributes of the License object that can be set.
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'link'];

    /**
     * Constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->fill($attributes);
    }

    /**
     * Fill the object attributes.
     *
     * @param  array  $attributes
     * @return void
     */
    public function fill(array $attributes)
    {
        collect($attributes)->filter(function ($value, $attribute) {
            return $this->isFillable($attribute);
        })->each(function ($value, $attribute) {
            $this->{$attribute} = $value;
        });
    }

    /**
     * Check if attribute is fillable.
     *
     * @param  string  $attribute
     * @return bool
     */
    protected function isFillable($attribute)
    {
        return in_array($attribute, $this->fillable);
    }

    /**
     * Get all licenses.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function all()
    {
        return collect([
            new static([
                'id' => self::CC_BY_SA,
                'name' => 'CC BY-SA 4.0',
                'link' => 'https://creativecommons.org/licenses/by-sa/4.0/',
            ]),
            new static([
                'id' => self::OPEN,
                'name' => 'Open',
                'link' => '',
            ]),
            new static([
                'id' => self::CC_BY_NC_SA,
                'name' => 'CC BY-NC-SA 4.0',
                'link' => 'https://creativecommons.org/licenses/by-nc-sa/4.0/',
            ]),
            new static([
                'id' => self::PARTIALLY_OPEN,
                'name' => 'Partially open',
                'link' => config('app.url').'/pages/partially-open-license',
            ]),
            new static([
                'id' => self::CLOSED_FOR_A_PERIOD,
                'name' => 'Closed for a period',
                'link' => '',
            ]),
            new static([
                'id' => self::CLOSED,
                'name' => 'Closed',
                'link' => config('app.url').('/pages/closed-license'),
            ]),
        ]);
    }

    /**
     * Get all active licenses.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function allActive()
    {
        return self::all()->whereIn('id', config('biologer.active_licenses'))->values();
    }

    /**
     * List of available licenses.
     *
     * @return array
     */
    public static function getOptions()
    {
        return self::allActive()->mapWithKeys(function ($license) {
            return [$license->id => $license->name()];
        })->all();
    }

    /**
     * Get license IDs.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function ids()
    {
        return self::all()->pluck('id');
    }

    /**
     * Get active license IDs.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function activeIds()
    {
        return self::allActive()->pluck('id');
    }

    /**
     * Get ID of the first license.
     *
     * @return int
     */
    public static function firstId()
    {
        return self::activeIds()->first();
    }

    /**
     * Get first license info.
     *
     * @return self
     */
    public static function first()
    {
        return self::allActive()->first();
    }

    /**
     * Find license by given ID.
     *
     * @param  int  $id
     * @return self|null
     */
    public static function findById($id)
    {
        return self::allActive()->where('id', $id)->first();
    }

    /**
     * Find license by given name.
     *
     * @param  string  $name
     * @return self|null
     */
    public static function findByName($name)
    {
        return self::allActive()->where('name', $name)->first();
    }

    /**
     * Get name translation.
     *
     * @return string
     */
    public function name()
    {
        return trans('licenses.'.$this->id);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name(),
            'link' => $this->link,
        ];
    }

    /**
     * Check if license is enabled.
     *
     * @param  int  $licenseId
     * @return bool
     */
    public static function isEnabled($licenseId)
    {
        return self::activeIds()->contains($licenseId);
    }
}
