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
        return $this->belongsTo(User::class,'to_assigned');
    }
    /**
     * Summary of scopePriority
     * @param mixed $query
     * @param mixed $priority
     * @return mixed
     */
    public function scopePriority($query , $priority)
    {
        return $query->where('priority' ,$priority);
    }
    /**
     * Summary of scopeStatus
     * @param mixed $query
     * @param mixed $status
     * @return mixed
     */
    public function scopeStatus($query , $status){
        return $query->where('status', $status);
    }
    /**
     * Summary of getDueDateAttribute
     * @param mixed $value
     * @return string
     */
    public function getDueDateAttribute($value){
        return Carbon::parse($value)->format('d-m-Y H:i');
    }
    /**
     * Summary of setDueDateAttribute
     * @param mixed $value
     * @return void
     */
    public function setDueDateAttribute($value){
        $this->attributes['due_date'] = Carbon::createFromFormat('d-m-Y H:i',$value)->format('Y-m-d H:i:s');
    }
    /**
     * Summary of employee
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee(){
        return $this->belongsTo(User::class , 'to_assigned'); // رح ترد المستخدم المرتبط بهاد التاسك
        // يعني الخرج هو سجل من جدول المستخدمين
    }
    /**
     * Summary of manager
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manager(){
        return $this->belongsTo(User::class , 'manager_id');
        /**
         * الخرج هو عبارة عن سجل من جدول المستخدمين ولكن هذا السجل هو مدير التاسك الذي أستدعينا عليه هذه الطريقة
         */
    }
}
