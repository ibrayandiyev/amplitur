<?php

namespace App\Models;

use App\Models\Relationships\BelongsToPackage;
use App\Models\Relationships\HasManyPromocodes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PromocodeGroup extends Model
{
    use HasFactory,
        BelongsToPackage,
        HasManyPromocodes,
        HasTranslations;

    protected $fillable = [
        'package_id',
        'name',
    ];

    protected $translatable = [
        'name'
    ];

    /**
     * [hasSpecificPackage description]
     *
     * @return  [type]  [return description]
     */
    public function hasSpecificPackage(): bool
    {
        return !is_null($this->package_id);
    }

    /**
     * [hasPromocodes description]
     *
     * @return  bool    [return description]
     */
    public function hasPromocodes(): bool
    {
        return $this->promocodes()->count();
    }
}
