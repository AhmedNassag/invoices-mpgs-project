<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Str;

class Invoice extends Model implements HasMedia
{
    use InteractsWithMedia;

    public static function boot()
    {
        parent::boot();
        //while creating/inserting invoice into db
        static::creating(function (Invoice $invoice) {
            $invoice->uuid = Str::uuid(); //assigning uuid value
        });
    }

    protected $casts = [
        'date' => 'datetime',
        'due_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function products(): HasMany
    {
        return $this->hasMany(InvoiceProduct::class);
    }

    public function taxes(): HasMany
    {
        return $this->hasMany(InvoiceTax::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class)->orderBy('id', 'DESC');
    }

    public function getPaymentStatusNameAttribute()
    {
        return data_get(trans('payment_statuses'), $this->payment_status);
    }

    public function getPaymentAmountAttribute()
    {
        return $this->payments->sum('payment_amount');
    }

    public function getDueAmountAttribute()
    {
        return $this->total_amount - $this->payment_amount;
    }

    public function scopeMyInvoice($query, $type = 5): void
    {
        $roleID = auth()->user()->role_id ?? 0;
        if ($roleID >= 3) {
            $query->where('user_id', auth()->id());
        }

        $query->where('type', $type);
    }

    public function getHasFileAttribute(): bool
    {
        return !empty($this->getFirstMediaUrl('invoice'));
    }
}
