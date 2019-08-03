<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\UnreadNotificationsSummary;

class PendingNotification extends Model
{
    protected $fillable = [
        'notification_id', 'notifiable_id', 'notifiable_type', 'notification',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    /**
     * Scope the query to get unread notifications.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeUnread($query)
    {
        $query->where(function ($query) {
            $query->whereNotIn('notification_id', function ($query) {
                $query->select('id')->from('notifications')->whereNotNull('read_at');
            })->whereNull('sent_at');
        });
    }

    public function getIsSentAttribute()
    {
        return (bool) $this->sent_at;
    }

    /**
     * Send out email with unread notifications summary.
     *
     * @return void
     */
    public static function sendOut()
    {
        User::has('unreadNotificationsForSummaryMail')
            ->with('unreadNotificationsForSummaryMail')
            ->each(function ($user) {
                $notifications = $user->unreadNotificationsForSummaryMail->map->unserialize();

                $user->notify(new UnreadNotificationsSummary($notifications));

                static::whereIn('id', $user->unreadNotificationsForSummaryMail->pluck('id'))->update(['sent_at' => now()]);
            }, 300);
    }

    /**
     * Unserialize original notification.
     *
     * @return \Illuminate\Notifications\Notification
     */
    public function unserialize()
    {
        return unserialize($this->notification);
    }
}
