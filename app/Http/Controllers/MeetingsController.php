<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MeetingHasMember;
use App\Models\Meeting;
use App\Models\Office;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as HttpRequest; // Alias the Request class for validation
use Illuminate\Support\Facades\Validator; // Validator facade
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class MeetingsController extends Controller
{
    public function index(): Response
    {
        if (Auth::user() && Auth::user()->hasRole('admin')) {
            return Inertia::render('Meetings/Index', [
                'filters' => Request::all('search', 'date', 'office_id'),
                'offices' => Office::all(),
                'meetings' => Auth::user()->office->meetings()->orderByDate()
                    ->filter(Request::only('search', 'date', 'office_id'))
                    ->paginate(10)
                    ->withQueryString()
                    ->through(fn ($meeting) => [
                        'id' => $meeting->id,
                        'title' => $meeting->title,
                        'office_id' => $meeting->office_id,
                        'office' => $meeting->office->name,
                        'date' => $meeting->date,
                        'topic' => $meeting->topic,
                        'attachement' => $meeting->attachement_path,
                    ]),
            ]);
        } else {
            return Inertia::render('Errors/Unauthorized');
        }

    }

    public function create(): Response
    {
        if (Auth::user() && Auth::user()->hasRole('admin')) {
            return Inertia::render('Meetings/Create', [
                'offices' => Office::all(),
            ]);
        } else {
            return Inertia::render('Errors/Unauthorized');
        }
    }

    public function store(): RedirectResponse
    {
        // Manually access request data using the Request facade
        $data = Request::all();

        // Use Validator facade to validate the data
        $validator = Validator::make($data, [
            'title' => ['required', 'max:50'],
            'attachment_path' => ['nullable', 'max:50'],
            'office_id' => ['required', 'exists:offices,id'],
            'topic' => ['nullable', 'max:50'],
            'date' => ['nullable', 'max:50'],
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        // Create the member using validated data
        Meeting::create($validator->validated());

        // Redirect with a success message
        return Redirect::route('meetings')->with('success', 'Meeting created.');
    }

    public function edit(Meeting $meeting): Response
    {
        $membersQuery = $meeting->members();
        // Paginate the results
        $members = $membersQuery->paginate(10)->withQueryString()->through(fn ($member) => [
            'id' => $member->id,
            'name' => $member->name,
            'phone' => $member->phone,
            'locality' => $member->locality,
            'deleted_at' => $member->deleted_at,
            'email' => $member->email,
            'constituency' => $member->constituency,
        ]);

        if (Auth::user() && Auth::user()->hasRole('admin')) {
            return Inertia::render('Meetings/Edit', [
                'meeting' => [
                    'id' => $meeting->id,
                    'title' => $meeting->title,
                    'date' => $meeting->date,
                    'office_id' => $meeting->office_id,
                    'topic' => $meeting->topic,
                    'attachment_path' => $meeting->attachment_path,
                ],
                'offices' => Office::all(),
                'members' => $members,
            ]);
        } else {
            return Inertia::render('Errors/Unauthorized');
        }

    }

    public function update(Meeting $meeting): RedirectResponse
    {
        $meeting->update(
            Request::validate([
                'title' => ['required', 'max:50'],
                'attachment_path' => ['nullable', 'max:50'],
                'office_id' => ['required', 'exists:offices,id'],
                'topic' => ['nullable', 'max:50'],
                'date' => ['nullable', 'max:50'],
            ])
        );

        return Redirect::back()->with('success', 'Meeting updated.');
    }

    public function destroy(Meeting $meeting): RedirectResponse
    {
        $meeting->delete();

        return Redirect::route('meetings')->with('success', 'Meeting deleted.');
    }

    public function restore(Meeting $meeting): RedirectResponse
    {
        $meeting->restore();

        return Redirect::back()->with('success', 'Meeting restored.');
    }

    public function attendance(Meeting $meeting): Response
    {
        // Transform participants to get id, name, email, and locality
        $participants = $meeting->members()->get()->transform(fn($participant) => [
            'id' => $participant->id,
            'name' => $participant->name,
            'email' => $participant->email,
            'locality' => $participant->locality,
        ]);

        $meeting = [
            'id' => $meeting->id,
            'title' => $meeting->title,
            'office_id' => $meeting->office_id,
            'office' => $meeting->office->name,
            'date' => $meeting->date,
            'topic' => $meeting->topic,
            'attachement' => $meeting->attachement_path,
        ];
        // Transform participants to get id, name, email, and locality

        return Inertia::render('Meetings/Attendance', [
            'meeting' => $meeting,
            'members' => Auth::user()->office->members()->orderByName()
                ->get()
                ->transform(fn ($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'locality' => $user->locality,
                ]),
            'participants' => $participants, // Include transformed participants
        ]);
    }

    public function addToMeeting(HttpRequest $request) {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'memberId' => 'nullable|integer', // Allow memberId to be nullable
            'memberFirstName' => 'nullable|string|max:255',
            'memberLastName' => 'nullable|string|max:255',
            'memberEmail' => 'nullable|email|max:255',
            'memberPhone' => 'nullable|string|max:20',
            'memberAddress' => 'nullable|string|max:255',
            'memberConstituency' => 'nullable|string|max:255',
            'meetingId' => 'nullable|integer',
        ]);

        // Access the data
        $memberId = $validatedData['memberId'];
        $memberFirstName = $validatedData['memberFirstName'];
        $memberLastName = $validatedData['memberLastName'];
        $memberEmail = $validatedData['memberEmail'];
        $memberPhone = $validatedData['memberPhone'];
        $memberAddress = $validatedData['memberAddress'];
        $memberConstituency = $validatedData['memberConstituency'];
        $meetingId = $validatedData['meetingId'];

        // Create or find the member
        if (is_null($memberId)) {
            // Create new member
            $member = new Member;
            $member->first_name = $memberFirstName;
            $member->last_name = $memberLastName;
            $member->email = $memberEmail;
            $member->phone = $memberPhone;
            $member->address = $memberAddress;
            $member->constituency = $memberConstituency;
            $member->office_id = 1;
            $member->save();

            $memberId = $member->id; // Get the new member ID
        } else {
            // Find existing member
            $member = Member::findOrFail($memberId); // Use findOrFail to handle not found
        }

        // Link the member to the meeting
        $meetingHasMember = new MeetingHasMember;
        $meetingHasMember->meeting_id = $meetingId;
        $meetingHasMember->member_id = $memberId;
        $meetingHasMember->save();

        // Return a response
        return response()->json(['message' => 'Registered to meeting successfully.']);
    }

}
