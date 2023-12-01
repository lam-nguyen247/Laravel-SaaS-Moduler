<?php

namespace App\Modules\User\Models;

use App\Enums\PlanStatus;
use App\Enums\PlanBillCycle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'name',
        'code',
        'price',
        'bill_frequency',
        'trial_period',
        'bill_cycle',
        'is_recommend',
        'is_free',
        'is_visible',
        'display_order',
        'extras',
        'status',
        'description',
        'properties',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'bill_cycle' => PlanBillCycle::class,
        'is_recommend' => 'boolean',
        'is_free' => 'boolean',
        'is_visible' => 'boolean',
        'status' => PlanStatus::class,
    ];
}
