<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    /**
     * Store a new contact form submission.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'project_type' => ['nullable', 'string', Rule::in(['web', 'mobile', 'saas', 'api', 'consulting', 'other'])],
            'budget' => ['nullable', 'string', Rule::in(['small', 'medium', 'large', 'enterprise', 'not-sure'])],
            'message' => ['required', 'string', 'min:10'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $contact = Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'project_type' => $request->project_type,
            'budget' => $request->budget,
            'message' => $request->message,
            'status' => 'new',
        ]);

        return response()->json([
            'message' => 'Contact form submitted successfully',
            'data' => new ContactResource($contact)
        ], 201);
    }

    /**
     * List all contacts (for admin).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Contact::query()
            ->when($request->status, function ($q, $status) {
                return $q->where('status', $status);
            })
            ->when($request->search, function ($q, $search) {
                return $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('message', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc');

        $contacts = $query->paginate($request->per_page ?? 20);

        return response()->json([
            'data' => ContactResource::collection($contacts->items()),
            'meta' => [
                'current_page' => $contacts->currentPage(),
                'last_page' => $contacts->lastPage(),
                'per_page' => $contacts->perPage(),
                'total' => $contacts->total(),
            ]
        ]);
    }

    /**
     * Show a single contact.
     */
    public function show(Contact $contact): JsonResponse
    {
        return response()->json([
            'data' => new ContactResource($contact)
        ]);
    }

    /**
     * Update contact status.
     */
    public function update(Request $request, Contact $contact): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => ['required', 'string', Rule::in(['new', 'in_progress', 'replied', 'archived'])],
            'notes' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $contact->update([
            'status' => $request->status,
            'notes' => $request->notes,
            'replied_at' => $request->status === 'replied' ? now() : $contact->replied_at,
        ]);

        return response()->json([
            'message' => 'Contact updated successfully',
            'data' => new ContactResource($contact)
        ]);
    }

    /**
     * Delete a contact.
     */
    public function destroy(Contact $contact): JsonResponse
    {
        $contact->delete();

        return response()->json([
            'message' => 'Contact deleted successfully'
        ]);
    }

    /**
     * Get contact statistics.
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total' => Contact::count(),
            'new' => Contact::new()->count(),
            'needs_reply' => Contact::needsReply()->count(),
            'archived' => Contact::archived()->count(),
            'recent' => Contact::recent()->count(),
        ];

        return response()->json([
            'data' => $stats
        ]);
    }
}
