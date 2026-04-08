<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    /**
     * Get all settings as JSON (for API/POS)
     */
    public function getSettings()
    {
        $settings = Cache::remember('settings_all', 3600, function () {
            return Setting::all()->pluck('value', 'key')->toArray();
        });

        // Add logo URL if exists
        if (isset($settings['store_logo']) && $settings['store_logo']) {
            $settings['store_logo_url'] = asset('storage/' . $settings['store_logo']);
        }

        return response()->json($settings);
    }

    /**
     * Display settings page
     */
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'nullable|string|max:255',
            'company_address' => 'nullable|string',
            'company_city' => 'nullable|string',
            'store_tagline' => 'nullable|string|max:255',
            'store_name' => 'nullable|string|max:255',
            'store_address' => 'nullable|string',
            'store_city' => 'nullable|string',
            'store_phone' => 'nullable|string|max:20',
            'store_email' => 'nullable|email|max:255',
            'receipt_footer' => 'nullable|string',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'currency' => 'nullable|string|max:10',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            $oldLogo = Setting::where('key', 'store_logo')->value('value');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            // Upload new logo
            $logoPath = $request->file('logo')->store('settings', 'public');
            Setting::set('store_logo', $logoPath);
            
            // Clear cache
            Cache::forget('settings_all');
        }

        // Update text settings (including store_tagline)
        $settings = [
            'company_name', 'company_address', 'company_city', 'store_tagline',
            'store_name', 'store_address', 'store_city', 'store_phone', 
            'store_email', 'receipt_footer', 'tax_rate', 'currency'
        ];

        foreach ($settings as $key) {
            if (isset($validated[$key])) {
                Setting::set($key, $validated[$key]);
            }
        }
        
        // Clear cache
        Cache::forget('settings_all');

        return back()->with('success', 'Pengaturan berhasil diupdate!');
    }

    /**
     * Reset logo to default
     */
    public function resetLogo()
    {
        $oldLogo = Setting::where('key', 'store_logo')->value('value');
        if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
            Storage::disk('public')->delete($oldLogo);
        }

        Setting::where('key', 'store_logo')->delete();
        Cache::forget('settings_all');
        Cache::forget('setting_store_logo');

        return back()->with('success', 'Logo berhasil direset ke default!');
    }

    /**
     * Upload logo via AJAX
     */
    public function uploadLogo(Request $request)
    {
        $validated = $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Delete old logo
        $oldLogo = Setting::where('key', 'store_logo')->value('value');
        if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
            Storage::disk('public')->delete($oldLogo);
        }

        // Upload new logo
        $logoPath = $request->file('logo')->store('settings', 'public');
        Setting::set('store_logo', $logoPath);

        // Clear cache
        Cache::forget('settings_all');

        return response()->json([
            'success' => true,
            'logo_url' => asset('storage/' . $logoPath),
            'message' => 'Logo berhasil diupdate!'
        ]);
    }

    /**
     * Backup database
     */
    public function backup()
    {
        $filename = 'vexamart-backup-' . date('Y-m-d-H-i') . '.sql';
        $path = storage_path('app/backups/' . $filename);

        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.host'),
            config('database.connections.mysql.database'),
            $path
        );

        $returnVar = NULL;
        $output = NULL;
        exec($command, $output, $returnVar);

        if ($returnVar === 0 && file_exists($path)) {
            return response()->download($path)->deleteFileAfterSend(true);
        }

        return back()->with('error', 'Gagal membuat backup database');
    }
}