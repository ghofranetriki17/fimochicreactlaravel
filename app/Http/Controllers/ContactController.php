<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the contacts.
     */
    public function index()
    {
        $contacts = Contact::all()->groupBy('subject');
        return response()->json([
            'status' => 'success',
            'data' => $contacts
        ], 200);
    }

    /**
     * Mark a contact as read.
     */
    public function markAsRead(Contact $contact)
    {
        $contact->update(['is_read' => true]);

        return response()->json([
            'status' => 'success',
            'message' => 'Contact marked as read!'
        ], 200);
    }

    /**
     * Store a newly created contact in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
    ]);

    if ($validated) {
        $contact = Contact::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Your message has been sent successfully!',
            'data' => $contact
        ], 201);
    }

    return response()->json([
        'status' => 'error',
        'message' => 'Validation failed!',
        'errors' => $errors
    ], 400);
}


    /**
     * Display the specified contact.
     */
    public function show(Contact $contact)
    {
        return response()->json([
            'status' => 'success',
            'data' => $contact
        ], 200);
    }

    /**
     * Update the specified contact in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contact  $contact
     */
    public function update(Request $request, Contact $contact)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $contact->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Contact updated successfully!',
            'data' => $contact
        ], 200);
    }

    /**
     * Remove the specified contact from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Contact deleted successfully!'
        ], 200);
    }

    /**
     * Get unread contacts.
     */
    public function getUnreadContacts()
    {
        $unreadContacts = Contact::where('is_read', false)->get();

        return response()->json([
            'status' => 'success',
            'data' => $unreadContacts
        ], 200);
    }
}
