<?php

namespace App\Http\Controllers;

use App\Subscriptions;
use App\Mail\SubscribeEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * Class SubsController
 * @package App\Http\Controllers
 */
class SubsController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function subscribe(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:subscriptions',
        ]);
        $sub = Subscriptions::add($request->get('email'));
        $sub->generateToken();
        Mail::to($sub)->send(new SubscribeEmail($sub));
        return redirect()->back()->with('status', 'Проверьте вашу почту');
    }

    /**
     * @param string $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(string $token)
    {
        $sub = Subscriptions::where('token', $token)->firstOrFail();
        $sub->token = null;
        $sub->save();
        return redirect()->back()->with('status', 'Ваша почта успешно подтверждена. Свежие новости уже мчат к вам :)');
    }
}
