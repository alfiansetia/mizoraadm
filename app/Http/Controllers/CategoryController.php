<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\CategoryProduct;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'asc'); // Default to ascending order if not specified
        $categories = CategoryProduct::orderBy('name', $sort)->get();

        return view('inventory.category.index', compact('categories'));
    }



    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Example validation for image upload
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();

            // Move the uploaded file to a specified directory
            $image->move(public_path('categoryproduct'), $imageName);

            $data['image'] = $imageName;
        }

        $categories = CategoryProduct::create($data);

        $categories->save();

        return redirect()->route('inventory.category.index', compact('data', 'categories'));
    }
    // public function edit(CategoryProduct $category) {
    //     return view('inventory.category.edit', compact('category'));
    // }

    public function update(Request $request, CategoryProduct $category)
    {
        // Validate the request data
        $data = $request->validate([
            'name' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imageName = '';
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('categoryproduct'), $imageName);
            $data['image'] = $imageName;
            if ($category->image) {
                if (file_exists(public_path('categoryproduct/' . $category->image))) {
                    unlink(public_path('categoryproduct/' . $category->image));
                }
            }
        } else {
            $data['image'] = $category->image;
        }

        // Update the category title and image
        $category->name = $data['name'];
        $category->image = $data['image'];
        $category->save();

        // Redirect to the category index page with a success message
        return redirect()->route('inventory.category.index', compact('data', 'category'));
    }
    public function destroy(CategoryProduct $category)
    {
        if ($category->image) {
            if (file_exists(public_path('categoryproduct/' . $category->image))) {
                unlink(public_path('categoryproduct/' . $category->image));
            }
        }
        // Delete the category
        $category->delete();

        // Redirect to the index view or any other desired action
        return redirect()->route('inventory.category.index')->with('success', 'Category deleted successfully.');
    }
}
