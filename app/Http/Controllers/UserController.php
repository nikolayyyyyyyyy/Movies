<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->has('email') && !empty($request->email)) {
            $users = User::with('role')->where('email', '=', $request->email)->paginate(8);
        } else {
            $users = User::with('role')->whereNot('id', Auth::user()->id)->paginate(8);
        }

        $roles = Role::all();

        return Inertia::render('User/Index', [
            'users' => $users,
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        if (User::withTrashed()->where('email', $request->email)->exists()) {
            $user = User::withTrashed()->where('email', $request->email)->first();
            $user->restore();
            $user->update($request->validated());

            return redirect()->back();
        }

        User::create($request->validated());

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return Inertia::render('User/Show', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->back();
    }
}
