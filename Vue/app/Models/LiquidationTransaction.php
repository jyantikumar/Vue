<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiquidationTransaction extends Model
{
    protected $fillable = [
        'liquidation_id',
        'tin_number',
        'payee_name',
        'address',
        'account_id',
        'particulars',
        'net_amount',
        'vat_exempt_sales',
        'input_vat_non_elite',
        'input_vat_elite',
        'withholding_tax',
        'total_amount',
    ];

    /**
     * Relationship back to the Account
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
