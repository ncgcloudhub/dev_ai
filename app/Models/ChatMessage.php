<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function conversation(): BelongsTo
{
    return $this->belongsTo(ChatConversation::class, 'conversation_id', 'id');
    // foreignKey, ownerKey
}
}
