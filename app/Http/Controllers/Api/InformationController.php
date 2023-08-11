<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Information;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class InformationController extends Controller
{
    public function createInformation(Request $request){
        $validator = Validator::make($request->all(), [
            'userId' => 'required',
            'title' => 'required',
            'file' => 'required|mimes:png,jpg,pdf,word,doc,jpeg,docx',
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }

        $data = Information::create([
            'user_id' => $request->userId,
            'title' => $request->title,
            'body' => $request->body,
            'date' => Carbon::now(),
            'slug' => Str::slug($request->title),
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = Str::random(16) . '.' . $file->getClientOriginalExtension();

            if (in_array($file->getClientOriginalExtension(), ['jpg', 'gif', 'png', 'jpeg'])) {
                $file->storeAs('images', $fileName, 'public');
            } elseif (in_array($file->getClientOriginalExtension(), ['word', 'pdf', 'doc', 'docx'])) {
                $file->storeAs('files', $fileName, 'public');
            }

            $data->file = $fileName;
            $data->save();
        }

        return response()->json([
            'data' => $data,
            'message' => 'Information terbuat'
        ], 201);
    }

    public function deleteInformation($slug){
        $data = Information::where('slug', $slug)->first();
        if ($data) {
            if ($data->file) {
                $fileExtension = pathinfo($data->file, PATHINFO_EXTENSION);

                if (in_array($fileExtension, ['jpg', 'gif', 'png', 'jpeg'])) {
                    Storage::delete('public/images/' . $data->file);
                } elseif (in_array($fileExtension, ['word', 'pdf', 'doc', 'docx'])) {
                    Storage::delete('public/files/' . $data->file);
                }
            }
            $data->delete();

            return response()->json([
                'message' => 'Information dihapus'
            ], 200);
        }

        return response()->json([
            'message' => 'Information tidak ditemukan'
        ], 400);
    }

    public function editInformation(Request $request, $slug){
        $data = Information::where('slug', $slug)->first();

        if ($data) {
            $data->title = $request->title;
            $data->body = $request->body;
            $data->date = Carbon::now();
            $data->slug = Str::slug($request->title);
            $data->save();

            return response()->json([
                'data' => $data,
                'message' => 'Information dipudate'
            ], 200);
        } return response()->json([
            'message' => 'Information tidak ditemukan'
        ], 400);
    } 

    public function editInformationFile(Request $request, $slug){
        $data = Information::where('slug', $slug)->first();
        if ($data) {
            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:png,jpg,pdf,word,doc,jpeg,docx',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors()
                ], 400);
            }

            if ($data->file) {
                $fileExtension = pathinfo($data->file, PATHINFO_EXTENSION);

                if (in_array($fileExtension, ['jpg', 'gif', 'png', 'jpeg'])) {
                    Storage::delete('public/images/' . $data->file);
                } elseif (in_array($fileExtension, ['word', 'pdf', 'doc', 'docx'])) {
                    Storage::delete('public/files/' . $data->file);
                }
                $data->file = null;
                $data->save();
            }
            
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = Str::random(16) . '.' . $file->getClientOriginalExtension();
    
                if (in_array($file->getClientOriginalExtension(), ['jpg', 'gif', 'png', 'jpeg'])) {
                    $file->storeAs('images', $fileName, 'public');
                } elseif (in_array($file->getClientOriginalExtension(), ['word', 'pdf', 'doc', 'docx'])) {
                    $file->storeAs('files', $fileName, 'public');
                }
    
                $data->file = $fileName;
                $data->save();
                
                return response()->json([
                    'data' => $data,
                    'message' => 'Information file terupdate'
                ], 200);
            }
        }

        return response()->json([
            'message' => 'Information tidak ditemukan'
        ], 400);
    }
}
