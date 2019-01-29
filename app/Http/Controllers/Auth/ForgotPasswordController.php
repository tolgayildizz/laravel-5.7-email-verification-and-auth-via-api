<?php
namespace App\Http\Controllers\Auth;


use App\Http\Controllers\ResponseController;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\Mail\ForgotPassword;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ForgotPasswordEmailRequest;

class ForgotPasswordController extends ResponseController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
     */
    use SendsPasswordResetEmails;

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(ForgotPasswordEmailRequest $request)
    {
        $email = $request->email;
        $resetUrlForReplace = urldecode($request->resetUrl);

        $user = User::where('email', $email)->first();

        if (!$user) {
            return $this->sendError(trans('passwords.user'), 404);
        }

        $token = $this->broker()->createToken($user);
        $resetUrl = str_replace(['<token>', '<email>'], [$token, $email], $resetUrlForReplace);
        Mail::to([
            'email' => $email
        ])->send(new ForgotPassword($resetUrl));

        return $this->sendResponse(NULL, trans('passwords.sent'));
    }
}
