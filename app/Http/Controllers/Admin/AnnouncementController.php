<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
class AnnouncementController extends Controller
{
    public function index()
    {
        return view('admin.announcement.index');
    }
    public function list(Request $request)
    {
        $user = Announcement::get();
        return DataTables::of($user)->make(true);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|mimes:jpg,png,jpeg',
        ]);
        if(!$validator->fails()){
            $msg ="";
            if($request->id && $request->id != "")
            {
                $announcement = Announcement::where('id',$request->id)->first();
                $msg = "Announcement Updated Successfully";
            }else{
                $announcement = new Announcement();
                $msg = "Announcement Created Successfully";
            }
            $announcement->title = $request->title;
            $announcement->description = $request->description;
            if(isset($request->image) && $request->image != "")
            {
                $attachment = $request->image;
                $attachment_name = time().".".$attachment->getClientOriginalExtension();
                $attachment->move(public_path('uploads/announcement'), $attachment_name);
                $announcement->attachment = 'uploads/announcement/'.$attachment_name;
            }
            if ($announcement->save())
            {
                $response_data = ["success" => 1, "message" => $msg];
            }else{
                $response_data = ["success" => 0, "message" => "Something went wrong"];
            }
        }else{
            $errors = array_values(Arr::dot($validator->errors()->toArray()));
            $response_data = ["success" => 0, "message" => "validation error" , "error" => $errors];
        }
        return response()->json($response_data);
    }
    public function get(Request $request)
    {
        if($request->id != ""){
            $announcement = Announcement::where('id',$request->id)->first();
            $response_data = ["success" => 1, "message" => "Announcement fetched successfully" , "data" => $announcement];
        }else{
            $response_data = ["success" => 0, "message" => "ID Not Found" ];
        }
        return response()->json($response_data);
    }  
    public function delete(Request $request)
    {
        if($request->id != ""){
            $announcement = Announcement::where('id',$request->id)->delete();
            $response_data = ["success" => 1, "message" => "Announcement Deleted successfully" , "data" => $announcement];
        }else{
            $response_data = ["success" => 0, "message" => "ID Not Found" ];
        }
        return response()->json($response_data);
    }   
    public function getAnnouncement()
    {
        $announcement = Announcement::all();
        $response_data = ["success" => 1, "message" => "Announcement fetched successfully" , "data" => $announcement];
        return response()->json($response_data);
    }
}
