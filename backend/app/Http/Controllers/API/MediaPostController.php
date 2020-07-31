<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\MediaPost;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\User;

/**
 * MediaPostController class
 */
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
        //dd($request->file('file'));
        $errorCode = '';
        $errorMsg = '';
        $data = array();
        $filevalidate = 'required|mimes:jpeg,jpg,pdf,mp4';
        if ($request->file('file')!= null){ 
            $file = $request->file('file'); 
            $imagemimes = ['image/jpeg','image/jpg','application/pdf']; 		
            $videomimes = ['video/mp4']; 
            
            //Validate Image/Pdf
            if(in_array($file->getMimeType() ,$imagemimes)) { 			
                $filevalidate = 'required|mimes:jpeg,jpg,pdf|max:2048'; 		
            } 		
            //Validate video 		
            if (in_array($file->getMimeType() ,$videomimes)) { 		
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
            $errorCode = 422;
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
            'message' => $errorMsg,
            'data' => $data
         ],$errorCode)->header('Content-Type',"application/json");

        
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
            $errorCode = 401;
        }else{

            $user = User::where('uuid',$user_id)->first();

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
        $call = '';
        if(Auth::user()->isAdmin == 1)
        {
           $searchResults = MediaPost::Where('title','LIKE','%'.$query)
            ->orWhere('tag','LIKE','%'.$query)
            ->orWhere('description','LIKE','%'.$query.'%')        
            ->get();
            $call = 'if';
        }
        else{
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
