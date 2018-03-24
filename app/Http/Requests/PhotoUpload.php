<?php

namespace App\Http\Requests;

use App\UploadedPhoto;
use App\Support\HumanReadable;
use Illuminate\Foundation\Http\FormRequest;

class PhotoUpload extends FormRequest
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
            'file' => [
                'required', 'image', 'max:'.config('biologer.max_upload_size'),
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'file.max' => trans('validation.photo_max', [
                'size' => HumanReadable::fileSize(config('biologer.max_upload_size')),
            ]),
        ];
    }

    /**
     * Process uploading photo.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function process()
    {
        $filename = UploadedPhoto::store($this->file('file'));

        return response()->json([
            'file' => $filename,
            'exif' => UploadedPhoto::formatedExif($filename),
        ]);
    }
}
