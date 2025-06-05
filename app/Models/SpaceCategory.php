<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaceCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'rate','system_agent_rate','corporate_agent_rate'];
}
