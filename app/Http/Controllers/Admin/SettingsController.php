<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    public function index()
    {
        $dashboardTitle = 'System Settings';
        
        // Load all settings as key => value for the view
        $settings = Setting::getAllCached();

        return view('admin.settings.index', compact('dashboardTitle', 'settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            // General
            'site_name'       => 'nullable|string|max:100',
            'contact_email'   => 'nullable|email|max:255',
            'timezone'        => 'nullable|string|max:50',
            'institution_name'=> 'nullable|string|max:200',
            'site_logo'       => 'nullable|file|mimes:png,jpg,jpeg,svg,webp|max:2048',

            // Quiz Rules
            'default_time_limit'      => 'nullable|integer|min:1|max:300',
            'default_pass_percentage' => 'nullable|integer|min:1|max:100',
            'max_attempts'            => 'nullable|integer|min:1|max:99',

            // Security
            'max_violations' => 'nullable|integer|min:1|max:20',
        ]);

        $tab = $request->input('_tab', 'general');

        // --- General Settings ---
        if ($tab === 'general') {
            $general = [
                'site_name'        => $request->input('site_name', 'QuizMaster v2.0'),
                'contact_email'    => $request->input('contact_email', ''),
                'timezone'         => $request->input('timezone', 'UTC'),
                'institution_name' => $request->input('institution_name', ''),
                'maintenance_mode' => $request->has('maintenance_mode') ? '1' : '0',
            ];

            // Handle logo upload
            if ($request->hasFile('site_logo')) {
                $oldLogo = Setting::get('site_logo');
                if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                    Storage::disk('public')->delete($oldLogo);
                }

                $path = $request->file('site_logo')->store('logos', 'public');
                $general['site_logo'] = $path;
            }

            Setting::setMany($general, 'general');
        }

        // --- Quiz Rules ---
        if ($tab === 'quiz') {
            $quiz = [
                'default_time_limit'      => $request->input('default_time_limit', '30'),
                'default_pass_percentage' => $request->input('default_pass_percentage', '60'),
                'max_attempts'            => $request->input('max_attempts', '1'),
                'shuffle_questions'       => $request->has('shuffle_questions') ? '1' : '0',
                'shuffle_answers'         => $request->has('shuffle_answers') ? '1' : '0',
                'show_result_immediately' => $request->has('show_result_immediately') ? '1' : '0',
                'allow_review'            => $request->has('allow_review') ? '1' : '0',
            ];

            Setting::setMany($quiz, 'quiz');
        }

        // --- Security ---
        if ($tab === 'security') {
            $security = [
                'disable_right_click'      => $request->has('disable_right_click') ? '1' : '0',
                'tab_switch_detection'     => $request->has('tab_switch_detection') ? '1' : '0',
                'enforce_fullscreen'       => $request->has('enforce_fullscreen') ? '1' : '0',
                'max_violations'           => $request->input('max_violations', '3'),
                'auto_submit_on_violation' => $request->has('auto_submit_on_violation') ? '1' : '0',
            ];

            Setting::setMany($security, 'security');
        }

        return redirect()->back()->with('success', 'Settings updated successfully!')->withInput(['_tab' => $tab]);
    }
}
