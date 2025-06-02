<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'supermarket_id', 'content', 'parent_id'];

    public function parent() {
        return $this->belongsTo(Review::class, 'parent_id');
    }

    public function replies() {
        return $this->hasMany(Review::class, 'parent_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supermarket()
    {
        return $this->belongsTo(Supermarket::class);
    }

    public function voters() {
        return $this->belongsToMany(User::class, 'review_votes')
                    ->withPivot('vote')
                    ->withTimestamps();
    }

    public function upvotesCount() {
        return $this->voters()->wherePivot('vote', 1)->count();
    }

    public function downvotesCount() {
        return $this->voters()->wherePivot('vote', -1)->count();
    }
}
