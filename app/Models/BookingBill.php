<?php

namespace App\Models;

use App\Enums\ProcessStatus;
use App\Models\Currency;
use App\Models\Relationships\BelongsToBooking;
use App\Models\Relationships\BelongsToClient;
use App\Models\Relationships\BelongsToCurrency;
use App\Models\Relationships\BelongsToPaymentMethod;
use App\Models\Relationships\HasManyTransactions;
use App\Models\Traits\HasDateLabels;
use App\Models\Traits\HasProcessStatusLabels;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Process\Process;

class BookingBill extends Model
{
    use BelongsToBooking,
        BelongsToClient,
        BelongsToPaymentMethod,
        BelongsToCurrency,
        HasDateLabels,
        HasProcessStatusLabels,
        HasFactory,
        HasManyTransactions;

    protected $fillable = [
        'booking_id',
        'client_id',
        'payment_method_id',
        'currency_id',
        'total',
        'tax',
        'status',
        'installment',
        'ct',
        'url',
        'processor',
        'quotations',
        'expires_at',
        'paid_at',
        'canceled_at',
        'viewed_at',
    ];

    protected $casts = [
        'quotations' => 'array',
        'expires_at' => 'datetime',
        'paid_at' => 'datetime',
        'canceled_at' => 'datetime',
        'viewed_at' => 'datetime',
    ];

    /**
     * [canBeCanceled description]
     *
     * @return  bool    [return description]
     */
    public function canBeCanceled(): bool
    {
        return empty($this->canceled_at) && !$this->isPaid();
    }

    /**
     * [canBePaid description]
     *
     * @return  bool    [return description]
     */
    public function canBePaid(): bool
    {
        return empty($this->paid_at) && $this->canBeCanceled();
    }

    /**
     * [canBeViewed description]
     *
     * @return  bool    [return description]
     */
    public function canBeViewed(): bool
    {
        return !empty($this->url);
    }

    /**
     * [canBeDeleted description]
     *
     * @return  bool    [return description]
     */
    public function canBeDeleted(): bool
    {
        return !$this->isPaid();
    }

    /**
     * [canBeRefunded description]
     *
     * @return  bool    [return description]
     */
    public function canBeRefunded(): bool
    {
        return empty($this->canceled_at) && $this->isPaid();
    }

    /**
     * [canBeRestored description]
     *
     * @return  bool    [return description]
     */
    public function canBeRestored(): bool
    {
        return $this->isCanceled();
    }

    /**
     * [isPending description]
     *
     * @return  bool    [return description]
     */
    public function isPending(): bool
    {
        return $this->status == ProcessStatus::PENDING;
    }

    /**
     * [isExpired description]
     *
     * @return  bool    [return description]
     */
    public function isExpired(): bool
    {
        return $this->isPending() && $this->expires_at < Carbon::now();
    }

    /**
     * [isCanceled description]
     *
     * @return  bool    [return description]
     */
    public function isCanceled(): bool
    {
        return $this->status == ProcessStatus::CANCELED;
    }

    /**
     * [isPaid description]
     *
     * @return  bool    [return description]
     */
    public function isPaid(): bool
    {
        return $this->status == ProcessStatus::PAID;
    }

    /**
     * [getTotalWithTaxes description]
     *
     * @return  [type]  [return description]
     */
    public function getTotalWithTaxes()
    {
        return $this->total + $this->tax;
    }

    /**
     * [getGateway description]
     *
     * @return  [type]  [return description]
     */
    public function getGateway()
    {
        if (!empty($this->processor)) {
            return $this->processor;
        }

        $package = $this->booking->package;

        if (!empty($package)) {
            return $package->getGateway();
        }

        return null;
    }
    
    /**
     * [getOrderNumber description]
     *
     * @return  [type]  [return description]
     */
    public function getOrderNumber(): string
    {
        if (!empty($this->ct)) {
            return (string) 'F' . $this->ct . $this->id;
        }
        
        return (string) 'F' . $this->id;
    }

    /**
     * [getBrlTotal description]
     *
     * @return  [type]  [return description]
     */
    public function getBrlTotal()
    {
        return moneyFloat($this->total, Currency::where('code', 'BRL')->first(), $this->currency);
    }

    /**
     * [getTotalLabel description]
     *
     * @return  float   [return description]
     */
    public function getTotalLabel(): ?string
    {
        $total = moneyDecimal($this->total);
        return $total;
    }

    public function getClientPaymentDataset()
    {
        return ClientPaymentDataset::where('booking_id', $this->booking_id)->orderByDesc('created_at')->first();
    }

    public function getDescription()
    {
        return __('resources.booking-bills.model.installment') . ' ' . $this->installment . ': ' . $this->paymentMethod->name . ' – ' . $this->currency->code . ' ' . $this->getTotalLabel() . ' – ' . __('messages.expiring') . ' ' . $this->expires_at->format('d/m/Y');
    }
}
