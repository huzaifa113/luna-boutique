<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Models\ContactSubmission;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(ContactFormRequest $request)
    {
        // Save to database
        ContactSubmission::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'is_read' => false,
        ]);

        // Send email notification
        try {
            Mail::raw(
                "Name: {$request->name}\nEmail: {$request->email}\nSubject: {$request->subject}\n\nMessage:\n{$request->message}",
                function ($message) use ($request) {
                    $message->to(config('mail.from.address'))
                        ->subject('Contact form: ' . $request->subject)
                        ->replyTo($request->email, $request->name);
                }
            );
        } catch (\Exception $e) {
            // Email might fail, but DB save succeeded
        }

        return back()->with('success', 'Thank you! Your message has been sent.');
    }
}
