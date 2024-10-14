<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingHasMember extends Model
{
    use HasFactory;

    protected $table = 'meeting_has_member';

    public function member() {
        return $this->belongsTo(Member::class);
    }

    // Relationship with Contacts
    public function meeting() {
        return $this->belongsTo(Meeting::class);
    }
}
