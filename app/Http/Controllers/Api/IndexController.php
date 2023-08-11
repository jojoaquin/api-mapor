<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Information;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function news(){
        $data = Information::select(['title', 'file', 'body'])->latest()->paginate(5);

        $filteredData = [];
        foreach ($data as $item) {
            $fileExtension = pathinfo($item->file, PATHINFO_EXTENSION);
        
             if (in_array($fileExtension, ['pdf', 'doc', 'docx', 'word'])) {
                $filteredData[] = [
                    'title' => $item->title,
                    'body' => $item->body,
                ];
            } else {
                $filteredData[] = [
                    'title' => $item->title,
                    'file' => $item->file,
                    'body' => $item->body,
                ];
            }
        }

        return response()->json([
            'dataPage' => $data,
            'dataDetail' => $filteredData,
            'message' => 'Information diterima'
        ], 200);
    }

    public function newsById($slug){
        $data = Information::where('slug', $slug)->first();

        if ($data) {
            return response()->json([
                'data' => $data,
                'message' => 'Information sukses'
            ]);
        } return response()->json([
            'data' => $data,
            'message' => 'Information gagal'
        ]);
    }
}
