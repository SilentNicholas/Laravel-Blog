<?php

namespace App\Http\Controllers\Admin;

use App\Subscriptions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class SubscribesController
 * @package App\Http\Controllers\Admin
 */
class SubscribesController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $subs = Subscriptions::where('token', null)->get();
        return view('admin.subscribes.index', compact('subs'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.subscribes.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:subscriptions',
        ]);
        Subscriptions::add($request->get('email'));
        return redirect()->route('subscribers.index');
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $id)
    {
        $sub = Subscriptions::find($id);
        return view('admin.subscribes.edit', compact('sub'));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        $subs = Subscriptions::find($id);
        $subs->edit($request->get('email'));
        return redirect()->route('subscribers.index');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        $sub = Subscriptions::find($id);
        $sub->delete();
        return redirect()->route('subscribers.index');
    }
}
