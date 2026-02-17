<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check())
            return redirect()->route('dashboard');

        return view('Auth.login');
    }

    private function isUnsafePassword($password, $username)
    {
        $password = strtolower($password);
        $username = strtolower($username);

        if ($password === $username) {
            return true;
        }

        if (str_contains($password, $username)) {
            return true;
        }

        similar_text($password, $username, $percent);

        return $percent > 70;
    }

    public function loginPost(Request $request)
    {
        $credentials = $request->validate([
            'password' => 'required',
            'username' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            $plainPass = $request->password;
            $username = $user->username;


            if ($this->isUnsafePassword($plainPass, $username)) {
                $user->update(['unsafe_password' => true]);
                return redirect()->route('changePassword')->with('warning', 'رمز عبور شما نا امن است؛ باید تغییر کند');
            }
            return redirect()->route('dashboard')->with('succes', "خوش آمدید");
        } else {
            return redirect()->back()->with('error', 'کاربری با این مشخصات وجود ندارد')->withInput();
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->regenerate();
        $request->session()->invalidate();

        return redirect()->route('login')->with('success', 'خروج موفقیت آمیز بود');
    }

    public function forgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'کاربری با این ایمیل یافت نشد'
        ]);

        $check = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if ($check) {
            if ($check->created_at > now()->subMinutes(3)) {
                return redirect()->route('forgetPassword')->with('error', 'ایمیل در سه دقیقه گذشته برای شما ارسال شده است. لطفا ایمیل خود را با دقت بررسی کنید (پوشه spam (هرزنامه) فراموش نشود.)');
            } else {
                DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            }
        }

        $token = str()->random(64);

        $insert = DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        if ($insert) {
            // Mail::send('Auth.emailForToken', ['token' => $token], function ($message) use ($request) {
            //     $message->to($request->email);
            //     $message->subject('درخواست فراموشی رمز عبور');
            // });
            return redirect()->route('forgetPassword')->with('success', 'کد ارسال شد. لطفا ایمیل خود را بررسی کنید');
        } else
            return redirect()->route('forgetPassword')->with('error', 'خطا. دوباره سعی کنید');
    }

    public function resetPassword($token)
    {
        return view('Auth.reset-password', compact('token'));
    }

    public function resetPasswordPost(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:5|confirmed',
        ]);

        $password_reset = DB::table('password_reset_tokens')->where('token', $request->token)->first();

        if (!$password_reset)
            return redirect()->route('forgetPassword')->with('error', 'اطلاعات وارد شده اشتباه است. مجددا روی لینک تغییر کلمه عبور در ایمیل کلیک کنید.');

        $user = User::where('email', $password_reset->email)->update([
            'password' => Hash::make($request->password)
        ]);

        $password_reset = DB::table('password_reset_tokens')->where('token', $request->token)->delete();

        return redirect()->route('login')->with('success', 'کلمه عبور با موفقیت تغییر کرد');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'previous_password' => 'required',
            'new_password' => 'required|min:5|confirmed'
        ], []);

        $user = Auth::user();
        if (!Hash::check($request->previous_password, $user->password)) {
            return redirect()->back()->withErrors([
                'previous_password' => 'کلمه عبور قبلی صحیح نیست'
            ]);
        }

        if ($this->isUnsafePassword($request->new_password, $user->username)) {
            return redirect()->back()->withErrors([
                'new_password' => 'کلمه عبور جدید امن نیست. رمز دیگری را امتحان کنید'
            ]);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
            'unsafe_password' => false,
        ]);
        Auth::logout();
        return redirect()->route('login')->with('success', 'کلمه عبور با موفقیت تغییر کرد. دوباره وارد شوید');

    }
}
