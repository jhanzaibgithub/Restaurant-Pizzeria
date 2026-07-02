<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SBConversation extends Model
{
    use HasFactory;

    protected $table = 'sb_conversations';

    protected $fillable = ['branch_id', 'message', 'reply', 'is_reply', 'image'];
}
