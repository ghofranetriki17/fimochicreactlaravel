<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the contacts grouped by subject.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $contacts = Contact::all()->groupBy('subject');
        return response()->json(['contacts' => $contacts], 200);
    }

    /**
     * Mark a contact as read.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Contact $contact)
    {
        $contact->update(['is_read' => true]);
        return response()->json(['message' => 'Contact marked as read successfully!'], 200);
    }

    /**
     * Store a newly created contact in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
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

        $contact = Contact::create($validated);

        return response()->json(['message' => 'Your message has been sent successfully!', 'contact' => $contact], 201);
    }

    /**
     * Display the specified contact.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Contact $contact)
    {
        return response()->json(['contact' => $contact], 200);
    }

    /**
     * Update the specified contact in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $contact->update($validated);

        return response()->json(['message' => 'Contact updated successfully!', 'contact' => $contact], 200);
    }

    /**
     * Remove the specified contact from storage.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response()->json(['message' => 'Contact deleted successfully!'], 200);
    }

    /**
     * Get unread contacts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnreadContacts()
    {
        $unreadContacts = Contact::where('is_read', false)->get();
        return response()->json(['unreadContacts' => $unreadContacts], 200);
    }
}
