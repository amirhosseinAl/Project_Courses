<?php

namespace Modules\User\App\Http\Controllers;

use Modules\User\App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller;
use Modules\User\app\Models\Log;




class LoginController extends Controller
{
    public function LoginForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:5'
        ]);
        
        if (!($user = User::select('id', 'email', 'status', 'password')
            ->where('email', $request->email)
            ->first())) {
            return [
                'success' => false,
                'msg' => __('user::validation.email_notFound')
            ];
        }

        if ($user->status == User::TYPE_NOTACTIVE) {
            return [
                'success' => true,
                'msg' => __('user::validation.account_notActive')
            ];
        }

        if (!Hash::check($request->password, $user->password)) {
            return [
                'success' => false,
                'msg' => __('user::validation.wrong_password')
            ];
        }
        $token = $user->createToken('login-token')->plainTextToken;

        $log = new Log();
        $log->Log(
            $user->id,
            'loginSuccess',
            null,
            []
        );
        return [
            'success' => true,
            'msg' => __('user::validation.login_success'),
            'token' => $token,
        ];
    }
}
