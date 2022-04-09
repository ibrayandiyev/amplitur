<?php

namespace App\Models;

use App\Enums\ProcessStatus;
use App\Models\Relationships\BelongsToAdditionalGroup;
use App\Models\Relationships\BelongsToOffer;
use App\Models\Relationships\BelongsToPackage;
use App\Models\Relationships\BelongsToProvider;
use App\Models\Traits\HasDateLabels;
use App\Models\Traits\HasFieldsSaleDates;
use App\Models\Traits\HasOfferTypeLabels;
use App\Repositories\CurrencyRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Boolean;
use Carbon\Carbon;
use Spatie\Translatable\HasTranslations;
use Symfony\Component\Process\Process;

class Additional extends Model
{
    use BelongsToAdditionalGroup,
        BelongsToProvider,
        BelongsToPackage,
        BelongsToOffer,
        HasTranslations,
        HasDateLabels,
        HasFieldsSaleDates,
        HasFactory;

    protected $fillable = [
        'provider_id',
        'package_id',
        'offer_id',
        'additional_group_id',
        'name',
        'currency',
        'price',
        'stock',
        'type',
        'fields',
        'allowed_providers',
        'allowed_companies',
        'availability',
    ];

    protected $translatable = [
        'name',
    ];

    protected $casts = [
        'fields' => 'array',
        'type' => 'array',
        'allowed_providers' => 'array',
        'allowed_companies' => 'array',
    ];

    public function getTitle(): ?string
    {
        return "{$this->group->name} – {$this->name}";
    }

    public function bustripBoardingLocations()
    {
        return $this->morphedByMany(BustripBoardingLocation::class, 'additionalable');
    }

    /**
     * Get money based on offer currency
     *
     * @return  string
     */
    public function getExtendedPriceAttribute(): ?string
    {
        return $this->currency . ' ' . $this->price;
    }

    /**
     * [getPrice description]
     *
     * @return  float   [return description]
     */
    public function getPrice()
    {
        return $this->price * $this->offer->saleCoefficient->value;
    }

    /**
     * [getPriceNet description]
     *
     * @return  float   [return description]
     */
    public function getPriceNet()
    {
        return $this->price;
    }

    public function getPriceSaleCoefficientValue()
    {
        return ($this->price * $this->offer->saleCoefficient->value) - $this->price;
    }

    /**
     * [getSaleCoefficient description]
     *
     * @return  float   [return description]
     */
    public function getSaleCoefficient()
    {
        return $this->offer->saleCoefficient->value;
    }

    /**
     * Get extended name with group and price
     *
     * @return  string
     */
    public function getExtendedNameAttribute(): ?string
    {
        return "{$this->name} - {$this->group->name} ($this->extendedPrice)";
    }

    /**
     * Get extended name with group and price
     *
     * @return  string
     */
    public function getSoftNameAttribute(): ?string
    {
        return "[{$this->provider->name}] {$this->name} - {$this->group->name}";
    }

    /**
     * [getCompleteNameAttribute description]
     *
     * @return  string  [return description]
     */
    public function getCompleteNameAttribute(): ?string
    {
        $groupName = $this->group->name ?? '';

        $providerName   = ucwords($this->provider->name);
        return "{$this->name} ({$providerName})";
    }

    /**
     * [getCurrency description]
     *
     * @return  string   [return description]
     */
    public function getCurrency(): ?Currency
    {
        $currency = app(CurrencyRepository::class)->findByCode($this->currency);

        return $currency;
    }

    /**
     * [getGroupNameAttribute description]
     *
     * @return  string  [return description]
     */
    public function getGroupNameAttribute(): ?string
    {
        $groupName = $this->group->name ?? '';

        return "{$groupName}";
    }

    /**
     * Check if additionals is in stock
     *
     * @return  bool
     */
    public function hasStock(): bool
    {
        return $this->stock > 0;
    }

    /**
     * [isOutOfStock description]
     *
     * @return  bool    [return description]
     */
    public function isOutOfStock(): bool
    {
        return !$this->hasStock();
    }

    /**
     * [isRunningOut description]
     *
     * @return  bool    [return description]
     */
    public function isRunningOut(): bool
    {
        return $this->stock <= 4;
    }

    /**
     * Get current stock
     *
     * @return  bool
     */
    public function getStock(): int
    {
        return $this->stock ?? 0;
    }

    /**
     * [putStock description]
     *
     * @param   int  $quantity  [$quantity description]
     *
     * @return  [type]          [return description]
     */
    public function putStock(int $quantity = 1)
    {
        $this->stock += $quantity;

        return $this->save();
    }

    /**
     * [pickStock description]
     *
     * @param   int  $quantity  [$quantity description]
     *
     * @return  [type]          [return description]
     */
    public function pickStock(int $quantity = 1)
    {
        $this->stock -= $quantity;

        return $this->save();
    }

    /**
     * [isPublic description]
     *
     * @return  bool    [return description]
     */
    public function isPublic(): bool
    {
        return $this->availability == 'public';
    }

    /**
     * [isExclusive description]
     *
     * @return  bool    [return description]
     */
    public function isExclusive(): bool
    {
        return $this->availability == 'exclusive';
    }

    /**
     * [isAllowedToProvider description]
     *
     * @param   Provider  $provider  [$provider description]
     *
     * @return  bool                 [return description]
     */
    public function isAllowedToProvider(Provider $provider): bool
    {
        return $this->isPublic() || $this->isExclusive() && $this->isStrictedAllowedToProvider($provider);
    }

    /**
     * [isStrictedAllowedToProvider description]
     *
     * @param   Provider  $provider  [$provider description]
     *
     * @return  bool                 [return description]
     */
    public function isStrictedAllowedToProvider(Provider $provider): bool
    {
        return in_array($provider->id, $this->allowed_providers);
    }

    /**
     * [getAvailabilityLabelAttribute description]
     *
     * @return  string  [return description]
     */
    public function getAvailabilityLabelAttribute(): ?string
    {
        if ($this->isPublic()) {
            return '<span class="label label-light-inverse">' . (__('resources.additionals.public')) . '</span>';
        } else {
              return '<span class="label label-inverse">' . (__('resources.additionals.private')) . '</span>';
        }
    }

    /**
     * [getOfferTypeLabelAttribute description]
     *
     * @return  string  [return description]
     */
    public function getOfferTypeLabelAttribute(): ?string
    {
        if (empty($this->type)) {
            return __('resources.offers.model.types.all');
        }

        return implode(', ', $this->type);
    }

    /**
     * [isActive description]
     *
     * @return  bool    [return description]
     */
    public function isActive(): bool
    {
        return $this->offer->status == ProcessStatus::ACTIVE;
    }


    /**
     * Get all sales date formatted
     *
     * @return  string
     */
    public function getSalesDatesFormattedAttribute()
    {
        $formattedSalesDates = "";
        if(isset($this->fields['sale_dates'])){
            $_saleDates = [];
            foreach($this->fields['sale_dates'] as $saleDate){
                $date = Carbon::createFromFormat("Y-m-d", $saleDate);
                $_saleDates[]  = $date->format("d/m/Y");
            }
            $formattedSalesDates = implode(", ", $_saleDates);
        }

        return $formattedSalesDates;
    }
}
