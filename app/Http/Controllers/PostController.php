<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostValidation;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $posts = Post::with('user:id,name,email')->select('id', 'user_id', 'title', 'created_at', 'image')->latest()->get();
        return view('admin.post.index', compact('posts'));
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin.post.create');
    }

    public function show($id): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $post = Post::findOrFail($id);
        return view('admin.post.create', compact('post'));
    }

    public function store(PostValidation $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();

        $data['image'] = $this->imageUpload($request);
        Post::create($data);

        return redirect()->route('post.index');
    }

    private function imageUpload($request): ?string
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = $file->hashName();
            $path = "post_image/$name";
            Storage::disk('public')->put($path, file_get_contents($file));
            return Storage::disk('public')->url($path);
        }
        return null;
    }


    public function update(PostValidation $request, $id): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();

        $post = Post::findOrFail($id);

        $url = $this->imageUpload($request);
        if ($url) {
            $data['image'] = $url;
        }

        $post->update($data);

        return redirect()->route('post.index');
    }

    public function delete($id): \Illuminate\Http\JsonResponse
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return response()->json(['success' => true, 'message' => 'Delete Successful', 'url' => route('post.index')]);
    }
}
