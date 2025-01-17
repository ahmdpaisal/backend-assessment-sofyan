<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, HasUuids, SoftDeletes; //use softdelete and uuid as PK

    protected $guarded = ['id'];

    public function createdBy(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by', 'id'); //relation to user
    }

    public function updatedBy(): BelongsTo {
        return $this->belongsTo(User::class, 'updated_by', 'id'); //relation to user
    }

    public function deletedBy(): BelongsTo {
        return $this->belongsTo(User::class, 'deleted_by', 'id'); //relation to user
    }
}
