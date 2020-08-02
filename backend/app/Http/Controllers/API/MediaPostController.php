<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\MediaPost;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
    public function storeMediaPost(Request $request)
    {          
        $responseCode = '';
        $responseMsg = '';
        $data = array();
        $filevalidate = 'required|mimes:jpeg,jpg,pdf,mp4';
        if ($request->file('file') != NULL)
        { 
            $file = $request->file('file'); 
            $filemimes = ['image/jpeg', 'image/jpg', 'application/pdf']; 		
            $videomimes = ['video/mp4'];
            $userfile_mimetype = $file->getMimeType();
            //Validate Image/Pdf
            if(in_array($userfile_mimetype, $filemimes))
            { 			
                $filevalidate = 'required|mimes:jpeg,jpg,pdf|max:2048'; 		
            } 		
            //Validate video 		
            if (in_array($userfile_mimetype, $videomimes))
            { 		
                $filevalidate = 'required|mimes:mp4|max:10240'; 		
            } 
        }
        $validator = Validator::make($request->all(), [
            'file'       => $filevalidate,
            'title'      => 'required',
            'description'=> 'required'
        ]);
        if($validator->fails()) 
        { 
            $data['errors'] = $validator->messages()->getMessages();
            $responseCode = 401;
            $responseMsg='Error! file type not allowed, jpg,pdf max(5MB) or mp4(max 10MB) only';
        }
        else{
            try{
                $mediapost = new MediaPost;
                $random = Str::random(10);
                $file   = $request->file('file');
                $fileName  = $random.time().'.'.$file->extension();
                $fileType = $file->extension();
                //$file->storeAs('mediafiles', $fileName);
                $file->move(public_path('/mediafiles'), $fileName);
                $mediapost->user_id = Auth::user()->id;
                $mediapost->file_name = $fileName;                
                $mediapost->file_type = $fileType;
                $mediapost->title = trim($request->title);
                $mediapost->tag = trim($request->tag);
                $mediapost->description = trim($request->description);
                $mediapost->save();
            }
            catch(\Exception $ex){
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
        if(Auth::user()->isAdmin == 1){
            $data = MediaPost::with('user:uuid,name')->get();
        }else{
            $data = MediaPost::with('user:uuid,name')->where('user_id', Auth::user()->id)->get();
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
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function showMediaPost($userId)
    { 
        $user = User::where('uuid',$userId)->first();
        if(!empty($user)) {
            $data = $user->mediaPosts()->get();
            return response()->json([
                'code' => 200,
                'message' => 'Success',
                'data' => $data
             ],200)->header('Content-Type',"application/json");
        }
        else{
            return response()->json([
                'code' => 404,
                'message' => 'uuid mismatched or not found',
                'data' => []
             ],404)->header('Content-Type',"application/json");
        }
    }   
    /**
     * Search media posts for a user
     *
     * @param $request array
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(SearchRequest $request)
    { 
        $query = $request['query'];
        $searchResults = [];
        if(Auth::user()->isAdmin == 1){
             $searchResults = MediaPost::searchByAdmin($query);
        }else{
           $searchResults = MediaPost::search(Auth::user()->id, $query);
        } 
        return response()->json([            
            'message' => 'Success',            
            'data' => count($searchResults) > 0 ? $searchResults : 'No search result found'
            ],200);
    }
}
