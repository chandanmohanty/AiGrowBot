<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Services\SeoService;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request, SeoService $seo)
    {
        $q = trim((string) $request->query('q', ''));
        $categorySlug = $request->query('category');
        $tagSlug = $request->query('tag');

        $posts = Post::published()
            ->with(['author', 'category', 'tags'])
            ->when($q, fn ($qb) => $qb->where(function ($w) use ($q) {
                $w->where('title', 'like', "%$q%")
                  ->orWhere('excerpt', 'like', "%$q%")
                  ->orWhere('body', 'like', "%$q%");
            }))
            ->when($categorySlug, fn ($qb) => $qb->whereHas('category', fn ($c) => $c->where('slug', $categorySlug)))
            ->when($tagSlug, fn ($qb) => $qb->whereHas('tags', fn ($t) => $t->where('slug', $tagSlug)))
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->limit(20)->get();

        return response()
            ->view('blog.index', [
                'posts'      => $posts,
                'categories' => $categories,
                'tags'       => $tags,
                'q'          => $q,
                'seo'        => $seo->forRoute('blog.index'),
            ])
            ->header('Cache-Control', 'public, max-age=120');
    }

    public function show(Post $post, SeoService $seo)
    {
        abort_unless($post->status === 'published' && (!$post->published_at || $post->published_at->isPast()), 404);

        $post->increment('views');

        $related = Post::published()
            ->where('id', '!=', $post->id)
            ->when($post->category_id, fn ($qb) => $qb->where('category_id', $post->category_id))
            ->latest('published_at')
            ->limit(3)
            ->get();

        return response()
            ->view('blog.show', [
                'post'    => $post,
                'related' => $related,
                'seo'     => $seo->forPost($post),
            ])
            ->header('Cache-Control', 'public, max-age=300');
    }
}
