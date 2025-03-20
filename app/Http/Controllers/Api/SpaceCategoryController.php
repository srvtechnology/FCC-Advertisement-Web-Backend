<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SpaceCategoryRequest;
use App\Models\SpaceCategory;
use Illuminate\Http\Request;

class SpaceCategoryController extends Controller
{
    public function index()
    {
        return response()->json(SpaceCategory::orderBy('id', 'desc')->get(), 200);
    }

    public function store(SpaceCategoryRequest $request)
    {
        $category = SpaceCategory::create($request->validated());
        return response()->json(['message' => 'Category created successfully', 'data' => $category], 201);
    }

    public function show(SpaceCategory $spaceCategory)
    {
        return response()->json($spaceCategory, 200);
    }

    public function update(SpaceCategoryRequest $request, SpaceCategory $spaceCategory)
    {
        $spaceCategory->update($request->validated());
        return response()->json(['message' => 'Category updated successfully', 'data' => $spaceCategory], 200);
    }

    public function destroy(SpaceCategory $spaceCategory)
    {
        $spaceCategory->delete();
        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}
