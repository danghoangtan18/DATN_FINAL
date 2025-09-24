<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $table = 'promotions';

    protected $fillable = [
        'title',
        'description',
        'discount_type', // 'percentage', 'fixed_amount', 'buy_x_get_y'
        'discount_value',
        'minimum_order_amount',
        'maximum_discount_amount',
        'start_date',
        'end_date',
        'is_active',
        'image',
        'promotion_code',
        'usage_limit',
        'used_count',
        'applicable_categories',
        'applicable_brands',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'applicable_categories' => 'array',
        'applicable_brands' => 'array',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('end_date', '<', now());
    }

    // Helper methods
    public function isActive()
    {
        return $this->is_active && 
               $this->start_date <= now() && 
               $this->end_date >= now();
    }

    public function isExpired()
    {
        return $this->end_date < now();
    }

    public function isUpcoming()
    {
        return $this->start_date > now();
    }

    public function getStatusAttribute()
    {
        if (!$this->is_active) {
            return 'inactive';
        }
        
        if ($this->isUpcoming()) {
            return 'upcoming';
        }
        
        if ($this->isExpired()) {
            return 'expired';
        }
        
        return 'active';
    }

    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case 'active':
                return 'success';
            case 'upcoming':
                return 'info';
            case 'expired':
                return 'secondary';
            case 'inactive':
                return 'danger';
            default:
                return 'secondary';
        }
    }

    public function getDiscountDisplayAttribute()
    {
        switch ($this->discount_type) {
            case 'percentage':
                return $this->discount_value . '%';
            case 'fixed_amount':
                return number_format($this->discount_value) . 'đ';
            case 'buy_x_get_y':
                return 'Mua ' . $this->discount_value . ' tặng 1';
            default:
                return $this->discount_value;
        }
    }

    public function canBeUsed()
    {
        if (!$this->isActive()) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function incrementUsage()
    {
        $this->increment('used_count');
    }
}