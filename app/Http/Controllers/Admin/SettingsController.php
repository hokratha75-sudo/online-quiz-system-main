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
            $general = [];
            if ($request->has('site_name')) $general['site_name'] = $request->input('site_name');
            if ($request->has('contact_email')) $general['contact_email'] = $request->input('contact_email');
            if ($request->has('timezone')) $general['timezone'] = $request->input('timezone');
            if ($request->has('institution_name')) $general['institution_name'] = $request->input('institution_name');
            
            // Maintenance mode toggle
            if ($request->ajax()) {
                if ($request->has('maintenance_mode')) $general['maintenance_mode'] = $request->input('maintenance_mode') == '1' || $request->input('maintenance_mode') === true ? '1' : '0';
            } else {
                $general['maintenance_mode'] = $request->has('maintenance_mode') ? '1' : '0';
            }

            if (!empty($general)) {
                Setting::setMany($general, 'general');
            }
        }

        // --- Quiz Rules ---
        if ($tab === 'quiz') {
            $quiz = [];
            foreach (['default_time_limit', 'default_pass_percentage', 'max_attempts'] as $field) {
                if ($request->has($field)) $quiz[$field] = $request->input($field);
            }
            
            foreach (['shuffle_questions', 'shuffle_answers', 'show_result_immediately', 'allow_review'] as $toggle) {
                if ($request->ajax()) {
                    if ($request->has($toggle)) $quiz[$toggle] = $request->input($toggle) == '1' || $request->input($toggle) === true ? '1' : '0';
                } else {
                    $quiz[$toggle] = $request->has($toggle) ? '1' : '0';
                }
            }

            if (!empty($quiz)) {
                Setting::setMany($quiz, 'quiz');
            }
        }

        // --- Security ---
        if ($tab === 'security') {
            $security = [];
            if ($request->has('max_violations')) $security['max_violations'] = $request->input('max_violations');
            
            foreach (['disable_right_click', 'tab_switch_detection', 'enforce_fullscreen', 'auto_submit_on_violation'] as $toggle) {
                if ($request->ajax()) {
                    if ($request->has($toggle)) $security[$toggle] = $request->input($toggle) == '1' || $request->input($toggle) === true ? '1' : '0';
                } else {
                    $security[$toggle] = $request->has($toggle) ? '1' : '0';
                }
            }

            if (!empty($security)) {
                Setting::setMany($security, 'security');
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Settings updated successfully!',
                'settings' => Setting::getAllCached()
            ]);
        }

        return redirect()->back()->with('success', 'Settings updated successfully!')->withInput(['_tab' => $tab]);
    }
}
