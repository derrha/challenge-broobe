<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Strategy extends Model
{
    use HasFactory;
    protected $table = 'strategies';

    protected $fillable = ['name'];

    public function metricHistoryRun(): HasOne{
        return $this->hasOne(MetricHistoryRun::class, 'strategy_id');
    }
}
