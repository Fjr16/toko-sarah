<?php

namespace App\Helpers;

use App\Enums\InventoryFlag;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CustomHelpers {
    public static function cleanCurrency($val){
        if (empty($val)) {
            return null;
        }
        $cleaned = preg_replace('/[^\d]/', '', $val);
        return $cleaned;
    }

    public function logInventoryMovements($instanceModelAsal, $req){
        $validators = Validator::make($req, [
            'user_id' => 'required',
            'item_id' => 'required|exists:items,id',
            'product_batch_id' => 'required|exists:product_batches,id',
            'flag' => ['required', Rule::enum(InventoryFlag::class)],
            'qty' => 'required',
            'unit' => 'required',
            'note' => 'nullable',
        ]);
        if ($validators->fails()) {
            return [
                'status' => false,
                'message' => $validators->errors()->first()
            ];
        }
        try {
            $instanceModelAsal->inventoryMovements()->create($req);

            return [
                'status' => true,
                'message' => 'Berhasil mencatat log pergerakan produk'
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => substr($th->getMessage(), 0,150)
            ];
        }
    }

    public static function formatterRupiah($val){
        if (empty($val)) {
            return 'Rp. -';
        }

        $formatter = number_format($val, 0, ',', '.');
        return 'Rp ' . $formatter;
    }
}
