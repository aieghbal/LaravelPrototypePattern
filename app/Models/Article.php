<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Patterns\Prototype\Prototype;

class Article extends Model implements Prototype
{
    protected $fillable = ['title', 'content', 'tags'];

    public function clone(): self
    {
        return new self([
            'title'   => $this->title . ' (Copy)',
            'content' => $this->content,
            'tags'    => $this->tags,
        ]);
    }
}
