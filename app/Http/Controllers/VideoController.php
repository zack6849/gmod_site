<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VideoController extends Controller
{
    //

    public function play(Request $request){
        $url = $request->video;
        $pattern = "#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be\/)[^&\n]+#";
        $matches = [];
        preg_match_all($pattern, $url, $matches);
        if(empty($matches)){
            abort("Failed to find video ID");
        }
        return view('video', ['id' => $matches[0][0]]);
    }

    public function title(Request $request){
        $url = $request->video;
        $pattern = "#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be\/)[^&\n]+#";
        $matches = [];
        preg_match_all($pattern, $url, $matches);
        $video_id = $matches[0][0];
        $api_key = trim(env('YOUTUBE_API_KEY'));
        $response = Http::get("https://www.googleapis.com/youtube/v3/videos", ["part" => "snippet", "id" => $video_id, "key"=> $api_key]);
        return $response->json()['items'][0]['snippet']['title'];
    }
}
