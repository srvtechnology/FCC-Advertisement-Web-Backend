<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;


class Payment extends Model
{
    use HasFactory;
protected $fillable = [
    'booking_id', 
    'space_id', 
    'amount', 
    'payment_type',
    'payment_amount_1',
    'payment_amount_2',
    'status',
    'payee_name', 
    'payment_date', 
    'payment_mode', 
    'payee_address', 
    'transaction_id', 
    'payment_slip',
    'created_user_id',
];


    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function space()
    {
        return $this->belongsTo(Space::class);
    }

      public function createdByUser() {
        return $this->hasOne('App\Models\User','id','created_user_id') ;
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
