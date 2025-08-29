<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function index()
    {
        $item = SystemSetting::first();
        $position = [
            'prefix' => 'Prefix',
            'suffix' => 'Suffix',
        ];
        $separator = [
            '.',
            ',',
        ];
        return view('pages.system-setting.index', [
            'title' => 'Pengaturan Sistem',
            'menu' => 'settings',
            'item' => $item,
            'position' => $position,
            'separator' => $separator,
        ]);
    }

    public function store(Request $request) {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'company_name' => 'nullable|string|max:50',
                'company_logo' => 'nullable|file|image:png,jpg,webp',
                'company_email' => 'nullable|email',
                'company_address' => 'nullable|string',
                'company_phone' => 'nullable|string|max:20',
                'currency_symbol' => 'nullable|string|max:10',
                'currency_code' => 'nullable|string|max:20',
                'currency_position_default' => 'nullable|in:prefix,suffix',
                'decimal_separator' => 'nullable',
                'thousand_separator' => 'nullable',
                'notification_email' => 'nullable|email|max:50',
                'language' => 'nullable|string|max:100',
            ]);

            $data = $request->all();

            $item = SystemSetting::first();
            if (!$item) {
                $item = new SystemSetting();
            }
            $item->company_name = $data['company_name'] ?? null;
            $item->company_logo = $data['company_logo'] ?? null;
            $item->company_email = $data['company_email'] ?? null;
            $item->company_address = $data['company_address'] ?? null;
            $item->company_phone = $data['company_phone'] ?? null;
            $item->company_code = $data['company_code'] ?? null;
            $item->currency_symbol = $data['currency_symbol'] ?? null;
            $item->currency_position_default = $data['currency_position_default'] ?? null;
            $item->decimal_separator = $data['decimal_separator'] ?? null;
            $item->thousand_separator = $data['thousand_separator'] ?? null;
            $item->notification_email = $data['notification_email'] ?? null;
            $item->language = $data['language'] ?? null;
            $item->save();

            Cache::forget('system_settings');

            DB::commit();

            return redirect()->route('pengaturan/sistem.index')->with('success', 'Pengaturan sistem berhasil disimpan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage())->withInput();
        }
    }
}
