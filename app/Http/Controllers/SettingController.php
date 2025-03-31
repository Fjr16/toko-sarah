<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SettingController extends Controller
{
    public function index()
    {
        $item = SystemSetting::first();
        $data = Currency::all();
        if (!$item) {
            $item = new SystemSetting();
            $item->currency_id = null;
            $item->currency_position_default = null;
            $item->company_name = 'Company Name';
            $item->company_email = 'company@gmail.com';
            $item->company_phone = '';
            $item->company_address = 'Indonesia';
            $item->notification_email = 'company@gmail.com';

            if ($firstCurr = Currency::first()) {
                $item->currency_id = $firstCurr->id;
            }
        }
        $position = [
            'prefix' => 'Prefix',
            'suffix' => 'Suffix',
        ];
        return view('pages.system-setting.index', [
            'title' => 'Pengaturan Sistem',
            'menu' => 'settings',
            'item' => $item,
            'data' => $data,
            'position' => $position,
        ]);
    }

    public function store(Request $request) {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'currency_id' => 'required|exists:currencies,id',
                'currency_position_default' => 'required|in:prefix,suffix',
                'company_name' => 'required|string|max:50',
                'company_email' => 'required|email|max:50',
                'company_phone' => 'required|string|max:20',
                'company_address' => 'required|string',
                'notification_email' => 'required|email|max:50',
            ]);

            $data = $request->all();

            $item = SystemSetting::first();
            if (!$item) {
                $item = new SystemSetting();
            }
            $item->currency_id = $data['currency_id'];
            $item->currency_position_default = $data['currency_position_default'];
            $item->company_name = $data['company_name'];
            $item->company_email = $data['company_email'];
            $item->company_phone = $data['company_phone'];
            $item->company_address = $data['company_address'];
            $item->notification_email = $data['notification_email'];
            $item->save();

            DB::commit();

            return redirect()->route('pengaturan/sistem.index')->with('success', 'Pengaturan sistem berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Pengaturan sistem gagal disimpan : ' . $e->getMessage())->withInput();  
        } catch (ValidationException $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();  
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();  
        }
    }
}
