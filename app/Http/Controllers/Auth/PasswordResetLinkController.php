<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PasswordResetLinkRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Status messages mapped to human readable copy.
     *
     * @var array<string, string>
     */
    private const MESSAGES = [
        Password::RESET_LINK_SENT => 'We have emailed your password reset link.',
        Password::RESET_THROTTLED => 'Please wait before retrying.',
        Password::INVALID_USER => 'We can\'t find a user with that email address.',
    ];

    /**
     * Display the forgot password form.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Send a password reset link to the given email address.
     *
     * @throws ValidationException
     */
    public function store(PasswordResetLinkRequest $request): RedirectResponse
    {
        $status = Password::sendResetLink($request->only('email'));

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => self::MESSAGES[$status] ?? 'Unable to send a password reset link.',
            ]);
        }

        return back()->with('status', self::MESSAGES[$status]);
    }
}
