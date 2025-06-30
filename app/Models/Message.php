<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'application_id',
        'sender_type',
        'sender_id',
        'message',
        'is_read',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function files()
    {
        return $this->hasMany(MessageFile::class);
    }

    public function senderUser()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function senderCompany()
    {
        return $this->belongsTo(Company::class, 'sender_id');
    }

    public function getSenderAttribute()
    {
        return $this->sender_type === 0
            ? $this->senderUser
            : $this->senderCompany;
    }
}