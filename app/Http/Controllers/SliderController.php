<?php

namespace App\Http\Controllers;

use App\Support\ImageOptimizer;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::orderBy('order')->get();
        return view('backend.pages.sliders.index', compact('sliders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'badge' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'highlight' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'btn1_text' => 'nullable|string|max:100',
            'btn1_link' => 'nullable|string|max:255',
            'btn2_text' => 'nullable|string|max:100',
            'btn2_link' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->only([
            'badge',
            'title',
            'highlight',
            'description',
            'btn1_text',
            'btn1_link',
            'btn2_text',
            'btn2_link',
            'order',
            'is_active',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = ImageOptimizer::store($request->file('image'), 'sliders');
        }

        $data['is_active'] = $request->is_active ?? 1;
        $data['order'] = $request->order ?? 0;

        Slider::create($data);

        Alert::success('Opération réussie', 'Le slider a été créé avec succès');
        return back();
    }

    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'badge' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'highlight' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'btn1_text' => 'nullable|string|max:100',
            'btn1_link' => 'nullable|string|max:255',
            'btn2_text' => 'nullable|string|max:100',
            'btn2_link' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->only([
            'badge',
            'title',
            'highlight',
            'description',
            'btn1_text',
            'btn1_link',
            'btn2_text',
            'btn2_link',
            'order',
            'is_active',
        ]);

        if ($request->hasFile('image')) {
            if ($slider->image && Storage::disk('public')->exists($slider->image)) {
                Storage::disk('public')->delete($slider->image);
            }
            $data['image'] = ImageOptimizer::store($request->file('image'), 'sliders');
        }

        $data['is_active'] = $request->is_active ?? $slider->is_active;
        $data['order'] = $request->order ?? $slider->order;

        $slider->update($data);

        Alert::success('Opération réussie', 'Le slider a été modifié avec succès');
        return back();
    }

    public function destroy(Slider $slider): JsonResponse
    {
        if ($slider->image && Storage::disk('public')->exists($slider->image)) {
            Storage::disk('public')->delete($slider->image);
        }

        $slider->delete();

        return response()->json([
            'status' => 200,
        ]);
    }
}