<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookLoan extends Model
{
    use HasFactory, HasUuids, SoftDeletes; //use softdelete and uuid as PK

    protected $guarded = ['id'];

    public function book(): BelongsTo {
        return $this->belongsTo(Book::class, 'book_id', 'id'); //relation to books
    }

    public function member(): BelongsTo {
        return $this->belongsTo(Member::class, 'member_id', 'id'); //relation to books
    }

    public function createdBy(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by', 'id'); //relation to users
    }
}
