<?php

namespace App\Http\Requests;

use App\Publication;
use App\PublicationType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SavePublication extends FormRequest
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
            'type' => ['required', Rule::in(PublicationType::values())],
            'year' => ['required', 'date_format:Y'],
            'name' => [
                'required_if:type,'.implode(PublicationType::hasName()),
                'nullable',
                'string',
                'max:255',
            ],
            'title' => ['required', 'string', 'max:255'],
            'authors' => ['required', 'array', 'min:1'],
            'authors.*' => ['array'],
            'authors.*.first_name' => ['filled', 'string', 'max:255'],
            'authors.*.last_name' => ['filled', 'string', 'max:255'],
            'editors' => [
                'required_if:type,'.implode(PublicationType::requiresEditors()),
                'array',
            ],
            'editors.*' => ['array'],
            'editors.*.first_name' => ['filled', 'string', 'max:255'],
            'editors.*.last_name' => ['filled', 'string', 'max:255'],
            'issue' => [
                'required_if:type,'.implode(PublicationType::hasIssue()),
                'nullable',
                'string',
                'max:255',
            ],
            'place' => [
                'required_if:type,'.implode(PublicationType::requiresPlace()),
                'nullable',
                'string',
            ],
            'publisher' => [
                'required_if:type,'.implode(PublicationType::requiresPublisher()),
                'nullable',
                'string',
            ],
            'page_count' => ['nullable', 'integer'],
            'page_range' => ['nullable', 'string'],
            'doi' => ['nullable', 'string'],
            'link' => ['nullable', 'string', 'max:1000'],
            'attachment_id' => ['nullable', 'exists:publication_attachments,id'],
            'citation' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'authors.*' => 'author',
            'editors.*' => 'editor',
        ];
    }

    /**
     * Save the publication using validated request data.
     *
     * @param  \App\Publication  $publication
     * @return \App\Publication
     */
    public function save(Publication $publication)
    {
        if (! $publication->exists) {
            $publication->created_by_id = $this->user()->id;
        }

        $publication->fill($this->validated())->save();

        return $publication;
    }
}
