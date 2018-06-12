<?php

namespace App;

use App\Concerns\HasRoles;
use App\Filters\Filterable;
use App\Concerns\CanMemoize;
use App\Concerns\Verifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;

class User extends Authenticatable
{
    use HasApiTokens, CanMemoize, HasRoles, Notifiable, Verifiable, Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'settings', 'verified',
        'institution',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'verified', 'created_at', 'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'settings' => 'json',
        'verified' => 'boolean',
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

    protected function filters()
    {
        return [
            'name' => \App\Filters\User\NameLike::class,
            'sort_by' => \App\Filters\SortBy::class,
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
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
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
}
