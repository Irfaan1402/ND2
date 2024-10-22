<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MeetingHasMember;
use App\Models\Meeting;
use App\Models\Post;
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

class PostsController extends Controller
{
    public function index(): Response
    {

        return Inertia::render('Posts/Index', [
            'filters' => Request::all('search', 'date', 'office_id'),
            'offices' => Office::all(),
            'posts' => Post::orderByDate()
                ->filter(Request::only('search'))
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
    }

    public function create(): Response
    {
        return Inertia::render('Meetings/Create', [
            'offices' => Office::all(),
        ]);
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


}
