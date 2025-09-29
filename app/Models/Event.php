<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CommonQueryScopes;

class Event extends Model
{
    use HasFactory, SoftDeletes, CommonQueryScopes;

    protected $fillable = [
        'title',
        'description',
        'date',
        'location',
        'created_by'
    ];

    public function creator(){
        return $this->belongsTo(User::class, 'created_by');
    }
}
