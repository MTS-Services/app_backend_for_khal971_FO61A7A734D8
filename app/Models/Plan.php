<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'order_index',
        'name',
        'description',
        'price',
        'duration',
        'stripe_price_id',
        'apple_product_id',
        'google_product_id',
        'features',
        'status',
        'is_popular',

        'created_by',
        'updated_by',

    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'status' => 'integer',
        'is_popular' => 'boolean',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->appends = array_merge(parent::getAppends(), [
            'status_label',
        ]);
    }

    /////////////////////////
    // Status Attributes
    /////////////////////////
    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;

    public static function getStatusList(): array
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::getStatusList()[$this->status];
    }

    public function getStatusListAttribute(): array
    {
        return self::getStatusList();
    }
}
