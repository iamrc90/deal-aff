<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class PostsController extends Controller
{
    public function addPostForm() {
    	$categories = Category::where('status',1)->select('name','id')->get();
    	return view('posts.create_post',[
    		'categories' => $categories
    	]);
    }

    public function listPosts() {
    	return view('posts.posts');
    }

    public function getPostsData() {
    	$aRequestData = Input::all();

        $columns = array(
            // datatable column index  => database column name
            0 =>'posts.id',
            1 => 'posts.title',
            2 => 'category',
            3 => 'posts.status',
        );
        $oCollection = DB::table('posts')
            // join roles
            ->join('categories', 'posts.category_id', '=', 'categories.id')
            ->select('posts.id','posts.title as title','categories.name as category','posts.status as status');
        $totalData = $totalFiltered = $oCollection->distinct('posts.id')->count('posts.id');
        // searching data
        if( !empty($aRequestData['search']['value']) ) {
            $oCollection = $oCollection->where('posts.title','like','%'.$aRequestData['search']['value'].'%');
        }
        $totalFiltered = $oCollection->distinct('posts.id')->count('posts.id');
        $aData =  $oCollection->orderBy($columns[$aRequestData['order'][0]['column']],$aRequestData['order'][0]['dir'])->limit($aRequestData['length'])->offset($aRequestData['start'])->get();
        return array(
            "draw"            => intval( $aRequestData['draw'] ),
            "recordsTotal"    => intval( $totalData ),  // total number of records
            "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data"            => $aData   //  data array
        );
    }

    public function savePost(Request $request) {
        $rules = [
            'title' => 'required',
            'body' => 'required',
            'category' => 'required'
        ];
        $this->validate($request, $rules);

    	$aRequestParams = Input::all();
    	$post = new Post();
    	$post->title = $aRequestParams['title'];
        $post->body = htmlentities($aRequestParams['body']);
    	$post->category_id = $aRequestParams['category'];
    	$post->user_id = Auth::user()->id;
    	$post->save();
        return redirect()->route('listPosts');
    }

    public function updateStatus(Request $request) {
        $rules = [
            'pid' => 'required | numeric ',
            'action' => 'required'
        ];
        $this->validate($request, $rules);
        $aRequestData = Input::all();
        $result = 0;
        $post = Post::find($aRequestData['pid']);

        if(!empty($post)) {
            if($aRequestData['action'] == 'publish') {
                // change status to 1
                $post->status = 1;
            }else if($aRequestData['action'] == 'unpublish'){
                //change status to 0
                $post->status = 0;
            }
            $post->save();
            $result = 1;
        }

        if($result == 1) {
            return [
              'status' => 12200,
               'message' => 'Success'
            ];
        }

        return [
            'status' => 12500,
            'message' => 'failed'
        ];

    }

    public function delete(Request $request){
        $rules = [
            'pid' => 'required | numeric'
        ];
        $this->validate($request,$rules);

        if(Post::destroy($request->pid)) {
            return [
                'status' => 12200,
                'message' => 'Success'
            ];
        }

        return [
            'status' => 12500,
            'message' => 'failed'
        ];
    }

    public function editPostForm($id) {
        $categories = Category::where('status',1)->select('name','id')->get();
        if($id) {
            $post = Post::find($id);
            if($post) {
                return view('posts.edit_post',[
                    'categories' => $categories,
                    'post' => $post
                ]);
            }
        }

        return redirect()->route('listPosts');

    }

    public function updatePost(Request $request) {
        $rules = [
            'id' => 'required',
            'title' => 'required',
            'body' => 'required',
            'category' => 'required'
        ];
        $this->validate($request, $rules);

        $aRequestParams = Input::all();
        $post = Post::find($aRequestParams['id']);
        $post->title = $aRequestParams['title'];
        $post->body = htmlentities($aRequestParams['body']);
        $post->category_id = $aRequestParams['category'];
        $post->user_id = Auth::user()->id;
        $post->save();
        return redirect()->route('listPosts');
    }

    public function viewPost($id) {

        if($id) {
            $post = Post::find($id);
            if($post) {
                $now = Carbon::now();
                $postTime = $post->updated_at;
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
                return view('posts.post_detail',[
                    'post' => $post
                ]);
            }
            return abort(404);
        }

        return redirect()->route('listPosts');

    }

    private function pluralForm($number,$string) {
        if($number > 1) {
            return $string . 's';
        }
        return $string;
    }
}
