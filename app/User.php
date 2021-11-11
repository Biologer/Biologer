<?php

namespace App;

use App\Concerns\CanMemoize;
use App\Concerns\HasRoles;
use App\Filters\Filterable;
use App\Jobs\DeleteUserData;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail, HasLocalePreference
{
    use HasApiTokens, HasFactory, CanMemoize, HasRoles, Notifiable, Filterable, SoftDeletes;

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
    protected $appends = ['full_name', 'is_verified'];

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
     * Observations of type field entered by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function observationsOfTypeField()
    {
        return $this->observations()->where('details_type', (new FieldObservation)->getMorphClass());
    }

    /**
     * Field observations identified by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fieldObservationsIdentified()
    {
        return $this->hasMany(FieldObservation::class, 'identified_by_id')
            ->whereHas('observation', function ($query) {
                $query->whereNotNull('created_by_id')->whereColumn('created_by_id', '<>', 'users.id');
            });
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
     * Unread notifications pending for summary.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function unreadNotificationsForSummaryMail()
    {
        return $this->morphMany(PendingNotification::class, 'notifiable')->latest()->unread();
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
     * Check if user has verified their email address.
     *
     * @return bool
     */
    public function getIsVerifiedAttribute()
    {
        return ! is_null($this->email_verified_at);
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
     * Get the user's preferred locale.
     *
     * @return string
     */
    public function preferredLocale()
    {
        return $this->settings()->language;
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
