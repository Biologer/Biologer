<?php

namespace App;

use App\Mail\VerificationEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class VerificationToken extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'token'];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'token';
    }

    /**
     * User this verification token is for.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Send email with verification link to the user.
     *
     * @return void
     */
    public function send()
    {
        Mail::to($this->user)->queue(new VerificationEmail($this));
    }

    /**
     * Check if user is verified.
     *
     * @return bool
     */
    public function userVerified()
    {
        return $this->user->verified;
    }

    /**
     * Verify user.
     *
     * @return User
     */
    public function markUserAsVerified()
    {
        return $this->user->markAsVerified();
    }
}
