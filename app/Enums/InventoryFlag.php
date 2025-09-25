<?php

namespace App\Enums;

enum InventoryFlag:string {
    case in = 'IN';
    case out = 'OUT';

    public function label(): string{
        return match ($this) {
            self::in => 'Penambahan',
            self::out => 'Pengurangan',
        };
    }
}
