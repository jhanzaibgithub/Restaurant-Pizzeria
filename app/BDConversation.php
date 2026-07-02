<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BDConversation extends Model
{
    use HasFactory;

    protected $table = "bd_conversations";


    public function messages(): HasMany
    {
        return $this->hasMany(BDMessage::class, 'conversation_id');
    }

}
