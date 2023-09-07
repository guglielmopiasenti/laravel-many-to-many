<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'technologies',
        'screenshot_path',
        'is_featured',
        'github_url',
        'type_id',
    ];
    public function type(){
        return $this->belongsTo(Type::class);
    }
}
