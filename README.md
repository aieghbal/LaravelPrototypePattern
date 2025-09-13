# Prototype Design Pattern in Laravel
This repository is a simple example of implementing the Prototype Pattern in the Laravel framework.


---

## 📌 Example Idea
Suppose we have a content management system for articles.
Sometimes we need to clone an existing article and only change small parts (like the title or tags).
Instead of creating a new object from scratch, we can use the Prototype Pattern.

---

## ⚙️ Implementation Steps

### 1.Create the Article Model

```bash
php artisan make:model Article -m
```

In the migration file:

```php
Schema::create('articles', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('content');
    $table->string('tags')->nullable();
    $table->timestamps();
});
```
Then run:

```bash
php artisan migrate
```

---

### 2. Define the Prototype Interface
File: app/Patterns/Prototype/Prototype.php

```php
<?php

namespace App\Patterns\Prototype;

interface Prototype
{
    public function clone(): self;
}
```

---

### 3. Implement Prototype in Article Modele
File: app/Models/Article.php

```php
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
```

---

### 4. Create a Controller for Testing

```bash
php artisan make:controller ArticleController
```

In ArticleController.php:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Article;

class ArticleController extends Controller
{
    public function createSample()
    {
        $article = Article::create([
            'title'   => 'Prototype Pattern in Laravel',
            'content' => 'این مقاله درباره Prototype Pattern است...',
            'tags'    => 'design pattern,laravel,prototype'
        ]);

        return response()->json($article);
    }

    public function cloneArticle($id)
    {
        $original = Article::findOrFail($id);
        $copy = $original->clone();
        $copy->save();

        return response()->json([
            'original' => $original,
            'copy'     => $copy
        ]);
    }
}
```

---

### 5. Define Routes
In routes/web.php:

```php
use App\Http\Controllers\ArticleController;

Route::get('/article/create-sample', [ArticleController::class, 'createSample']);
Route::get('/article/clone/{id}', [ArticleController::class, 'cloneArticle']);
```

---

## 🚀 Test the Project
1. Visit /article/create-sample → creates a sample article.
2. Visit /article/clone/1 → clones the article with ID 1 and saves the new copy.

---

## 🧪 Write Tests to Ensure Correct Implementation
Test file: tests/Feature/PrototypeTest.php
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PrototypeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_clone_an_article()
    {
        $article = Article::create([
            'title'   => 'Original Title',
            'content' => 'Some content',
            'tags'    => 'laravel,prototype'
        ]);

        $clone = $article->clone();
        $clone->save();

        $this->assertDatabaseHas('articles', ['title' => 'Original Title (Copy)']);
    }
}
```

Run the test:

```bash
php artisan test
```

---

## 🔑 Key Points

- ** The Prototype Pattern is useful when:
  - Creating a new object from scratch is expensive or time-consuming.
  - You need to quickly copy an existing object.
  - You want to make small changes on the cloned version.
- Writing tests ensures that cloning works correctly and accurately.

---

✅ Using this method, you can easily implement the Prototype Pattern in Laravel and verify its functionality with automated tests.


[Persian Version](./README.fa.md)
