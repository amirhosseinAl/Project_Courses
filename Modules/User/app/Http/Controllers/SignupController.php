<?php

namespace Modules\User\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\User\App\Models\User;
use Modules\User\app\Emails\OtpMail;
use Modules\User\app\Models\Log;
use Modules\User\app\Models\Verify;

class SignupController extends Controller
{
    public function SignupForm(Request $request)
    {
        $request->validate([
            'firstname' => 'required|min:3|max:200',
            'lastname' => 'required|min:3|max:200',
            'email' => 'required|email|min:5|max:100',
            'password' => 'required|confirmed|min:5|max:50',
            'phone' => 'required|digits:11'
        ]);
        $email = $request->email;
        $emailHash = md5($email);

        if (Verify::where('id', $emailHash)
            ->where('expire_at', '>', now())
            ->first()
        ) {
            return [
                'success' => false,
                'msg' => __('user::validation.resend_error')
            ];
        }

        if (
            $user =
            User::select('id', 'email', 'status')
            ->where('email', $email)
            ->first()
        ) {
            if ($user->status == User::TYPE_ACTIVE) {
                return [
                    'success' => true,
                    'msg' => __('user::validation.exists_user')
                ];
            }
            $verifyCode = rand(1000, 9999);
            Mail::to($email)
                ->send(new OtpMail($verifyCode));

            $expireTime = now()->addMinutes(5);

            Verify::where('id', $emailHash)
                ->update([
                    'code' => $verifyCode,
                    'expire_at' => $expireTime
                ]);
            return [
                'success' => true,
                'msg' => __('user::validation.signUp_again')
            ];
        }
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ]);
        $verifyCode = rand(1000, 9999);
        Mail::to($email)
            ->send(new OtpMail($verifyCode));

        $emailHash = md5($email);
        $expireTime = now()->addMinutes(5);
        Verify::create([
            'id' => $emailHash,
            'code' => $verifyCode,
            'expire_at' => $expireTime,
        ]);
        $log = new Log();
        $log->Log(
            $user->id,
            'signupSuccess',
            null,
            []
        );
        return [
            'success' => true,
            'msg' => __('user::validation.send_code')
        ];
    }

    public function SignupVerify(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:4',
            'email' => 'required|email|exists:users,email',
        ]);
        $emailHash = md5($request->email);
        $code   = $request->code;
        $email = $request->email;
        $verify = Verify::select('id', 'code', 'expire_at')
            ->where([
                'id' => $emailHash,
                'code' => $code,
            ])
            ->where('expire_at', '>', now())
            ->first();

        if ($verify) {
            User::where('email', $email)
                ->update(['status' => User::TYPE_ACTIVE]);

            $verify->delete();

            $user = User::select('id', 'email')
                ->where('email', $email)
                ->first();

            $log = new Log();
            $log->Log(
                $user->id,
                'signupVerifySuccess',
                null,
                [
                    'email'       => $email,
                    'verify_code' => $code,
                ]
            );
            return [
                'success' => true,
                'msg' => __('user::validation.signUp_success')
            ];
        } else {
            return [
                'success' => false,
                'msg' => __('user::validation.code_expired')
            ];
        }
    }

    public function Resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        $email = $request->email;
        $emailHash = md5($email);
        $expireTime = now()->addMinutes(5);
        if (!($user = User::select('id', 'status')->where('email', $email)->first())) {
            return [
                'success' => false,
                'msg' => __('user::validation.email_notFound')
            ];
        }
        if ($user->status == User::TYPE_ACTIVE) {
            return [
                'success' => false,
                'msg' => __('user::validation.exists_user')
            ];
        }
        $verify = Verify::select('id', 'code', 'expire_at')
            ->where('id', $emailHash)
            ->first();

        if ($verify->expire_at > now()) {
            return [
                'success' => false,
                'msg' => __('user::validation.resend_error')
            ];
        }
        $verifyCode = rand(1000, 9999);
        Mail::to($email)
            ->send(new OtpMail($verifyCode));

        if ($verify) {
            $verify->code = $verifyCode;
            $verify->expire_at = $expireTime;
            $verify->save();
        }
        return [
            'success' => true,
            'msg' => __('user::validation.resend_success')
        ];
    }
}
