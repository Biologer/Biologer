<?php

namespace App\Http\Requests;

use App\Support\Localization;
use App\Models\Taxon;
use App\UploadedPhoto;
use App\Models\ViewGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SaveViewGroup extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'parent_id' => [
                'nullable',
                Rule::exists('view_groups', 'id')->whereNull('parent_id'),
            ],
            'name' => ['required', 'array'],
            'description' => ['required', 'array'],
            'image_url' => ['nullable', 'string', 'max:255'],
            'image_path' => ['nullable', 'string', 'max:255'],
            'only_observed_taxa' => ['nullable', 'boolean'],
            'taxa_ids' => [
                'array',
                Rule::in(Taxon::pluck('id')->all()),
            ],
        ];
    }

    /**
     * Save given group with data from the request.
     *
     * @param  \App\Models\ViewGroup  $group
     * @return \App\Models\ViewGroup
     */
    public function save(ViewGroup $group)
    {
        $group->fill(array_merge(
            $this->getData($group),
            ['image_path' => $this->saveImage($group)]
        ))->save();

        $group->taxa()->sync(request('taxa_ids', []));

        return $group;
    }

    /**
     * Get data from request.
     *
     * @return array
     */
    protected function getData()
    {
        return array_merge(request()->only([
            'parent_id', 'only_observed_taxa',
        ]), Localization::transformTranslations(request()->only([
            'name', 'description',
        ])));
    }

    /**
     * Save uploaded image for the ViewGroup.
     *
     * @param  \App\Models\ViewGroup  $group
     * @return string|null  URL of the image
     */
    private function saveImage(ViewGroup $group)
    {
        if (($uploadPath = $this->getUploadedImagePath()) && Storage::disk('public')->exists($uploadPath)) {
            return ViewGroup::saveImageToDisk(
                basename($uploadPath),
                Storage::disk('public')->readStream($uploadPath)
            );
        }

        return $this->input('image_url')
            ? $group->image_path
            : null;
    }

    /**
     * Get path to uploaded file.
     *
     * @return string|null
     */
    private function getUploadedImagePath()
    {
        if ($path = $this->input('image_path')) {
            return UploadedPhoto::relativePath($path);
        }
    }
}
