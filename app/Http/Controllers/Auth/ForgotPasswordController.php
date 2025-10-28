<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\SMS;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use function PHPUnit\Framework\assertInstanceOf;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    */

    use SendsPasswordResetEmails;

    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        $this->sendOtp($request);

        return redirect()->route('password.otp', encrypt($request->input('phone')));
    }

    public function showOtpForm($phoneNumber)
    {
        return view('auth.passwords.otp', compact('phoneNumber'));
    }

    /**
     * Validate the email for the given request.
     */
    protected function validateEmail(Request $request)
    {
        $request->validate(['phone' => 'required|numeric']);
    }

    private function sendOtp(Request $request)
    {
        $phone = $request->input('phone');
        $user = User::where('phone', $phone)->first();

        if (is_null($user)) {
            throw ValidationException::withMessages([
                'phone' => [trans("Could not find a user with that phone number")],
            ]);
        }

        $otp = rand(1000, 9999);

        DB::table('password_resets')->insert([
            'phone' => $phone,
            'token' => $otp,
            'created_at' => now(),
        ]);

        $message = sprintf("#\nপাসওয়ার্ড রিসেট\nOTP: %d\n#", $otp);

        (new SMS($message))->send($phone);
    }

    public function verifyOtp(Request $request, $identifier)
    {
        $this->validate($request, [
            'otp' => 'required|numeric|digits:4',
        ]);

        $phone = decrypt($identifier);
        $user = User::where('phone', $phone)->first();

        if (is_null($user)) {
            throw ValidationException::withMessages([
                'phone' => [trans("Could not find a user with that phone number")],
            ]);
        }

        $otp = $request->input('otp');

        $reset = DB::table('password_resets')->where(['phone' => $phone, 'token' => $otp])->first();

        if (is_null($reset)) {
            throw ValidationException::withMessages([
                'otp' => [trans("OTP did not match")],
            ]);
        }

        $token = md5($otp);
        DB::table('password_resets')->where(['phone' => $phone, 'token' => $otp])->update(['token' => md5($otp)]);

        return redirect()->route('password.reset', ['token' => $token, 'phone' => $phone]);
    }
}
