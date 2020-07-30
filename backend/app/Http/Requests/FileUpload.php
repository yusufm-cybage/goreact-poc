<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class FileUpload extends FormRequest
{
    /**
     * Custom Failed Response
     *
     * Overrides the Illuminate\Foundation\Http\FormRequest
     * response function to stop it from auto redirecting
     * and applies a API custom response format.
     *
     * @param array $errors
     * @return JsonResponse
     */
    public function response(array $errors) {

        // Put whatever response you want here.
        return new JsonResponse([
            'status' => '422',
            'errors' => $errors,
        ], 422);
    }
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'file' => 'required|mimes:jpeg,pdf,mp4'
            
        ];

    return $rules;

       /*if(request()->file->extension()=='jpeg' || request()->file->extension()=='pdf'){
            return [
                'file' => 'required|mimes:jpeg,pdf|max:2048',
                'title' =>'required',
                'description' =>'required',
            ];
        }else if(request()->file->extension()=='mp4'){
            return [
                'file' => 'required|mimes:mp4|max:10240',
                'title' =>'required',
                'description' =>'required',
            ];
        }else {
            return [
               'file' => 'required|mimes:jpeg,pdf,mp4',
               'title' =>'required',
               'description' =>'required',
           ];
        }*/
        
    }
}
