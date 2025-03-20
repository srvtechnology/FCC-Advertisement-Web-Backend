<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Space extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_collection_date',
        'name_of_person_collection_data',
        'name_of_advertise_agent_company_or_person',
        'name_of_contact_person',
        'telephone',
        'email',
        'location',
        'stree_rd_no',
        'section_of_rd',
        'landmark',
        'gps_cordinate',
        'description_property_advertisement',
        'advertisement_cat_desc',
        'type_of_advertisement',
        'position_of_billboard',
        'lenght_advertise',
        'width_advertise',
        'area_advertise',
        'no_advertisement_sides',
        'other_advertisement_sides',
        'other_advertisement_sides_no',
        'clearance_height_advertise',
        'illuminate_nonilluminate',
        'certified_georgia_licensed',
        'landowner_company_corporate',
        'landowner_name',
        'landlord_street_address',
        'landlord_telephone',
        'landlord_email',
        'space_cat_id',  // <-- Added
        'rate',          // <-- Added
        'business_address',
        'business_contact',
        'business_name',
        'created_user_id',
    ];

    public function category()
    {
        return $this->belongsTo(SpaceCategory::class, 'space_cat_id');
    }

    // In app/Models/Space.php

    public function bookings()
    {
        return $this->hasMany(Booking::class); // Or hasMany(Booking::class, 'space_id'); if foreign key is not 'space_id'
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
