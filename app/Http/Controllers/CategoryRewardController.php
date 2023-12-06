<?php

namespace App\Http\Controllers;

use App\Models\CategoryReward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CategoryRewardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = CategoryReward::all();
        return view('reward.category-reward.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('reward.category-reward.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'image' => 'required|image|mimes:png,jpg,jpeg',
        ], [
            'name.required' => 'Nama Kategori Wajib Diisi!',
            'image.required' => 'Gambar Kategori Wajib Diupload!'
        ]);

        $data = $request->all();
        $data = request()->except(['_method', '_token']);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();

            // Move the uploaded file to a specified directory
            $image->move(public_path('category-reward'), $imageName);

            $data['image'] = $imageName;
        }

        // dd($data);

        CategoryReward::create($data);
        return redirect()->route('reward-category.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categoryReward = CategoryReward::find($id);
        return view('reward.category-reward.edit', compact('categoryReward'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $categoryReward = CategoryReward::find($id);
        $imageName = '';
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('category-reward'), $imageName);
            $data['image'] = $imageName;
            if ($categoryReward->image) {
                if (file_exists(public_path('category-reward/' . $categoryReward->image))) {
                    unlink(public_path('category-reward/' . $categoryReward->image));
                }
            }
        } else {
            $data['image'] = $categoryReward->image;
        }
        $categoryReward->update($data);
        return redirect()->route('reward-category.index')->with('success', 'Data berhasil di update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $categoryReward = CategoryReward::find($id);
        if ($categoryReward->image) {
            if (file_exists(public_path('category-reward/' . $categoryReward->image))) {
                unlink(public_path('category-reward/' . $categoryReward->image));
            }
        }

        $categoryReward->delete();

        return redirect()->route('reward-category.index')->with('success', 'Data berhasil dihapus');
    }

    public function syncRewardCategory()
    {
        try {
            $response = Http::get("http://apim.mizora.jewelry/api/radeem/category");
            $results = $response->json();

            $existingData = CategoryReward::pluck('name')->toArray();

            $insertData = [];

            foreach ($results['data'] as $result) {
                // dd($result);
                $image = isset($result["icon"]) ? $result["icon"] : null;
                $rewardCategoryData = [
                    "id" => $result["id"],
                    "image" => $image,
                ];
                if (!in_array($result['title'], $existingData)) {
                    $rewardCategoryData["name"] = $result["title"];
                    $insertData[] = $rewardCategoryData;
                } else {
                    CategoryReward::where('name', $result['title'])->update($rewardCategoryData);
                }
            }

            if (!empty($insertData)) {
                DB::table('category_reward')->insert($insertData);
            }

            return redirect()->route('reward-category.index')->with('success', 'Success Sycn Data');
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
