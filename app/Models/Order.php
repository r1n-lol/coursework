<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_PREPARING = 'preparing';
    public const STATUS_READY = 'ready';
    public const STATUS_ON_THE_WAY = 'on_the_way';
    public const STATUS_AT_DOOR = 'at_door';
    public const STATUS_DONE = 'done';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'status',
        'order_type',
        'payment_method',
        'payment_card_last4',
        'customer_name',
        'customer_phone',
        'address',
        'comment',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function statusLabels(): array
    {
        return [
            self::STATUS_ACCEPTED => 'Принят',
            self::STATUS_PREPARING => 'Готовится',
            self::STATUS_READY => 'Готов к выдаче',
            self::STATUS_ON_THE_WAY => 'В пути',
            self::STATUS_AT_DOOR => 'Курьер у двери',
            self::STATUS_DONE => 'Завершен',
            self::STATUS_CANCELLED => 'Отменен',
            'new' => 'Принят',
            'in_progress' => 'Готовится',
        ];
    }

    public static function availableStatusesForType(string $orderType): array
    {
        if ($orderType === 'delivery') {
            return [
                self::STATUS_ACCEPTED,
                self::STATUS_PREPARING,
                self::STATUS_ON_THE_WAY,
                self::STATUS_AT_DOOR,
                self::STATUS_DONE,
                self::STATUS_CANCELLED,
            ];
        }

        return [
            self::STATUS_ACCEPTED,
            self::STATUS_PREPARING,
            self::STATUS_READY,
            self::STATUS_DONE,
            self::STATUS_CANCELLED,
        ];
    }

    public function statusLabel(): string
    {
        return self::statusLabels()[$this->status] ?? 'В обработке';
    }

    public function canBeDeleted(): bool
    {
        return in_array($this->status, [
            self::STATUS_DONE,
            self::STATUS_CANCELLED,
        ], true);
    }
}
