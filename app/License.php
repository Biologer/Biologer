<?php

namespace App;

class License
{
    const CC_BY_SA = 10;
    const CC_BY_NC_SA = 20;
    const PARTIALLY_OPEN = 30;
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
     * List of attributes of the License object that can be set.
     *
     * @var array
     */
    protected $fillable = ['id', 'name'];

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
                'id' => static::CC_BY_SA,
                'name' => 'CC BY-SA 4.0',
            ]),
            new static([
                'id' => static::CC_BY_NC_SA,
                'name' => 'CC BY-NC-SA 4.0',
            ]),
            new static([
                'id' => static::PARTIALLY_OPEN,
                'name' => 'Partially open',
            ]),
            new static([
                'id' => static::CLOSED,
                'name' => 'Closed',
            ]),
        ]);
    }

    /**
     * List of available licenses.
     *
     * @return array
     */
    public static function getOptions()
    {
        return static::all()->mapWithKeys(function ($license) {
            return [$license->id => __("licenses.{$license->name}")];
        })->all();
    }

    /**
     * Get license IDs.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function ids()
    {
        return static::all()->pluck('id');
    }

    /**
     * Get ID of the first license.
     *
     * @return int
     */
    public static function firstId()
    {
        return static::ids()->first();
    }

    /**
     * Get first license info.
     *
     * @return self
     */
    public static function first()
    {
        return static::all()->first();
    }

    /**
     * Find license by given ID.
     *
     * @param  int  $id
     * @return self|null
     */
    public static function findById($id)
    {
        return static::all()->where('id', $id)->first();
    }

    /**
     * Find license by given name.
     *
     * @param  string  $name
     * @return self|null
     */
    public static function findByName($name)
    {
        return static::all()->where('name', $name)->first();
    }

    /**
     * Get name translation.
     *
     * @return string
     */
    public function name()
    {
        return trans('licenses.'.$this->name);
    }
}
