<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\PublicationAttachment;
use App\Http\Controllers\Controller;
use App\Http\Resources\PublicationAttachmentResource;

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
