<?php

namespace App\Enums;

enum PaymentMethod: string {
    case transfer = 'Transfer';
    case tunai = 'Tunai';
    case qris = 'QRIS';
    case other = 'Lainnya';
}
