<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;
    protected $table = 'article';
    public $timestamps = false;

    protected $casts = [
        'date' => 'datetime',
    ];

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function user() {

        return $this->hasOne(User::class, 'id', 'user_id');
   
    }

    public function articles() {

        return $this->hasOne(ArticleCategories::class, 'id', 'articles_id');

    }

    // Used to create table of contents for the blog posts.

    public function getBodyHeadings($tag = 'h2')
    {
        $dom = new \DOMDocument();
    
        // Suppress warnings and add proper HTML structure
        @$dom->loadHTML('<html><body>' . $this->text . '</body></html>');
    
        $headings = [];
    
        foreach ($dom->getElementsByTagName($tag) as $heading) {
            $headings[] = $heading->nodeValue;
        }
    
        return $headings;
    }

    // add anchors to the post h2 headings so that you can scroll to that point.

    public function addAnchorLinksToHeadings()
    {
        $content = $this->text;

        // Use regular expressions to find and replace H2 headings with anchor links
        $contentWithAnchors = preg_replace_callback(
            '/<h2[^>]*>(.*?)<\/h2>/i',
            function ($matches) {
                $headingText = strip_tags($matches[1]); // Get the heading text
                $slug = Str::slug($headingText); // Generate a slug from heading text

                // Create an anchor link
                return "<h2 id=\"$slug\">$headingText <a href=\"#$slug\" class=\"anchor-link\"></a></h2>";
            },
            $content
        );

        return $contentWithAnchors;
    }

}
