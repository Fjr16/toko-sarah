<?php

namespace App\Enums;

enum InventoryFlag:string {
    case in = 'IN';
    case out = 'OUT';
}