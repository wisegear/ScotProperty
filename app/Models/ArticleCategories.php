<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleCategories extends Model
{
    use HasFactory;
    protected $table = 'article_categories';
    public $timestamps = false;

    public function article()
    {
        return $this->hasMany(Article::class, 'articles_id', 'id');
    }

    public static function getCategoriesWithNavigation()
    {
        return self::with(['article' => function ($query) {
                $query->orderBy('order', 'asc'); // Order articles by the 'order' field
            }])
            ->where('navigation', 1)
            ->orderBy('order', 'asc') // Order categories by the 'order' field
            ->get();
    }
}
