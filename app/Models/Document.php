<?php

namespace App\Models;

use App\Models\Relationships\BelongsToCompany;
use App\Models\Traits\HasDateLabels;
use App\Models\Traits\HasDocumentStatusLabels;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use BelongsToCompany,
        HasDateLabels,
        HasDocumentStatusLabels,
        HasFactory;

    protected $fillable = [
        'provider_id',
        'company_id',
        'filename',
        'filepath',
        'status',
    ];
}
