<?php

namespace App\Enums;

enum PaymentMethod: string {
    case tunai = 'tunai';
    case transfer = 'transfer';
    case qris = 'qris';
    case other = 'other';

    public function label(){
        return match($this){
            self::tunai => 'Cash / Tunai',
            self::transfer => 'Transfer',
            self::qris => 'QRIS',
            self::other => 'Lainnya',
        };
    }
}

