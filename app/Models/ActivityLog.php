<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'action', 'model_type', 'model_id',
        'old_data', 'new_data', 'ip_address', 'user_agent'
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Record an audit entry for a critical data change.
     *
     * @param  string  $action    e.g. 'checkout', 'stock_in', 'opname', 'update_price'
     * @param  \Illuminate\Database\Eloquent\Model|null  $model
     * @param  array|null  $oldData
     * @param  array|null  $newData
     * @return static|null
     */
    public static function log(string $action, $model = null, array $oldData = null, array $newData = null)
    {
        if (! auth()->check()) {
            return null;
        }

        $request = request();

        return static::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'old_data' => $oldData,
            'new_data' => $newData,
            'ip_address' => $request ? $request->ip() : null,
            'user_agent' => $request ? $request->userAgent() : null,
        ]);
    }
}
