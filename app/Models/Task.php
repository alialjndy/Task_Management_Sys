<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'user_task';
    protected $primaryKey = 'task_id';
    public $incrementing  = true;
    protected $fillable = [
        'manager_id',
        'title',
        'description',
        'due_date',
        'priority',
        'status',
        'to_assigned'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function scopePriority($query , $priority)
    {
        return $query->where('priority' ,$priority);
    }
    public function scopeStatus($query , $status){
        return $query->where('status', $status);
    }
    public function getDueDateAttribute($value){
        return Carbon::parse($value)->format('d-m-Y H:i');
    }
    public function setDueDateAttribute($value){
        $this->attributes['due_date'] = Carbon::createFromFormat('d-m-Y H:i',$value)->format('Y-m-d H:i:s');
    }
}
