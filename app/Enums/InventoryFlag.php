<?php

namespace App\Enums;

enum InventoryFlag:string {
    case in = 'IN';
    case out = 'OUT';

    public function label(): string{
        return match ($this) {
            self::in => 'Peningkatan',
            self::out => 'Penurunan',
        };
    }
    Public function icon(){
        return match ($this) {
            self::in => 'bi bi-graph-up-arrow text-primary',
            self::out => 'bi bi-graph-down-arrow text-danger'
        };
    }
}
