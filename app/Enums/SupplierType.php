<?php

namespace App\Enums;

enum SupplierType: string {
    case pabrik = 'Pabrik';
    case distributor = 'Distributor';
    case agen = 'Agen';
}