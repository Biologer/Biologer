<?php

namespace App\Http\Requests;

use App\Announcement;
use App\Support\Localization;
use Illuminate\Foundation\Http\FormRequest;

class SaveAnnouncement extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required', 'array'],
            'title.*' => ['string', 'max:150'],
            'message' => ['required', 'array'],
            'message.*' => ['string'],
            'private' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get announcement data from request.
     *
     * @return array
     */
    private function getData()
    {
        $translated = Localization::transformTranslations($this->only([
            'title', 'message',
        ]));

        return collect($translated)->filter(function ($translation) {
            return !empty($translation['title']) && !empty($translation['message']);
        })->merge($this->only(['private']))->all();
    }

    /**
     * Store new Announcement.
     *
     * @return \App\Announcement
     */
    public function store()
    {
        return Announcement::publish($this->getData(), $this->user());
    }

    /**
     * Update the Announcement.
     *
     * @param  \App\Announcement  $announcement
     * @return \App\Announcement
     */
    public function update(Announcement $announcement)
    {
        return tap($announcement)->update($this->getData());
    }
}
