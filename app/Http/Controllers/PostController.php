<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Category;
use App\Post;
use App\Tag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(8);
        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        $posts = Post::where('category_id', $post->category_id)->latest()->limit(6)->get();
        return view('posts.show', compact('post', 'posts'));
    }

    public function create()
    {
        return view('posts.create', [
            'post' => new Post(),
            'categories' => Category::get(),
            'tags' => Tag::get()
        ]);
    }

    public function store(PostRequest $request)
    {
        $request->validate([
            'thumbnail' => 'image|mimes:jpg,jpeg,png,svg|max:2048'
        ]);

        $attr = $request->all();

        $slug = Str::slug(request('title'));
        $attr["slug"] = $slug;

        $thumbnail = request()->file('thumbnail')
            ? request()->file('thumbnail')->store("images/posts")
            : null;

        $attr["category_id"] = request('category');
        $attr["thumbnail"] = $thumbnail;

        $post = auth()->user()->posts()->create($attr);
        $post->tags()->attach(request('tags'));

        session()->flash('success', 'The post was created!');

        return redirect('/posts');
        return back();
    }

    public function edit(Post $post)
    {
        // dd($post);
        return view('posts.edit', [
            'post' => $post,
            'categories' => Category::get(),
            'tags' => Tag::get()
        ]);
    }

    public function update(PostRequest $request, Post $post)
    {
        $request->validate([
            'thumbnail' => 'image|mimes:jpeg,jpg,png,svg|max:2048'
        ]);

        if (request()->file('thumbnail')) {
            Storage::delete($post->thumbnail);
            $thumbnail = request()->file('thumbnail');
            $thumbnailUrl = $thumbnail->store("images/posts");
        } else {
            $thumbnail = $post->thumbnail;
        }

        $this->authorize('update', $post);
        $attr = $request->all();
        $attr['category_id'] = request('category');
        $attr["thumbnail"] = $thumbnailUrl;
        // inheritance from Post
        $post->update($attr);
        $post->tags()->sync(request('tags'));

        session()->flash('success', 'The post succeessfully updated!');
        return redirect('/posts');
    }

    public function destroy(Post $post)
    {
        // if (auth()->user()->is($post->user)) {
        //     $post->tags()->detach();
        //     $post->delete();
        //     session()->flash('success', "The post was destroyed");
        //     return redirect('posts');
        // } else {
        //     session()->flash('error', "It wasn't your post");
        //     return redirect('posts');
        // }

        $this->authorize('update', $post);
        Storage::delete($post->thumbnail);
        $post->tags()->detach();
        $post->delete();
        session()->flash('success', "The post was destroyed");
        return redirect('posts');
    }
}
