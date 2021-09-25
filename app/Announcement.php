<?php

namespace App;

use App\Events\NewAnnouncement;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Announcement extends Model
{
    use Concerns\HasTranslatableAttributes, HasFactory, Translatable;

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'reads' => '[]',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'creator_id' => 'integer',
        'reads' => 'collection',
        'private' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['title', 'message'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = ['reads', 'creator_id'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['translations'];


    protected $translatedAttributes = ['title', 'message'];

    /**
     * The user that created the announcements.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Accessor to check if user has read the announcement.
     *
     * @return bool
     */
    public function getIsReadAttribute()
    {
        return $this->isRead();
    }

    /**
     * Title translated to current locale.
     *
     * @return string
     */
    public function getTitleAttribute()
    {
        return $this->translateOrNew($this->locale())->title;
    }

    /**
     * Message translated to current locale.
     *
     * @return string
     */
    public function getMessageAttribute()
    {
        return $this->translateOrNew($this->locale())->message;
    }

    /**
     * Publish an announcements.
     *
     * @param  array  $data
     * @param  \App\User  $creator
     * @return self
     */
    public static function publish(array $data, User $creator)
    {
        return tap(static::create(array_merge($data, [
            'creator_id' => $creator->id,
            'creator_name' => $creator->full_name,
        ])), function ($announcement) {
            NewAnnouncement::broadcast($announcement->markAsRead())->toOthers();
        });
    }

    /**
     * Mark the announcement as read by current user.
     *
     * @return self
     */
    public function markAsRead()
    {
        if (auth()->guest() || $this->isRead()) {
            return $this;
        }

        $this->reads = $this->reads->push(auth()->id())->unique()->values();

        return tap($this)->save();
    }

    /**
     * Check if announcement has been read by given user.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function isReadBy(User $user)
    {
        return $this->reads->contains($user->id);
    }

    /**
     * Check if announcement has been read by current user.
     *
     * @return bool
     */
    public function isRead()
    {
        if (! auth()->check()) {
            return false;
        }

        return $this->isReadBy(auth()->user());
    }

    /**
     * Check if announcement is properly translated to current language.
     *
     * @return bool
     */
    public function isTranslated()
    {
        if (! $this->hasTranslation()) {
            return false;
        }

        $translation = $this->translate();

        if (empty($translation->title) || empty($translation->message)) {
            return false;
        }

        return true;
    }

    /**
     * Scope the query to get only those translated in wanted locale.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|null  $locale
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeThatAreTranslated($query, $locale = null)
    {
        return $query->whereHas('translations', function ($query) use ($locale) {
            return $query->where('locale', $this->locale($locale))
                ->whereNotNull('title')
                ->where('title', '!=', '')
                ->whereNotNull('message')
                ->where('message', '!=', '');
        });
    }

    /**
     * Get author's name.
     *
     * @return string
     */
    public function authorName()
    {
        return $this->creator ? $this->creator->full_name : $this->creator_name;
    }
}
