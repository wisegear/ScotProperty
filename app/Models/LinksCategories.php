<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinksCategories extends Model
{
    use HasFactory;
    protected $table = 'links_categories';
    public $timestamps = false;

    public function links() {
        return $this->hasMany(Links::class, 'category_id', 'id');
    }

}
