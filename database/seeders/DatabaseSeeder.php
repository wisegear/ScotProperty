<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Quote;
use App\Models\Timeline;
use App\Models\BlogPosts;
use App\Models\BlogCategories;
use App\Models\BlogTags;
use App\Models\BlogPostTags;
use App\Models\LinksCategories;
use App\Models\Links;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UsersSeeder::class);
        $this->call(UsersRolesSeeder::class);
        Quote::factory(50)->create();
        Timeline::factory(100)->create();
        BlogCategories::factory(6)->create();
        BlogPosts::factory(50)->create();
        BlogTags::factory(100)->create();
        BlogPostTags::factory(200)->create();
        LinksCategories::factory(8)->create();
        Links::factory(100)->create();

    }
}
