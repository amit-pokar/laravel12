<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create specific blogs
        $blogs = [
            [
                'name' => 'Getting Started with Laravel',
                'slug' => 'getting-started-with-laravel',
                'content' => '<h2>Introduction to Laravel</h2><p>Laravel is a modern web application framework with elegant, expressive syntax. We believe development must be an enjoyable, creative experience.</p><p>Laravel takes the pain out of development by easing common tasks used in most web projects.</p>',
                'status' => 'Active',
            ],
            [
                'name' => 'Advanced Database Queries',
                'slug' => 'advanced-database-queries',
                'content' => '<h2>Mastering Database Queries</h2><p>Learn how to write efficient database queries in Laravel using Eloquent ORM.</p><p>This guide covers relationships, eager loading, and optimization techniques.</p>',
                'status' => 'Active',
            ],
            [
                'name' => 'Building RESTful APIs',
                'slug' => 'building-restful-apis',
                'content' => '<h2>RESTful API Development</h2><p>Create powerful REST APIs with Laravel. Learn about routing, resources, and authentication.</p><p>We\'ll cover best practices for API design and implementation.</p>',
                'status' => 'Active',
            ],
            [
                'name' => 'Testing Best Practices',
                'slug' => 'testing-best-practices',
                'content' => '<h2>Unit and Feature Testing</h2><p>Write comprehensive tests for your Laravel applications.</p><p>Discover how to test models, controllers, and API endpoints effectively.</p>',
                'status' => 'Inactive',
            ],
        ];

        foreach ($blogs as $blog) {
            Blog::create($blog);
        }

        // Create 5 random blogs
        Blog::factory(5)->create();
    }
}
