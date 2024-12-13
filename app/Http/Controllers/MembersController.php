<?php

namespace App\Http\Controllers;

use App\Models\MeetingHasMember;
use App\Models\Member;
use App\Models\Office;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator; // Validator facade
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class MembersController extends Controller
{
    public function index(): Response
    {

        if (Auth::user() && Auth::user()->hasRole('admin')) {
            return Inertia::render('Members/Index', [
                'filters' => Request::all('search', 'constituency'),
                'members' => Auth::user()->office->members()->orderByName()
                    ->filter(Request::only('search', 'constituency'))
                    ->paginate(10)
                    ->withQueryString()
                    ->through(fn ($member) => [
                        'id' => $member->id,
                        'name' => $member->name,
                        'phone' => $member->phone,
                        'address' => $member->address,
                        'deleted_at' => $member->deleted_at,
                        'email' => $member->email,
                        'constituency' => $member->constituency,
                    ]),
            ]);
        } else {
            return Inertia::render('Errors/Unauthorized');
        }


    }

    public function create(): Response
    {

        if (Auth::user() && Auth::user()->hasRole('admin')) {
            return Inertia::render('Members/Create', [
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
            'first_name' => ['nullable', 'max:50'],
            'last_name' => ['nullable', 'max:50'],
            'office_id' => ['required', 'exists:offices,id'],
            'email' => ['nullable', 'max:50', 'email'],
            'phone' => ['nullable', 'max:50'],
            'address' => ['nullable', 'max:250'],
            'constituency' => ['nullable', 'max:50'],
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        // Create the member using validated data
        Member::create($validator->validated());

        // Redirect with a success message
        return Redirect::route('members')->with('success', 'Member created.');
    }

    public function edit(Member $member): Response
    {
        if (Auth::user() && Auth::user()->hasRole('admin')) {
            return Inertia::render('Members/Edit', [
                'member' => [
                    'id' => $member->id,
                    'first_name' => $member->first_name,
                    'last_name' => $member->last_name,
                    'office_id' => $member->office_id,
                    'email' => $member->email,
                    'phone' => $member->phone,
                    'constituency' => $member->constituency,
                    'address' => $member->address,
                ],
                'offices' => Office::all(),
                'filters' => Request::all('search', 'date', 'office_id'),
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
                'userMeetings' => MeetingHasMember::where('member_id', $member->id)->pluck('meeting_id')->toArray(),
            ]);

        } else {
            return Inertia::render('Errors/Unauthorized');
        }


    }

    public function update(Member $member): RedirectResponse
    {
        $member->update(
            Request::validate([
                'first_name' => ['required', 'max:50'],
                'last_name' => ['required', 'max:50'],
                'office_id' => ['required', 'exists:offices,id'],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:250'],
                'constituency' => ['required', 'max:50'],
            ])
        );

        return Redirect::back()->with('success', 'Member updated.');
    }

    public function destroy(Member $member): RedirectResponse
    {
        $member->delete();

        return Redirect::route('members')->with('success', 'Member deleted.');
    }

    public function restore(Contact $contact): RedirectResponse
    {
        $contact->restore();

        return Redirect::back()->with('success', 'Contact restored.');
    }
}
