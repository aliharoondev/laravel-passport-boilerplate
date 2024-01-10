<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as RoleModel;
use App\Models\User;

class Role extends RoleModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug', 'is_active', 'created_by', 'updated_by', 'deleted_by'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
