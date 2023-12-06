<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = Slider::all();
        return view('pages.slider.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.slider.create');
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
            'url' => 'required|url',
            'image' => 'required|image|mimes:png,jpg,jpeg',
        ]);

        $data = $request->all();
        $data = request()->except(['_method', '_token']);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();

            // Move the uploaded file to a specified directory
            $image->move(public_path('sliders'), $imageName);

            $data['image'] = $imageName;
        }

        // dd($data);

        Slider::create($data);
        return redirect()->route('slider.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function show(Slider $slider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function edit(Slider $slider)
    {
        return view('pages.slider.edit', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slider $slider)
    {
        $data = $request->all();
        $imageName = '';
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('sliders'), $imageName);
            $data['image'] = $imageName;
            if ($slider->image) {
                if (file_exists(public_path('sliders/' . $slider->image))) {
                    unlink(public_path('sliders/' . $slider->image));
                }
            }
        } else {
            $imageName = $slider->image;
        }
        $slider->update($data);
        return redirect()->route('slider.index')->with('success', 'Data berhasil di update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slider $slider)
    {
        if ($slider->image) {
            if (file_exists(public_path('sliders/' . $slider->image))) {
                unlink(public_path('sliders/' . $slider->image));
            }
        }

        $slider->delete();

        return redirect()->route('slider.index')->with('success', 'Data berhasil dihapus');
    }
}
