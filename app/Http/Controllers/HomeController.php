<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($category = null)
    {
        $mainHeading = 'Recent Deals';
        $oCollection = Post::where('status',1)->orderBy('created_at','desc');
        if($category) {
            $mainHeading = $category;
            $catId = Category::where('slug',$category)->pluck('id');
            if(empty($catId[0])) {
                return redirect()->route('home');
            }
            $oCollection->where('category_id',$catId[0]);
        }

        $posts = $oCollection->paginate(env('pagination_limit',10));

        // process posts

        foreach ($posts as $post) {
            $now = Carbon::now();
            $postTime = $post->created_at;
            if($now->diffInMinutes($postTime) < 60) {
                $diff = $now->diffInMinutes($postTime);
                $post->formattedDateString =  $diff .' '. $this->pluralForm($diff,'minute').' ago';
            }else if($now->diffInHours($postTime) <= 24) {
                $diff = $now->diffInHours($postTime);
                $post->formattedDateString =  $diff .' '. $this->pluralForm($diff,'hour').' ago';
            }else if($now->diffInDays($postTime) < 7){
                $diff = $now->diffInDays($postTime);
                $post->formattedDateString =  $diff .' '. $this->pluralForm($diff,'day').' ago';
            }else if($now->diffInWeeks($postTime) < 4){
                $diff = $now->diffInWeeks($postTime);
                $post->formattedDateString =  $diff .' '. $this->pluralForm($diff,'week').' ago';
            }else{
                $post->formattedDateString = date('d-M-Y', strtotime($post->created_at));
            }
            $post->body = htmlspecialchars_decode($post->body);
        }
//        dd($posts->nextPageUrl());
        return view('main.post',[
            'title' => 'Deals Khojo',
            'categories' => Category::all(),
            'posts' => $posts,
            'main_heading' => $mainHeading,
            'recentDeals' => $this->getRecentDeals(5)
        ]);
    }


    public function viewDeal($slug) {

        if($slug) {
            $post = Post::where('slug',$slug)->get();
            if(!empty($post->toArray())) {
                $post = $post[0];
                $now = Carbon::now();
                $postTime = $post->created_at;
                if($now->diffInMinutes($postTime) < 60) {
                    $diff = $now->diffInMinutes($postTime);
                    $post->formattedDateString =  $diff .' '. $this->pluralForm($diff,'minute').' ago';
                }else if($now->diffInHours($postTime) <= 24) {
                    $diff = $now->diffInHours($postTime);
                    $post->formattedDateString =  $diff .' '. $this->pluralForm($diff,'hour').' ago';
                }else if($now->diffInDays($postTime) < 7){
                    $diff = $now->diffInDays($postTime);
                    $post->formattedDateString =  $diff .' '. $this->pluralForm($diff,'day').' ago';
                }else if($now->diffInWeeks($postTime) < 4){
                    $diff = $now->diffInWeeks($postTime);
                    $post->formattedDateString =  $diff .' '. $this->pluralForm($diff,'week').' ago';
                }else{
                    $post->formattedDateString = date('d-M-Y', strtotime($post->created_at));
                }
                $post->body = htmlspecialchars_decode($post->body);

                return view('main.post_detail',[
                    'title' => 'Deals Khojo',
                    'categories' => Category::all(),
                    'post' => $post,
                    'main_heading' => 'breadcrumb',
                    'recentDeals' => $this->getRecentDeals(5)
                ]);
            }
            return abort(404);
        }

        return redirect()->route('listPosts');
    }

    public function search() {
        $params = Input::all();
        if($params['q'] != '' || $params['q'] != null) {
            $mainHeading = 'Search Page Results';
            $oCollection = Post::where('status',1)->orderBy('created_at','desc')->where('title','like','%'.$params['q'].'%');
            $posts = $oCollection->paginate(env('pagination_limit',10));

            // process posts

            foreach ($posts as $post) {
                $now = Carbon::now();
                $postTime = $post->created_at;
                if($now->diffInMinutes($postTime) < 60) {
                    $diff = $now->diffInMinutes($postTime);
                    $post->formattedDateString =  $diff .' '. $this->pluralForm($diff,'minute').' ago';
                }else if($now->diffInHours($postTime) <= 24) {
                    $diff = $now->diffInHours($postTime);
                    $post->formattedDateString =  $diff .' '. $this->pluralForm($diff,'hour').' ago';
                }else if($now->diffInDays($postTime) < 7){
                    $diff = $now->diffInDays($postTime);
                    $post->formattedDateString =  $diff .' '. $this->pluralForm($diff,'day').' ago';
                }else if($now->diffInWeeks($postTime) < 4){
                    $diff = $now->diffInWeeks($postTime);
                    $post->formattedDateString =  $diff .' '. $this->pluralForm($diff,'week').' ago';
                }else{
                    $post->formattedDateString = date('d-M-Y', strtotime($post->created_at));
                }
                $post->body = htmlspecialchars_decode($post->body);
            }
//        dd($posts->nextPageUrl());
            return view('main.post',[
                'title' => 'Deals Khojo',
                'categories' => Category::all(),
                'posts' => $posts,
                'main_heading' => $mainHeading,
                'recentDeals' => $this->getRecentDeals(5)
            ]);
        }
        return redirect()->route('home');
    }

    public function addViewCount(Request $request) {
        $params = Input::all();
        if(isset($params['pid']) && $params['pid'] != null){
            $post = Post::find($params['pid']);
            $post->views += 1;
            $post->save();
            return [
              'status' => 12200,
                'message' => 'success'
            ];
        }
        abort(404);
    }

    public function getRecentDeals($numberOfDeals) {
        $deals = Post::where('status',1)->orderBy('created_at','desc')->limit($numberOfDeals)->select('title','slug')->get();
        return $deals;
    }

    public function contactUs() {
        $mainHeading = 'Contact Us';
        return view('general.contact',[
            'title' => 'Deals Khojo',
            'categories' => Category::all(),
            'main_heading' => $mainHeading,
            'recentDeals' => $this->getRecentDeals(5)
        ]);
    }

    public function postContactUs(Request $request) {
        $params = Input::all();
        //print_r($params);exit;
        $to = ['dealkhojo53@gmail.com'];
        $subject = 'Contact Support';

        $data = array();
        $data['name'] = $params['name'];
        $data['email'] = $params['email'];
        $data['message'] = $params['message'];

        Mail::send('mail.contact', ['data' => $data] , function($message) use ($to,$subject)
        {
            $message->subject($subject);
            foreach ($to as $key => $value) {
                $message->to($value, $key);
            }
        });

        return [
            'status' => 12200,
            'message' => "Thanks for contacting us.We shall get back to you shortly."
        ];
    }

    public function termsOfService() {
        $mainHeading = 'Terms of service';
        return view('general.terms',[
            'title' => 'Deals Khojo - Terms of Service',
            'categories' => Category::all(),
            'main_heading' => $mainHeading,
            'recentDeals' => $this->getRecentDeals(5)
        ]);
    }

    private function pluralForm($number,$string) {
        if($number > 1) {
            return $string . 's';
        }
        return $string;
    }


}
