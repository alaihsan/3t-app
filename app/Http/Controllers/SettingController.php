<?php

namespace App\Http\Controllers;

use App\Models\SchoolSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $setting = SchoolSetting::getSettings();
        return view('settings.index', compact('setting'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'school_address' => 'nullable|string|max:500',
            'academic_year' => 'required|string|max:50',
            'semester' => 'required|string|max:50',
            'teacher_takhassus' => 'nullable|string|max:255',
            'teacher_tahsin' => 'nullable|string|max:255',
            'teacher_tahfizh' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $setting = SchoolSetting::getSettings();

        $data = [
            'school_name' => $validated['school_name'],
            'school_address' => $validated['school_address'] ?? null,
            'academic_year' => $validated['academic_year'],
            'semester' => $validated['semester'],
            'teacher_takhassus' => $validated['teacher_takhassus'] ?? 'Ust. Ichsan',
            'teacher_tahsin' => $validated['teacher_tahsin'] ?? 'Ust. Ahmad Fauzi, S.Pd.I',
            'teacher_tahfizh' => $validated['teacher_tahfizh'] ?? 'Ust. Ichsan',
            'teacher_name' => $validated['teacher_tahfizh'] ?? $setting->teacher_name,
        ];

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($setting->logo_path && file_exists(public_path($setting->logo_path))) {
                @unlink(public_path($setting->logo_path));
            }

            $file = $request->file('logo');
            $fileName = 'school_logo_' . time() . '.' . $file->getClientOriginalExtension();
            if (!is_dir(public_path('uploads'))) {
                mkdir(public_path('uploads'), 0755, true);
            }
            $file->move(public_path('uploads'), $fileName);
            $data['logo_path'] = 'uploads/' . $fileName;
        }

        $setting->update($data);

        return redirect()->route('settings.index')->with('success', 'Pengaturan sekolah dan kop laporan berhasil disimpan!');
    }
}
