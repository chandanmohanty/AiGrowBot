<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $query = Post::with(['author', 'category']);

        if (!auth()->user()->can('publish posts')) {
            $query->where('user_id', auth()->id());
        }

        if ($q) {
            $query->where(function ($w) use ($q) {
                $w->where('title', 'like', "%$q%")->orWhere('slug', 'like', "%$q%");
            });
        }

        $posts = $query->latest()->paginate(15)->withQueryString();
        return view('admin.posts.index', compact('posts', 'q'));
    }

    public function create()
    {
        $this->authorize('create posts');
        return view('admin.posts.form', [
            'post'       => new Post(),
            'categories' => Category::orderBy('name')->get(),
            'tags'       => Tag::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create posts');
        $data = $this->validateData($request);
        $data['user_id'] = auth()->id();
        $data['body']    = Purifier::clean($data['body'] ?? '');

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $this->storeImage($request->file('cover_image'));
        }

        if ($data['status'] === 'published' && !auth()->user()->can('publish posts')) {
            $data['status'] = 'draft';
        }

        $post = Post::create($data);

        if ($request->filled('tags')) {
            $post->tags()->sync($this->resolveTags($request->input('tags', [])));
        }

        Cache::forget('sitemap.xml');
        return redirect()->route('admin.posts.edit', $post)->with('success', 'Post created.');
    }

    public function edit(Post $post)
    {
        $this->authorize('edit posts');
        if (!auth()->user()->can('publish posts') && $post->user_id !== auth()->id()) {
            abort(403);
        }

        return view('admin.posts.form', [
            'post'       => $post,
            'categories' => Category::orderBy('name')->get(),
            'tags'       => Tag::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('edit posts');
        if (!auth()->user()->can('publish posts') && $post->user_id !== auth()->id()) {
            abort(403);
        }

        $data = $this->validateData($request, $post->id);
        $data['body'] = Purifier::clean($data['body'] ?? '');

        if ($request->hasFile('cover_image')) {
            if ($post->cover_image) Storage::disk('public')->delete(str_replace('/storage/', '', $post->cover_image));
            $data['cover_image'] = $this->storeImage($request->file('cover_image'));
        }

        if ($data['status'] === 'published' && !auth()->user()->can('publish posts')) {
            $data['status'] = $post->status;
        }

        $post->update($data);
        $post->tags()->sync($this->resolveTags($request->input('tags', [])));

        Cache::forget('sitemap.xml');
        Cache::forget("seo:route:post.{$post->slug}");
        return redirect()->route('admin.posts.edit', $post)->with('success', 'Post updated.');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete posts');
        if (!auth()->user()->can('publish posts') && $post->user_id !== auth()->id()) {
            abort(403);
        }
        if ($post->cover_image) Storage::disk('public')->delete(str_replace('/storage/', '', $post->cover_image));
        $post->delete();
        Cache::forget('sitemap.xml');
        return redirect()->route('admin.posts.index')->with('success', 'Post deleted.');
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'slug'             => ['nullable', 'string', 'max:255', 'alpha_dash', "unique:posts,slug,{$ignoreId}"],
            'category_id'      => ['nullable', 'exists:categories,id'],
            'excerpt'          => ['nullable', 'string', 'max:500'],
            'body'             => ['nullable', 'string'],
            'status'           => ['required', 'in:draft,published'],
            'published_at'     => ['nullable', 'date'],
            'cover_image'      => ['nullable', 'image', 'max:4096'],
            'meta_title'       => ['nullable', 'string', 'max:160'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'meta_keywords'    => ['nullable', 'string', 'max:255'],
            'canonical_url'    => ['nullable', 'url', 'max:500'],
            'noindex'          => ['nullable', 'boolean'],
        ]);
    }

    private function storeImage($file): string
    {
        $path = $file->store('posts', 'public');
        return '/storage/' . $path;
    }

    private function resolveTags(array $input): array
    {
        $ids = [];
        foreach ($input as $val) {
            if (is_numeric($val)) {
                $ids[] = (int) $val;
            } else {
                $name = trim((string) $val);
                if ($name === '') continue;
                $tag = Tag::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name]);
                $ids[] = $tag->id;
            }
        }
        return array_unique($ids);
    }
}
