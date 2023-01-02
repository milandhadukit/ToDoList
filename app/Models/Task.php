<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TaskComplate;

class Task extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function taskComplate()
    {
        return $this->hasOne(TaskComplate::class,'id');
    }
}
