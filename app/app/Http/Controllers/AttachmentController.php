<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function download(Attachment $attachment)
    {
        // Check if user has access to this ticket
        $ticket = $attachment->ticket;
        $user = Auth::user();

        if ($user->isUser() && $ticket->created_by !== $user->id) {
            abort(403);
        }

        return Storage::disk('public')->download(
            'attachments/' . $attachment->filename,
            $attachment->original_name
        );
    }

    public function destroy(Attachment $attachment)
    {
        $user = Auth::user();

        // Only the uploader or admin can delete
        if ($attachment->user_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }

        $attachment->deleteFile();

        return back()->with('success', 'Attachment deleted successfully.');
    }
}
