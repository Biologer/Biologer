<?php

namespace App;

use Illuminate\Contracts\Support\Arrayable;

class ImageLicense implements Arrayable
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
     * @var string
     */
    public $link;

    /**
     * Constraint the query for field observations when this license is applied.
     *
     * @var \Closure
     */
    private $fieldObservationConstraint;

    /**
     * List of attributes of the License object that can be set.
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'link', 'fieldObservationConstraint'];

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
                'fieldObservationConstraint' => function ($query) {
                    $query->where('license', static::CC_BY_SA);
                },
            ]),
            new static([
                'id' => self::CC_BY_NC_SA,
                'name' => 'CC BY-NC-SA 4.0',
                'link' => 'https://creativecommons.org/licenses/by-nc-sa/4.0/',
                'fieldObservationConstraint' => function ($query) {
                    $query->where('license', static::CC_BY_NC_SA);
                },
            ]),
            new static([
                'id' => self::PARTIALLY_OPEN,
                'name' => 'Copyrighted',
                'link' => route('licenses.partially-open-image-license'),
                'fieldObservationConstraint' => function ($query) {
                    $query->where('license', static::PARTIALLY_OPEN);
                },
            ]),
            new static([
                'id' => self::CLOSED,
                'name' => 'Closed',
                'link' => route('licenses.closed-image-license'),
                'fieldObservationConstraint' => function ($query) {
                    $query->where('license', static::CLOSED)->whereRaw('0=1');
                },
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
        return self::all()->mapWithKeys(function ($license) {
            return [$license->id => $license->name()];
        })->all();
    }

    /**
     * Get active license IDs.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function ids()
    {
        return self::all()->pluck('id');
    }

    /**
     * Get ID of the first license.
     *
     * @return int
     */
    public static function firstId()
    {
        return self::ids()->first();
    }

    /**
     * Get first license info.
     *
     * @return self
     */
    public static function first()
    {
        return self::all()->first();
    }

    /**
     * Find license by given ID.
     *
     * @param  int  $id
     * @return self|null
     */
    public static function findById($id)
    {
        return self::all()->where('id', $id)->first();
    }

    /**
     * Find license by given name.
     *
     * @param  string  $name
     * @return self|null
     */
    public static function findByName($name)
    {
        return self::all()->where('name', $name)->first();
    }

    /**
     * Get name translation.
     *
     * @return string
     */
    public function name()
    {
        return trans('licenses.image.'.$this->id);
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
}
