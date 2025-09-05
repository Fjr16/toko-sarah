<?php

namespace App\Enums;

enum PaymentMethod: string {
    case paid = 'Paid';
    case unpaid = 'Unpaid';
    case pending = 'Pending';
}
