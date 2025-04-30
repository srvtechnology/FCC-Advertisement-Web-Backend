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
         audit_log('add', 'space_category', $category->id, request()->all());
        return response()->json(['message' => 'Category created successfully', 'data' => $category], 201);
    }

    public function show(SpaceCategory $spaceCategory)
    {
        return response()->json($spaceCategory, 200);
    }

    public function update(SpaceCategoryRequest $request, SpaceCategory $spaceCategory)
    {
        $spaceCategory->update($request->validated());
         audit_log('edit', 'space_category', $spaceCategory->id, request()->all());
        return response()->json(['message' => 'Category updated successfully', 'data' => $spaceCategory], 200);
    }

    // public function destroy(SpaceCategory $spaceCategory)
    // {
    //     $spaceCategory->delete();
    //     return response()->json(['message' => 'Category deleted successfully'], 200);
    // }

    public function destroy(SpaceCategory $spaceCategory)
{
    $categoryId = $spaceCategory->id;
    $categoryName = $spaceCategory->name;

    $spaceCategory->delete();

    audit_log('delete', 'space_category', $categoryId, [
        'deleted_category_id' => $categoryId,
        'deleted_category_name' => $categoryName
    ]);

    return response()->json(['message' => 'Category deleted successfully'], 200);
}

}
