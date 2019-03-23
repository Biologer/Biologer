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
            'title' => ['required', 'array', 'contains_non_empty'],
            'title.*' => ['nullable', 'string', 'max:150'],
            'message' => ['required', 'array', 'contains_non_empty'],
            'message.*' => ['nullable', 'string'],
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
            return ! empty($translation['title']) && ! empty($translation['message']);
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
        $data = $this->getData();
        $keys = array_keys($data);

        $localesToRemove = $announcement->translations->pluck('locale')->reject(function ($locale) use ($keys) {
            return in_array($locale, $keys);
        });

        if ($localesToRemove->isNotEmpty()) {
            $announcement->deleteTranslations($localesToRemove->all());
        }

        return tap($announcement)->update($data);
    }
}
