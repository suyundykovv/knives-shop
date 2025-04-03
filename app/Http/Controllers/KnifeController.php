<?php
namespace App\Http\Controllers;

use App\Models\Knife;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Routing\Controller as BaseController;

class KnifeController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth'); // Аутентификация обязательна
    }

    public function index2(): Response
    {
        return Inertia::render('Knives/Index2', [
            'knives' => Knife::all() // Тот же запрос, что и в index()
        ]);
    }
    public function knifePanel(): Response
    {
        return Inertia::render('Knives/knifePanel', [
            'knives' => Knife::all()
        ]);
    }
    public function index(): Response
    {
        return Inertia::render('Knives/Index', [
            'knives' => Knife::all()
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Knives/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048', // Ensure this is for the image upload
        ]);

        $knife = new Knife($request->only(['name', 'description', 'price']));

        if ($request->hasFile('image')) {
            $knife->image_url = $request->file('image')->store('knives', 'public'); // Use image_url here
        }

        $knife->save();

        return Inertia::render('Knives/Index2', [
            'knives' => Knife::all(),
            'message' => 'Knife created successfully'
        ]);    }

    public function update(Request $request, Knife $knife)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048', // Ensure this is for the image upload
        ]);

        $knife->fill($request->only(['name', 'description', 'price']));

        if ($request->hasFile('image')) {
            $knife->image_url = $request->file('image')->store('knives', 'public'); // Use image_url here
        }

        $knife->save();

        return Inertia::render('Knives/Index2', [
            'knives' => Knife::all(),
            'message' => 'Knife updated successfully'
        ]);    }
    public function destroy($id)
    {
        $knife = Knife::findOrFail($id);
        $knife->delete();

        return Inertia::render('Knives/Index2', [
            'knives' => Knife::all(),
            'message' => 'Knife deleted successfully'
        ]);
    }
}

