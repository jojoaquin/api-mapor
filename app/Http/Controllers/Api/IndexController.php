<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Information;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class IndexController extends Controller
{
    public function news(Request $request){
        $page = $request->input('page', 1); // Mengambil nomor halaman dari permintaan
    
        $keyCache = 'data_summary_page_' . $page;
        $filteredKeyCache = 'filteredData_summary_page_' . $page;
    
        $data = Cache::get($keyCache);
        $filteredData = Cache::get($filteredKeyCache);
    
        if ($data && $filteredData) {
            return response()->json([
                'dataPage' => $data,
                'dataDetail' => $filteredData,
                'message' => 'Information diterima'
            ], 200);
        }
    
        $data = Information::select(['id', 'title', 'file', 'body', 'slug', 'date'])->latest()->paginate(6);
    
        $filteredData = [];
        foreach ($data as $item) {
            $fileExtension = pathinfo($item->file, PATHINFO_EXTENSION);
        
            if (in_array($fileExtension, ['pdf', 'doc', 'docx', 'word'])) {
                $filteredData[] = [
                    'id' => $item->id,
                    'title' => $item->title,
                    'body' => $item->body,
                    'slug' => $item->slug,
                    'date' => $item->date
                ];
            } else {
                $filteredData[] = [
                    'id' => $item->id,
                    'title' => $item->title,
                    'file' => $item->file,
                    'body' => $item->body,
                    'slug' => $item->slug,
                    'date' => $item->date
                ];
            }
        }
    
        Cache::put($keyCache, $data, 60 * 1);
        Cache::put($filteredKeyCache, $filteredData, 60 * 1);
    
        if ($filteredData == []) {
            return response()->json([
                'message' => 'Data tidak ada!!'
            ], 401);
        } else {
            return response()->json([
                'dataPage' => $data,
                'dataDetail' => $filteredData,
                'message' => 'Information diterima'
            ], 200);
        }
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

    public function allNews(){
        $data = Information::select(['title', 'slug'])->latest()->get();


        if ($data) {
            return response()->json([
                'data' => $data,
                'message' => 'Information diterima'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data tidak ada!!'
            ], 401);
        }
    }
}
