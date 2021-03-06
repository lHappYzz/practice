<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;

class DashboardController extends Controller
{
    //
    public function index(){
        $todayPosts = Post::getPostsByDaysAgo();
        $weekPosts = Post::getPostsByWeeksAgo();

        return view('admin.dashboard',[
            'posts' => Post::all(),
            'todayPosts' => $todayPosts,
            'compareWithLastWeekDay' => $this->getDiffInPercent($todayPosts->count(), Post::getPostsByDaysAgo(7)->count()),
            'weekPosts' => $weekPosts,
            'compareWithLastWeek' => $this->getDiffInPercent($weekPosts->count(), Post::getPostsByWeeksAgo(1)->count()),
            'lastThreePosts' => Post::getLatestPosts(),
        ]);
    }

    private function getDiffInPercent(float $firstElement, float $secondElement){
        if ($firstElement == 0) return [
            "className" => "text-success",
            "percentDiff" => "0%",
        ];

        if ($secondElement == 0) return [
            "className" => "text-success",
            "percentDiff" => "+100%",
        ];
        else {
            $percentDiff = $firstElement / $secondElement * 100 - 100;
        }

        if ($percentDiff - floor($percentDiff) != 0){
            $percentDiff = number_format($percentDiff, 2, '.', '');
        }

        if ($percentDiff > 0){
            $percentDiff = '+' . $percentDiff;
            $className = 'text-success';
        }
        else if ($percentDiff == 0){
            $className = 'text-success';
        }
        else {
            $className = 'text-danger';
        }

        return [
            "className" => $className,
            "percentDiff" => $percentDiff . '%',
        ];
    }
}
