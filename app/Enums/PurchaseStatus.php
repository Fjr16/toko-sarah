<?php

namespace App\Enums;

enum PurchaseStatus: string {
    case finish = 'finished';
    case cancel = 'cancelled';

    public function label() : string {
        return match ($this){
            self::finish => 'Selesai',
            self::cancel => 'Batal'
        };
    }
    public function badgeClass() : string {
        return match ($this){
            self::finish => 'badge bg-success text-white',
            self::cancel => 'badge bg-danger text-white'
        };
    }
}
