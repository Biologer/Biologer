<?php

namespace App\Http\Requests;

use App\Taxon;
use App\ViewGroup;
use App\UploadedPhoto;
use App\Support\Localization;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Http\FormRequest;

class SaveViewGroup extends FormRequest
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
     * @param  \App\ViewGroup  $group
     * @return \App\ViewGroup
     */
    public function save(ViewGroup $group)
    {
        $group->fill(array_merge(
            $this->getData($group),
            ['image_url' => $this->saveImage()]
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
     * @return string|null  URL of the image
     */
    private function saveImage()
    {
        if (($uploadPath = $this->getUploadedImagePath()) && Storage::disk('public')->exists($uploadPath)) {
            return ViewGroup::saveImageToDisk(
                basename($uploadPath),
                Storage::disk('public')->readStream($uploadPath)
            );
        }

        return $this->input('image_url');
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
