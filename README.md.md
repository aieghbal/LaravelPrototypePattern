# Prototype Design Pattern در لاراول

<div dir="rtl">
این ریپوزیتوری یک مثال ساده از پیاده‌سازی **Prototype Pattern** در فریم‌ورک Laravel است.
</div>

---

## 📌 ایده مثال

<div dir="rtl">
فرض کنید یک سیستم مدیریت مقالات داریم. گاهی لازم است یک مقاله موجود را **کپی (Clone)** کنیم و فقط بخش‌هایی کوچک (مثل عنوان یا تگ‌ها) را تغییر دهیم. در این مواقع، به جای ساختن یک شیء جدید از صفر، می‌توانیم از **Prototype Pattern** استفاده کنیم.
</div>

---

## ⚙️ مراحل پیاده‌سازی

### 1. ساخت مدل Article

```bash
php artisan make:model Article -m
```

<div dir="rtl">
در فایل migration:
</div>

```php
Schema::create('articles', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('content');
    $table->string('tags')->nullable();
    $table->timestamps();
});
```

<div dir="rtl">
سپس:
</div>

```bash
php artisan migrate
```

---

### 2. تعریف Interface برای Prototype

<div dir="rtl">فایل `app/Patterns/Prototype/Prototype.php`:</div>

```php
<?php

namespace App\Patterns\Prototype;

interface Prototype
{
    public function clone(): self;
}
```

---

### 3. پیاده‌سازی Prototype در مدل Article

<div dir="rtl">فایل `app/Models/Article.php`:</div>

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

### 4. ساخت کنترلر برای تست

```bash
php artisan make:controller ArticleController
```

<div dir="rtl">در فایل `ArticleController.php`:</div>

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

### 5. تعریف Route

<div dir="rtl">در فایل `routes/web.php`:</div>

```php
use App\Http\Controllers\ArticleController;

Route::get('/article/create-sample', [ArticleController::class, 'createSample']);
Route::get('/article/clone/{id}', [ArticleController::class, 'cloneArticle']);
```

---

## 🚀 تست پروژه

<div dir="rtl">
1. اجرای آدرس `/article/create-sample` → یک مقاله اصلی ایجاد می‌شود.
2. اجرای آدرس `/article/clone/1` → مقاله با شناسه `1` کلون می‌شود و نسخه جدید آن ذخیره خواهد شد.
</div>

---

## 🧪 نوشتن تست برای اطمینان از صحت پیاده‌سازی

<div dir="rtl">فایل تست `tests/Feature/PrototypeTest.php`:</div>

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

<div dir="rtl">سپس تست را اجرا کنید:</div>

```bash
php artisan test
```

---

## 🔑 نکات کلیدی

<div dir="rtl">
- **Prototype Pattern** زمانی مفید است که:
  - ساخت یک شیء جدید پرهزینه یا زمان‌بر باشد.
  - نیاز به کپی‌برداری سریع از نمونه موجود داشته باشیم.
  - بخواهیم تغییرات جزئی روی نسخه جدید انجام دهیم.
- با تست نوشتن می‌توانیم مطمئن شویم که کپی‌برداری دقیق و درست انجام شده است.
</div>

---

<div dir="rtl">
✅ با این روش می‌توانید در لاراول به راحتی Prototype Pattern را پیاده‌سازی کرده و با تست‌های خودکار از صحت عملکرد آن مطمئن شوید.
</div>


[English Version](./README.md)
