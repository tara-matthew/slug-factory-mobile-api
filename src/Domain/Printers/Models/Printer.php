<?php

namespace Domain\Printers\Models;

use Domain\Printers\Brands\Models\PrinterBrand;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Printer extends Model
{
    use HasFactory;

    public function printerBrand(): BelongsTo
    {
        return $this->belongsTo(PrinterBrand::class);
    }
}
