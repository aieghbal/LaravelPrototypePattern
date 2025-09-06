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
