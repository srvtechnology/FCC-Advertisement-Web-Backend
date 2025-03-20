<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;


class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'space_id', 'start_date','period', 'end_date','customer_name','customer_email','mobile','address', 'status','description_of_ad','created_user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function space()
    {
        return $this->belongsTo(Space::class);
    }

      public function createdByUser() {
        return $this->hasOne('App\Models\User','id','created_user_id') ;
    }

   public function payment() {
    return $this->hasOne('App\Models\Payment', 'booking_id', 'id');
}

public function payments()
{
    return $this->hasMany('App\Models\Payment', 'booking_id', 'id');
}

public function getTotalAmountAttribute()
{
    return $this->payments->sum('amount'); // Use the loaded relationship instead of making a new query
}


    protected static function boot()
    {
        parent::boot();

        // Automatically set 'created_user_id' ONLY when inserting a new record
        static::creating(function ($model) {
            if (Auth::check() && empty($model->created_user_id)) { // Ensure it's not already set
                $model->created_user_id = Auth::id();
            }
        });
    }
}
