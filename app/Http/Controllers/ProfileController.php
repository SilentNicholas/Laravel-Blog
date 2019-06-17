<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * Class ProfileController
 * @package App\Http\Controllers
 */
class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        return view('pages.profile', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users' )->ignore(Auth::user()->id),
            ],
            'avatar' => 'nullable|image',
        ]);
        $user = Auth::user();
        $user->update($request->all());
        $user->uploadAvatar($request->file('avatar'));
        $user->generatePassword($request->get('password'));
        return redirect()->back()->with('status', 'Ваш профиль был успешно обновлен');
    }

}
