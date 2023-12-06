<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = Location::all();
        return view('pages.location.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.location.create');
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
            'img' => 'required|mimes:png,jpg,jpeg',
            'name' => 'required',
            'description' => 'required',
            'location' => 'required',
            'street1' => 'required',
            'street2' => 'required',
            'postal_code' => 'required|numeric',
            'lat' => 'required',
            'lng' => 'required',
            'phone' => 'required',
            'whatsapp' => 'required',
            'day_of_week' => 'required',
            'start_time' => 'required',
            'end_time' => 'required'
        ]);
        $data = $request->all();
        $data = request()->except(['_method', '_token']);

        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $imageName = time() . '.' . $image->extension();

            // Move the uploaded file to a specified directory
            $image->move(public_path('locations'), $imageName);

            $data['img'] = $imageName;
        }
        Location::create($data);
        return redirect()->route('location.index')->with('success', 'Berhasil menambah data');
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
    public function edit(Location $location)
    {
        return view('pages.location.edit', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Location $location)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'location' => 'required',
            'street1' => 'required',
            'street2' => 'required',
            'postal_code' => 'required|numeric',
            'lat' => 'required',
            'lng' => 'required',
            'phone' => 'required',
            'whatsapp' => 'required',
            'day_of_week' => 'required',
            'start_time' => 'required',
            'end_time' => 'required'
        ]);
        $data = $request->all();

        $imageName = '';
        if ($request->hasFile('img')) {
            $imageName = time() . '.' . $request->img->extension();
            $request->img->move(public_path('locations'), $imageName);
            $data['img'] = $imageName;
            if ($location->img) {
                if (file_exists(public_path('locations/' . $location->img))) {
                    unlink(public_path('locations/' . $location->img));
                }
            }
        } else {
            $data['img'] = $location->img;
        }

        $location->update($data);
        return redirect()->route('location.index')->with('success', 'Berhasil update data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
        if ($location->img) {
            if (file_exists(public_path('locations/' . $location->img))) {
                unlink(public_path('locations/' . $location->img));
            }
        }

        $location->delete();
        return redirect()->route('location.index')->with('success', 'Berhasil menghapus data');
    }
}
