<?php

namespace App\Http\Controllers;

use App\Http\Resources\ThemeResource;
use App\Models\Theme;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function index()
    {
        return ThemeResource::collection(Theme::all());
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);
        $theme = Theme::create($validatedData);

        return response($theme, 201);
    }

    public function show(string $id)
    {
        return new ThemeResource(Theme::find($id));
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
