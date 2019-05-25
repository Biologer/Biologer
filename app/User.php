<?php

namespace App;

use App\Concerns\HasRoles;
use App\Filters\Filterable;
use App\Concerns\CanMemoize;
use App\Jobs\DeleteUserData;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, CanMemoize, HasRoles, Notifiable, Filterable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'settings', 'institution',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email_verified_at', 'created_at', 'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'settings' => 'json',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['roles'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['full_name'];

    /**
     * The channels the user receives notification broadcasts on.
     *
     * @return string
     */
    public function receivesBroadcastNotificationsOn()
    {
        return 'users.'.$this->id;
    }

    /**
     * Filter definitions.
     *
     * @return array
     */
    protected function filters()
    {
        return [
            'name' => \App\Filters\User\NameLike::class,
            'sort_by' => \App\Filters\SortBy::class,
            'search' => \App\Filters\User\Search::class,
        ];
    }

    /**
     * List of fields that users can be sorted by.
     *
     * @return array
     */
    public static function sortableFields()
    {
        return [
            'id', 'first_name', 'last_name', 'email', 'institution',
        ];
    }

    /**
     * Observations entered by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function observations()
    {
        return $this->hasMany(Observation::class, 'created_by_id');
    }

    /**
     * Taxa the user is in charge of.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function curatedTaxa()
    {
        return $this->belongsToMany(Taxon::class);
    }

    /**
     * Full name accessor.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * Sort the users by their name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortByName($query)
    {
        return $query->orderBy('first_name')->orderBy('last_name');
    }

    /**
     * Get user settings object.
     *
     * @return \App\Settings
     */
    public function settings()
    {
        return $this->memoize('settings', function () {
            return new Settings($this);
        });
    }

    /**
     * Find user by their email.
     *
     * @param  string  $email
     * @return self
     */
    public static function findByEmail($email)
    {
        return static::where('email', $email)->firstOrFail();
    }

    /**
     * Delete the user account.
     *
     * @param  bool  $deleteObservations
     * @return void
     */
    public function deleteAccount($deleteObservations = false)
    {
        DeleteUserData::dispatch($this, $deleteObservations);

        $this->delete();
    }
}
