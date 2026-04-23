<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
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
        Schema::defaultStringLength(191);

        // Force HTTPS when using Ngrok or other secure proxies
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        \Illuminate\Support\Facades\Event::listen(function (\Illuminate\Auth\Events\Failed $event) {
            if (!Schema::hasTable('users')) {
                return;
            }
            
            // Find all admins
            $admins = \App\Models\User::where('role_id', 1)->get();
            if ($admins->isNotEmpty()) {
                $email = $event->credentials['email'] ?? $event->credentials['username'] ?? 'Unknown User';
                \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\QuizActivityNotification([
                    'type' => 'danger',
                    'title' => 'Security Alert: Failed Login',
                    'message' => "Unauthorized access attempt for credentials: {$email}.",
                    'icon' => 'fas fa-shield-alt',
                    'url' => '#' // They can view full logs in a real analytics module later
                ]));
            }
        });

        // Track Login History
        \Illuminate\Support\Facades\Event::listen(function (\Illuminate\Auth\Events\Login $event) {
            if (!Schema::hasTable('login_histories')) {
                return;
            }
            
            \App\Models\LoginHistory::create([
                'user_id' => $event->user->id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'login_at' => now(),
            ]);
        });
    }
}
