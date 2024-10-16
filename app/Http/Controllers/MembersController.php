<?php

namespace App\Http\Controllers;

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
                    'locality' => $member->locality,
                    'deleted_at' => $member->deleted_at,
                    'email' => $member->email,
                    'constituency' => $member->constituency,
                ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Members/Create', [
            'offices' => Office::all(),
        ]);
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
            'locality' => ['nullable', 'max:150'],
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
        return Inertia::render('Members/Edit', [
            'member' => [
                'id' => $member->id,
                'first_name' => $member->first_name,
                'last_name' => $member->last_name,
                'office_id' => $member->office_id,
                'email' => $member->email,
                'phone' => $member->phone,
                'constituency' => $member->constituency,
                'locality' => $member->locality,
            ],
            'offices' => Office::all(),
        ]);
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
                'locality' => ['nullable', 'max:150'],
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
