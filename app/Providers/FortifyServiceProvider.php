<?php

namespace App\Providers;

use App\Http\Responses\LoginResponse;
use App\Http\Responses\PasswordResetResponse;
use App\Http\Responses\SuccessfulPasswordResetLinkRequestResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\PasswordResetResponse as PasswordResetResponseContract;
use Laravel\Fortify\Contracts\SuccessfulPasswordResetLinkRequestResponse as SuccessfulPasswordResetLinkRequestResponseContract;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Inertia\Inertia;
use App\Models\User;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::loginView(function(){
            return Inertia::render('Auth/Login');
        });

        RateLimiter::for('login', function (Request $request) {
            $user=User::where('username',$request->username)->first();
            if ($user) {
                if ($user->active==1) {
                    $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

                    return Limit::perMinute(5)->by($throttleKey);
                }
                else{
                    $message = 'Cuenta desactivada';
                    return redirect()->route('login')
                        ->with('status',$message)
                        ->withErrors(['username' => 'Cuenta desactivada.'])
                    ;
                }
            }
            
        });

        Fortify::resetPasswordView(function ($request) {
            $token = request()->route('token');

            return Inertia::render('Auth/Reset', ['token' => $token]);
        });

        Fortify::requestPasswordResetLinkView(function () {
            return Inertia::render('Auth/Recovery');
        });

        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
        $this->app->singleton(PasswordResetResponseContract::class, PasswordResetResponse::class);
        $this->app->singleton(SuccessfulPasswordResetLinkRequestResponseContract::class, SuccessfulPasswordResetLinkRequestResponse::class);
    }
}
