<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'store_address' => 'nullable|string',
            'store_phone' => 'nullable|string|max:20',
            'store_email' => 'nullable|email|max:255',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'currency' => 'required|string|max:10',
            'receipt_header' => 'nullable|string',
            'receipt_footer' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('settings', 'public');
            Setting::set('store_logo', $path);
        }

        // Update text settings
        $settings = [
            'store_name', 'store_address', 'store_phone', 'store_email',
            'tax_rate', 'currency', 'receipt_header', 'receipt_footer'
        ];

        foreach ($settings as $key) {
            if (isset($validated[$key])) {
                Setting::set($key, $validated[$key]);
            }
        }

        return back()->with('success', 'Pengaturan berhasil diupdate!');
    }

    public function backup()
    {
        // Simple backup - in production use spatie/laravel-backup
        $filename = 'vexamart-backup-' . date('Y-m-d-H-i') . '.sql';
        $path = storage_path('app/backups/' . $filename);
        
        // Create backup directory
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        // Execute mysqldump
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