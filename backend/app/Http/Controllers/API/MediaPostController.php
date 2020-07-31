<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\MediaPost;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
 

/**
 * MediaPostController class
 */
class MediaPostController extends Controller
{
    /**
     * Create a media post for a user
     *
     * @param $request array
     * @return \Illuminate\Http\JsonResponse
     */
    public function store_mediapost(Request $request)
    { 
        $responseCode = '';
        $responseMsg = '';
        $data = array();
        $filevalidate = 'required|mimes:jpeg,jpg,pdf,mp4';
        if ($request->file('file')!= null){ 
            $file = $request->file('file'); 
            $filemimes = ['image/jpeg','image/jpg','application/pdf']; 		
            $videomimes = ['video/mp4'];
            $userfile_mimetype = $file->getMimeType();
            //Validate Image/Pdf
            if(in_array($userfile_mimetype ,$filemimes)) { 			
                $filevalidate = 'required|mimes:jpeg,jpg,pdf|max:2048'; 		
            } 		
            //Validate video 		
            if (in_array($userfile_mimetype ,$videomimes)) { 		
                $filevalidate = 'required|mimes:mp4|max:10240'; 		
            } 
        }
        $validator = Validator::make($request->all(),[
            'file' => $filevalidate,
            'title' =>'required',
            'description' =>'required'
        ]);
        if ($validator->fails()) { 
            $data['errors'] = $validator->messages()->getMessages();
            $responseCode = 401;
            $responseMsg='Error, not a valid input format.';
        }else{
            try{
                $mediapost = new MediaPost;
                $random = Str::random(10);
                $fileName  = $random.time().'.'.$request->file->extension();
                $file_path = '/mediafiles/'.$fileName;
                $file_type = $request->file->extension();
                $request->file('file')->storeAs('mediafiles', $fileName);
                $mediapost->user_id = Auth::user()->id;
                $mediapost->file_name = $fileName;
                $mediapost->file_path = $file_path;
                $mediapost->file_type = $file_type;
                $mediapost->title = $request->title;
                $mediapost->tag = $request->tag;
                $mediapost->description = $request->description;
                $mediapost->save();
            }catch(\Exception $ex){
                $responseCode = 401;
                $responseMsg = $ex->getMessage();
            }
                $responseCode = 200;
                $responseMsg = 'Success';
        }
        return response()->json([
            'code' => $responseCode,
            'message' => $responseMsg,
            'data' => $data
        ],$responseCode)->header('Content-Type',"application/json");
    }
    
    /**
     * Fetch media posts for a user  
     * @return \Illuminate\Http\JsonResponse
     */
    public function mediaAll()
    {   
        $errorMsg = '';
        $data = array();

        if(Auth::user()->isAdmin == 1)
        {
            $data = MediaPost::all();
        }
        else{
            $data = MediaPost::where('user_id',Auth::user()->id)->get();
        }
        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $data
        ],200)->header('Content-Type',"application/json");
    }
    /**
     * Fetch media posts for a user based on uuid
     *
     * @param $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show_mediapost($user_id)
    { 
        $data = array();
        if($user_id =='') {
            $data['errors'] = "User uuid is required";
            $responseCode = 401;
        }else{

            $user = User::where('uuid',$user_id)->first();

            if(!empty($user)) {
                $data = $user->mediaPost()->get();
                return response()->json([
                    'code' => 200,
                    'message' => 'Success',
                    'data' => $data
                 ],200)->header('Content-Type',"application/json");
            }
            else{
                return response()->json([
                    'code' => 401,
                    'message' => 'User uuid is required',
                    'data' => []
                 ],401)->header('Content-Type',"application/json");
            }
        }

    }
   
    /**
     * Search media posts for a user
     *
     * @param $request array
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {   
        $query = $request['query'];
        $searchResults =[];
        
        if(Auth::user()->isAdmin == 1){
           $searchResults = MediaPost::Where('title','LIKE','%'.$query)
            ->orWhere('tag','LIKE','%'.$query)
            ->orWhere('description','LIKE','%'.$query.'%')        
            ->get();            
        }else
        {
            $searchResults = MediaPost::Where('title','LIKE','%'.$query)
                ->orWhere('tag','LIKE','%'.$query)
                ->orWhere('description','LIKE','%'.$query.'%')        
                ->get();
        } 
        return response()->json([            
            'message' => 'Success',
            'data' => $searchResults
            ]);
    }

}
