<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
      protected $fillable = [
            'user_id',
            'content',
            'completed',
            'complete_date',
      ];

      public function user()
      {
            return $this->belongsTo(User::class);
      }
}
