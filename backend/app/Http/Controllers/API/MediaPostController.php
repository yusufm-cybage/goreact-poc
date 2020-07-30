<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\MediaPost;
use Validator;

class MediaPostController extends Controller
{
    /**
     * Create a media post for a user
     *
     * @param $request array
     * @return string
     */
    public function store_mediapost(Request $request)
    {
        $errorCode = '';
        $errorMsg = '';
        $validator = Validator::make($request->all(),[
            'file' => 'required|mimes:jpeg,jpg,pdf,mp4',
            'title' =>'required',
            'description' =>'required',
        ]);
        if ($request->file('file')!=null){
            if($request->file->extension()=='mp4'){
                $validator = Validator::make($request->all(),[
                    'file' => 'required|mimes:mp4|max:10240',
                ]); 
            }
            if($request->file->extension()=='jpeg,jpg,pdf'){
                $validator = Validator::make($request->all(),[
                    'file' => 'required|mimes:jpeg,pdf|max:1024',
                ]); 
            }
        }
        if ($validator->fails()) {
            $data['errors'] = $validator->messages()->getMessages();
            $errorCode = 401;
            $errorMsg='Error, not a valid input format.';
        }else{ 
                $mediapost = new MediaPost;
                $fileName  = time().'.'.$request->file->extension();  
                $file_path = public_path('mediafiles');
                $file_type = $request->file->extension();
                $request->file->move(public_path('mediafiles'), $fileName);
                
                $mediapost->user_id = 1;
                $mediapost->file_name = $fileName;
                $mediapost->file_path = $file_path;
                $mediapost->file_type = $file_type;
                $mediapost->title = $request->title;
                $mediapost->tag = $request->tag;
                $mediapost->description = $request->description; 
                $mediapost->save();
                
                $errorCode = 200;
                $errorMsg = 'Success';
       
                
        }
        return response()->json([
            'code' => $errorCode,
            'message' => $errorMsg
         ])->header('Content-Type',"application/json"); 
                
    }
    
    /**
     * Fetch media posts for a user
     *
     * @param $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show_mediapost($user_id)
    {
        $data = array();
        if ($user_id =='') {
            $data['errors'] = "User id is required";
            $errorCode = 401;
        }else{ 
            $mediapost = MediaPost::where('user_id', '=', $user_id)->get();
            if(!empty($mediapost)) {
                foreach ($mediapost as $media){
                    $tmpArr['file_name'] = $media->file_name;
                    $tmpArr['file_path'] = $media->file_path;
                    $tmpArr['file_type'] = $media->file_type;
                    $tmpArr['title'] = $media->title;
                    $tmpArr['tag'] = $media->tag;
                    $tmpArr['description'] = $media->description;
                    array_push($data, $tmpArr);
                }
            }
         return response()->json([
           'code' => 200,
           'message' => 'Success',
           'data' => $data
        ])->header('Content-Type',"application/json");
        }
        
    }
    
}
