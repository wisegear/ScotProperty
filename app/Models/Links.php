<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Links extends Model
{
    use HasFactory;
    protected $table = 'links';

    public function link_category() {
        return $this->hasOne(LinksCategories::class, 'id', 'category_id');
    }

}
