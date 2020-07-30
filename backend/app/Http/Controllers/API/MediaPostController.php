<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\MediaPost;
use Validator;
use Auth;
use App\User;


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
            'file' => 'required|mimes:jpeg,pdf,mp4',
            'title' =>'required',
            'description' =>'required'
        ]);

        if ($request->file('file')!= null){
            if($request->file->extension()=='mp4')
            {
                $validator = Validator::make($request->all(),[
                    'file' => 'required|mimes:mp4|max:10240'
                ]);
            }

            if($request->file->extension()=='jpeg,pdf'){
                $validator = Validator::make($request->all(),[
                    'file' => 'required|mimes:jpeg,pdf|max:1024'
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

                $mediapost->user_id = Auth::user()->id;
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
            $data['errors'] = "User uuid is required";
            $errorCode = 401;
        }else{
            $user = User::findByUUID($user_id);

            if(!empty($user)) {
                $data = $user->mediaPost()->get();
                return response()->json([
                    'code' => 200,
                    'message' => 'Success',
                    'data' => $data
                 ])->header('Content-Type',"application/json");
            }
            else{
                return response()->json([
                    'code' => 200,
                    'message' => 'Success',
                    'data' => []
                 ])->header('Content-Type',"application/json");
            }
        }

    }

    public function search(Request $request)
    {
        $query = $request['query'];

        $searchResults = MediaPost::where('user_id',Auth::user()->id)
        ->orWhere('title','LIKE','%'.$query)
        ->orWhere('tag','LIKE','%'.$query)
        ->orWhere('description','LIKE','%'.$query.'%')
        ->get();

        return response()->json([
            'message' => 'Success',
            'data' => $searchResults
            ]);
    }

}
