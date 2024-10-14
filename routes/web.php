<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\MeetingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\OrganizationsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Dashboard

Route::get('/', [MeetingsCOntroller::class, 'index'])
    ->name('meetings')
    ->middleware('auth');

// Users

Route::get('users', [UsersController::class, 'index'])
    ->name('users')
    ->middleware('auth');

Route::get('users/create', [UsersController::class, 'create'])
    ->name('users.create')
    ->middleware('auth');

Route::post('users', [UsersController::class, 'store'])
    ->name('users.store')
    ->middleware('auth');

Route::get('users/{user}/edit', [UsersController::class, 'edit'])
    ->name('users.edit')
    ->middleware('auth');

Route::put('users/{user}', [UsersController::class, 'update'])
    ->name('users.update')
    ->middleware('auth');

Route::delete('users/{user}', [UsersController::class, 'destroy'])
    ->name('users.destroy')
    ->middleware('auth');

Route::put('users/{user}/restore', [UsersController::class, 'restore'])
    ->name('users.restore')
    ->middleware('auth');

// Organizations

Route::get('organizations', [OrganizationsController::class, 'index'])
    ->name('organizations')
    ->middleware('auth');

Route::get('organizations/create', [OrganizationsController::class, 'create'])
    ->name('organizations.create')
    ->middleware('auth');

Route::post('organizations', [OrganizationsController::class, 'store'])
    ->name('organizations.store')
    ->middleware('auth');

Route::get('organizations/{organization}/edit', [OrganizationsController::class, 'edit'])
    ->name('organizations.edit')
    ->middleware('auth');

Route::put('organizations/{organization}', [OrganizationsController::class, 'update'])
    ->name('organizations.update')
    ->middleware('auth');

Route::delete('organizations/{organization}', [OrganizationsController::class, 'destroy'])
    ->name('organizations.destroy')
    ->middleware('auth');

Route::put('organizations/{organization}/restore', [OrganizationsController::class, 'restore'])
    ->name('organizations.restore')
    ->middleware('auth');

// members

Route::get('members', [MembersController::class, 'index'])
    ->name('members')
    ->middleware('auth');

Route::get('members/create', [MembersController::class, 'create'])
    ->name('members.create')
    ->middleware('auth');

Route::post('members', [MembersController::class, 'store'])
    ->name('members.store')
    ->middleware('auth');

Route::get('members/{member}/edit', [MembersController::class, 'edit'])
    ->name('members.edit')
    ->middleware('auth');

Route::put('members/{member}', [MembersController::class, 'update'])
    ->name('members.update')
    ->middleware('auth');

Route::delete('members/{member}', [MembersController::class, 'destroy'])
    ->name('members.destroy')
    ->middleware('auth');

Route::put('members/{member}/restore', [MembersController::class, 'restore'])
    ->name('members.restore')
    ->middleware('auth');

// Meetings
Route::get('meetings', [MeetingsController::class, 'index'])
    ->name('meetings')
    ->middleware('auth');

Route::get('meetings/create', [MeetingsController::class, 'create'])
    ->name('meetings.create')
    ->middleware('auth');

Route::post('meetings', [MeetingsController::class, 'store'])
    ->name('meetings.store')
    ->middleware('auth');

Route::get('meetings/{meeting}/edit', [MeetingsController::class, 'edit'])
    ->name('meetings.edit')
    ->middleware('auth');

// Reports

Route::get('reports', [ReportsController::class, 'index'])
    ->name('reports')
    ->middleware('auth');

// Images

Route::get('/img/{path}', [ImagesController::class, 'show'])
    ->where('path', '.*')
    ->name('image');

Route::get('attendance/{meeting}', [MeetingsController::class, 'attendance'])
    ->name('attendance')
    ->middleware('auth');

Route::post('/addToMeeting', [MeetingsController::class, 'addToMeeting'])
    ->name('addToMeeting')
    ->middleware('auth');
