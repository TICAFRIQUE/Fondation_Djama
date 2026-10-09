<?php

namespace App\Http\Controllers;

use App\Support\ImageOptimizer;
use App\Support\Site;
use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class NewsController extends Controller
{

    // FRONT + BACK LIST
    public function index()
    {
        $news = News::latest()->get();

        return view('backend.pages.news.index', compact('news'));
    }
    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|image',
            'category' => 'required|string',
            'published_at' => 'nullable|date',
            'reading_time' => 'nullable|integer',
        ]);

        $path = null;

        if ($request->hasFile('image')) {
            $path = ImageOptimizer::store($request->file('image'), 'news');
        }

        News::create([
            'title' => $request->title,
            'slug' => Site::uniqueSlug(News::class, $request->title),
            'content' => $request->content,
            'image' => $path,
            'category' => $request->category,
            'published_at' => $request->published_at,
            'reading_time' => $request->reading_time ?? 3,
        ]);

        Alert::success('Opération réussie', 'L\'article a été créé avec succès');
        return back();
    }

    // UPDATE
    public function update(Request $request, News $news)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|image',
            'category' => 'required|string',
            'published_at' => 'nullable|date',
            'reading_time' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {

            if ($news->image && Storage::disk('public')->exists($news->image)) {
                Storage::disk('public')->delete($news->image);
            }

            $news->image = ImageOptimizer::store($request->file('image'), 'news');
        }

        // le slug n'est pas modifié : l'adresse publique de l'article reste stable
        $news->update([
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'published_at' => $request->published_at,
            'reading_time' => $request->reading_time ?? 3,
        ]);

        Alert::success('Opération réussie', 'L\'article a été modifié avec succès');
        return back();
    }

    // DELETE
    public function destroy(News $news): JsonResponse
    {
        if ($news->image && Storage::disk('public')->exists($news->image)) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();

        return response()->json([
            'status' => 200,
        ]);
    }
}
