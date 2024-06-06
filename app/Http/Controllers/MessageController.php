<?php

namespace App\Http\Controllers;

use App\Models\CategoryMessage;
use App\Models\Membership;
use App\Models\Message;
use DateTime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class MessageController extends Controller
{

    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $counter = 1;
            $group = Message::with(['category', 'user'])->orderBy('id', 'desc')->get();

            return DataTables::of($group)
                ->addIndexColumn()
                ->addColumn('no', function () use (&$counter) {
                    return $counter++;
                })
                ->addColumn('user_id', function ($row) {
                    return $row->user->customer_name ?? 'ALL';
                })
                ->addColumn('category', function ($row) {
                    return $row->category->name;
                })
                ->addColumn('image', function ($row) {
                    return "<img src='" . $row->image . "' width='100' height='100' /> ";
                })
                ->addColumn('description', function ($row) {
                    return '<div class="line-clamp-2 nowrap" style="width: 300px;">' . $row->description . '</div>';
                })
                ->addColumn('url', function ($row) {
                    return '<div class="line-clamp-2 nowrap" style="width: 300px;">' . $row->url_cta . '</div>';
                })
                ->addColumn('label', function ($row) {
                    return $row->label_cta;
                })
                ->addColumn('datetime', function ($row) {
                    return Carbon::parse($row->created_at)->format('Y-m-d H:i:s');
                })
                ->addColumn('action', function ($row) {
                    $btn = "
                    <a onclick='runFunction(event,`open_edit`,`$row->id`)' href='javascript:void(0)'><i class='ik ik-edit f-16 mr-15 text-green'></i></a>
                    <a onclick='runFunction(event,`open_delete`,`$row->id`)' href='javascript:void(0)'><i class='ik ik-trash-2 f-16 text-red'></i></a>
                    ";
                    return $btn;
                })
                ->rawColumns(['image', 'description', 'url', 'action'])
                ->make(true);
        }
        $users = DB::table('customers')->get();
        return view('pages.message', compact('users'));
    }

    public function getOption()
    {
        $thisCategory = CategoryMessage::get();

        foreach ($thisCategory as $category) {
            $array[] = $category->name;
        }

        return response()->json([
            'data' => true,
            'array' => $array
        ], 200);
    }

    public function create(Request $request)
    {
        // VALIDATE IMAGE
        // required|image|mimes:jpg,jpeg,png|max:5120
        //required|date|date_format:Y-m-d\TH:i

        $rules = [
            'image'         => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
            'category'      => 'required|string',
            'title'         => 'required|string|max:220',
            'description'   => 'required|string',
            'url'           => 'required|string',
            'label'         => 'required|string',
            'datetime'      => 'required',
            'user'          => 'nullable|exists:customers,id'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = $request->category;

        $dateTime = new DateTime($request->expiry);
        $cnvDateTime = $dateTime->format('Y-m-d H:i:s');

        $categoryID = CategoryMessage::where('name', $category)->first();
        $thisID = $categoryID->id;

        $img = null;
        if ($files = $request->file('image')) {
            $destinationPath = '/var/www/mizoraadm/public/images/message/';
            if (!file_exists($destinationPath)) {
                File::makeDirectory($destinationPath, 755, true);
            }
            $img = 'image_notif_' . date('YmdHis') . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $img);
        }

        Message::create([
            'category_message_id'   => $thisID,
            'title'                 => $request->title,
            'image'                 => $img,
            'description'           => $request->description,
            'url_cta'               => $request->url,
            'label_cta'             => $request->label,
            'datetime'              => $cnvDateTime,
            'user_id'               => $request->user,
        ]);

        return response()->json([
            'data' => true,
            'notif' => '[CREATE] message success!'
        ], 200);
    }

    public function edit(Request $request)
    {
        $parameter = $request->parameter;
        if ($parameter == "get") {
            $thisData = Message::with(['category'])->where('id', $request->value)->first();

            $array['category'] = $thisData->category->name;
            $array['user'] = $thisData->user_id;
            $array['title'] = $thisData->title;
            $array['description'] = $thisData->description;
            $array['url'] = $thisData->url_cta;
            $array['label'] = $thisData->label_cta;
            $array['datetime'] = Carbon::parse($thisData->datetime)->format('Y-m-d H:i:s');

            return response()->json([
                'data' => true,
                'array' => $array
            ], 200);
        } else if ($parameter == "save") {
            $rules = [
                'image'         => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
                'category'      => 'required|string',
                'title'         => 'required|string',
                'description'   => 'required|string',
                'url'           => 'required|string',
                'label'         => 'required|string',
                'datetime'      => 'required',
                'user'          => 'nullable|exists:customers,id'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $category = $request->category;

            $dateTime = new DateTime($request->expiry);
            $cnvDateTime = $dateTime->format('Y-m-d H:i:s');

            $categoryID = CategoryMessage::where('name', $category)->first();
            $thisID = $categoryID->id;
            $message = Message::find($request->value);
            if (!$message) {
                return response()->json([
                    'message' => 'Data Not Found!',
                ], 404);
            }

            $img = $message->getRawOriginal('image');
            if ($files = $request->file('image')) {
                $destinationPath = '/var/www/mizoraadm/public/images/message/';
                if (!empty($img) && file_exists($destinationPath . $img)) {
                    File::delete($destinationPath . $img);
                }
                if (!file_exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 755, true);
                }
                $img = 'image_notif_' . date('YmdHis') . "." . $files->getClientOriginalExtension();
                $files->move($destinationPath, $img);
            }
            $thisData = $message->update([
                'category_message_id'   => $thisID,
                'title'                 => $request->title,
                'description'           => $request->description,
                'url_cta'               => $request->url,
                'label_cta'             => $request->label,
                'datetime'              => $cnvDateTime,
                'user_id'               => $request->user,
                'image'                 => $img,
            ]);

            return response()->json([
                'data' => true,
                'notif' => '[EDIT] message success!'
            ], 200);
        }
    }

    public function delete(Request $request)
    {
        $parameter = $request->parameter;
        if ($parameter == "get") {
            $thisData = Message::with(['category'])->where('id', $request->value)->first();

            $array['category'] = $thisData->category->name;
            $array['user'] = $thisData->user_id;
            $array['title'] = $thisData->title;
            $array['description'] = $thisData->description;
            $array['url'] = $thisData->url_cta;
            $array['label'] = $thisData->label_cta;
            $array['datetime'] = Carbon::parse($thisData->datetime)->format('Y-m-d H:i:s');

            return response()->json([
                'data' => true,
                'array' => $array
            ], 200);
        } else if ($parameter == "confirm") {
            $checkImage = Message::where('id', $request->value)->first();
            $imageDB = $checkImage->getRawOriginal('image');
            if ($checkImage) {
                if ($imageDB && file_exists('/var/www/mizoraadm/public/images/message/' . $imageDB)) {
                    File::delete('/var/www/mizoraadm/public/images/message/' . $imageDB);
                    // unlink($imageDB);
                }
                $checkImage->delete();
            }

            return response()->json([
                'data' => true,
                'notif' => '[DELETE] message success!'
            ], 200);
        }
    }
}
