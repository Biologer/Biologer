<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublicationAttachmentResource;
use App\PublicationAttachment;
use Illuminate\Http\Request;

class PublicationAttachmentsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'attachment' => [
                'required', 'file', 'max:'.config('biologer.max_upload_size'),
                'mimes:pdf,doc,docx,odt',
            ],
        ]);

        return new PublicationAttachmentResource(
            PublicationAttachment::createFromUploadedFile($request->file('attachment'))
        );
    }

    public function destroy(PublicationAttachment $publicationAttachment)
    {
        $publicationAttachment->delete();

        return response()->json(null, 204);
    }
}
