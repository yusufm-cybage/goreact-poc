<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\MediaPost;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
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
        $fileValidate = 'required|mimes:jpeg,jpg,pdf|max:' . config('upload.MAXSIZE_2MB');
         
        if ($request->file('file') != NULL) 
        { 
            $file = $request->file('file'); 
            $fileMimes = ['image/jpeg', 'image/jpg', 'application/pdf']; 		
            $videoMimes = ['video/mp4'];
            $userFileMimetype = $file->getMimeType();
            //Validate Image/Pdf
            if (in_array($userFileMimetype, $fileMimes))
            { 			
                $fileValidate = 'required|mimes:jpeg,jpg,pdf|max:' . config('upload.MAXSIZE_2MB');
            } 		
            //Validate video 		
            if (in_array($userFileMimetype, $videoMimes))
            { 		
                $fileValidate = 'required|mimes:mp4|max:' . config('upload.MAXSIZE_10MB'); 		
            } 
        }

        $validator = Validator::make($request->all(), [
            'file'       => $fileValidate,
            'title'      => 'required',
            'description'=> 'required',
        ]);

        if ($validator->fails()) 
        { 
            $data['errors'] = $validator->messages()->getMessages();
            $responseCode = 422;
            $responseMsg = 'Error! file type not allowed, jpg, pdf or mp4 only';
        }
        else 
        {
            try 
            {
                $mediaPost = new MediaPost;
                $random = Str::random(10);
                $file   = $request->file('file');
                $fileName  = $random . time() . '.' . $file->extension();
                $fileType = $file->extension();                
                $file->move(config('upload.PATH'), $fileName);
                $mediaPost->user_id = Auth::user()->id;
                $mediaPost->file_name = $fileName;                
                $mediaPost->file_type = $fileType;
                $mediaPost->title = trim($request->title);
                $mediaPost->tag = trim($request->tag);
                $mediaPost->description = trim($request->description);
                $mediaPost->save();
            }
            catch (\Exception $ex)
            {
                $responseCode = 401;
                $responseMsg = $ex->getMessage();
            }

            $responseCode = 200;
            $responseMsg = 'Success';
        }

        return response()->json([
            'code' => $responseCode,
            'message' => $responseMsg,
            'data' => $data,
        ], $responseCode)->header('Content-Type', "application/json");
    }

    /**
     * Fetch media posts for a user  
     * @return \Illuminate\Http\JsonResponse
     */
    public function mediaAll()
    {   
        $errorMsg = '';
        $data = array();

        if (Auth::user()->isAdmin == 1)
        {
            $data = MediaPost::with('user:uuid,name')
            ->orderByDesc('created_at')
            ->get();
        }
        else
        {
            $data = MediaPost::with('user:uuid,name')
            ->where('user_id', Auth::user()->id)
            ->orderByDesc('created_at')
            ->get();
        }

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $data,
        ], 200)->header('Content-Type', "application/json");
    }

    /**
     * Fetch media posts for a user based on uuid
     *
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function showMediaPost($userId)
    { 
        $user = User::where('uuid', $userId)->first();
        if (!empty($user)) 
        {
            $data = $user->mediaPosts()->get();

            return response()->json([
                'code' => 200,
                'message' => 'Success',
                'data' => $data,
             ], 200)->header('Content-Type', "application/json");
        }
        else
        {
            return response()->json([
                'code' => 404,
                'message' => 'uuid mismatched or not found',
                'data' => [],
             ], 404)->header('Content-Type', "application/json");
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

        if (Auth::user()->isAdmin == 1)
        {
            $searchResults = MediaPost::searchByAdmin($query);
        }
        else
        {
            $searchResults = MediaPost::search(Auth::user()->id, $query);
        } 

        return response()->json([            
            'message' => 'Success',            
            'data' => $searchResults,
            ], 200);
    }
}
