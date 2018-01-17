<?php

namespace App;

use App\Concerns\HasRoles;
use App\Concerns\CanMemoize;
use App\Concerns\Verifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, CanMemoize, HasRoles, Notifiable, Verifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'settings', 'verified'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
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
     * Get user settings object.
     *
     * @return Settings
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
