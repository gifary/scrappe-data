<?php
/**
 * Created by PhpStorm.
 * User: gifary
 * Date: 8/20/18
 * Time: 8:35 AM
 */

namespace App\Http\Controllers;


use App\Http\Models\Asin;
use App\Http\Models\AsinComment;
use App\Http\Models\Tag;
use App\Jobs\ProcessScrapeData;
use Carbon\Carbon;
use Goutte\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => 'required'
        ]);
        //need to validate code to amazon
        $code = $request->code;
        $client = new Client();
        $client->setHeader('User-Agent', "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36");

        $crawler = $client->request('GET', 'https://www.amazon.com/product-reviews/'.$code);
        Log::debug('response code . '.$client->getInternalResponse()->getStatus());
        $validCode = $crawler->filter('#g')->each(function ($node) {
            Log::debug("Code not valid ".$node->text());
            return true;
        });

        if(empty($validCode)){
            $urlProduct = $crawler->filter('h1 > .a-link-normal')->each(function ($rating){
                return $rating->attr('href');
            });
            $data =$request->only(['code']);
            if(!empty($urlProduct)){
                $data['url'] = $urlProduct[0];
            }else{
                $data['url'] = "/product-reviews/".$request->code;
            }

            $asin= Asin::create($data);
            //attached job for getting data
            ProcessScrapeData::dispatch($asin)->delay(Carbon::now()->addMinutes(1))->onConnection('database');
            return redirect()->route('home')->with('message', 'Please click this <a href="view/'.$asin->id.'"> link </a> for see the result');
        }else{
            return redirect()->route('home')->with('error', 'ASIN Code not valid');
        }
    }

    public function view($id,$tag=''){
        //find code
        $asin = Asin::with("comments")->find($id);

        if(!empty($asin)){
            $child_asin = $asin->comments()->groupBy('asin_child')->get();
            $whereTag = function ($q) use($tag){
                $q->where('name',$tag);
            };

            $total_verified_five_star = $asin->comments()->where(["is_verified"=>true,"review_score"=>5])->whereHas('tags',$whereTag)->count();
            $total_unverified_five_star = $asin->comments()->where(["is_verified"=>false,"review_score"=>5])->whereHas('tags',$whereTag)->count();

            $total_verified_four_star = $asin->comments()->where(["is_verified"=>true,"review_score"=>4])->whereHas('tags',$whereTag)->count();
            $total_unverified_four_star = $asin->comments()->where(["is_verified"=>false,"review_score"=>4])->whereHas('tags',$whereTag)->count();

            $total_verified_three_star = $asin->comments()->where(["is_verified"=>true,"review_score"=>3])->whereHas('tags',$whereTag)->count();
            $total_unverified_three_star = $asin->comments()->where(["is_verified"=>false,"review_score"=>3])->whereHas('tags',$whereTag)->count();

            $total_verified_two_star = $asin->comments()->where(["is_verified"=>true,"review_score"=>2])->whereHas('tags',$whereTag)->count();
            $total_unverified_two_star = $asin->comments()->where(["is_verified"=>false,"review_score"=>2])->whereHas('tags',$whereTag)->count();

            $total_verified_one_star = $asin->comments()->where(["is_verified"=>true,"review_score"=>1])->whereHas('tags',$whereTag)->count();
            $total_unverified_one_star = $asin->comments()->where(["is_verified"=>false,"review_score"=>1])->whereHas('tags',$whereTag)->count();

            $total_verified = $total_verified_five_star+$total_verified_four_star+$total_verified_three_star+$total_verified_two_star+$total_verified_one_star;
            $total_unverified = $total_unverified_five_star+$total_unverified_four_star+$total_unverified_three_star+$total_unverified_two_star+$total_unverified_one_star;

            return view('detail',compact('asin','id','child_asin',
                'total_unverified_five_star','total_verified_five_star',
                'total_unverified_four_star','total_verified_four_star',
                'total_unverified_three_star','total_verified_three_star',
                'total_unverified_two_star','total_verified_two_star',
                'total_unverified_one_star','total_verified_one_star',
                'total_verified','total_unverified','tag'));
        }else{
            return redirect()->route('home')->with('error', 'Code not valid');
        }
    }

    public function data(Datatables $datatables,Request$request,$id,$tag=''){
        ini_set('memory_limit', '-1');

        $asinComment = AsinComment::where('asin_id',$id);

        if($tag!=''){
            $asinComment = $asinComment->whereHas('tags',function ($q) use($tag){
                $q->where('name',$tag);
            });
        }

        if($request->has("asin_child")){
            if(!empty($request->get("asin_child"))){
                $asinComment = $asinComment->where("asin_child",$request->get("asin_child"));
            }
        }
        $review_score=array();
        if($request->get("star_one")=='true'){
            array_push($review_score,1);
        }
        if($request->get("star_two")=='true'){
            array_push($review_score,2);
        }
        if($request->get("star_three")=='true'){
            array_push($review_score,3);
        }
        if($request->get("star_four")=='true'){
            array_push($review_score,4);
        }
        if($request->get("star_five")=='true'){
            array_push($review_score,5);
        }

        if(sizeof($review_score)>0){
            $asinComment = $asinComment->whereIn('review_score',$review_score);
        }

        if($request->get("is_verified")=='true'){
            $asinComment = $asinComment->where('is_verified',true);
        }
        $asinComment = $asinComment->get();
        return $datatables->collection($asinComment)
            ->editColumn('title', function ($asinComment) {
                return '<a href="https://www.amazon.com/'.$asinComment->link_review_page.'" target="_blank">' . $asinComment->title . '</a>';
            })
            ->editColumn('author', function ($asinComment) {
                return '<a href="https://www.amazon.com/'.$asinComment->link_author.'" target="_blank">' . $asinComment->author . '</a>';
            })
            ->editColumn('is_verified',function ($asinComment){
                if($asinComment->is_verified){
                    return "Yes";
                }else{
                    return "No";
                }
            })
            ->addColumn('is_image',function ($asinComment){
                if($asinComment->images()){
                    return "Yes";
                }else{
                    return "No";
                }
            })
            ->editColumn('video_url',function ($asinComment){
                if(!empty($asinComment->video_url)){
                    return "Yes";
                }else{
                    return "No";
                }
            })
            ->addColumn('input_tag',function ($asinComment){
                $tag= '';
                $id= $asinComment->id;
                $asin_id= $asinComment->asin_id;
                if($asinComment->tags()!=null){
                    $tag = $asinComment->tags->map(function($tag) {
                        return $tag->name;
                    })->implode(',');
                }
                return view('tags',compact('tag','id','asin_id'));
            })
            ->rawColumns(['title','author','input_tag'])
            ->make(true);
    }

    public function scrapeData(){
        $client = new Client();
        $client->setHeader('User-Agent', "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36");

        $crawler = $client->request('GET', 'https://www.amazon.com/product-reviews/B0742HBFWW');
        //get max page number first

        $ulrProduct = $crawler->filter('h1 > .a-link-normal')->each(function ($rating){
            return $rating->attr('href');
        });

        dd($ulrProduct);
    }

    public function getTags(Request $request)
    {
        $q = $request->q;
        $tag = Tag::where('name','LIKE','%'.$q.'%')->take(10)->get();
        return response()->json($tag);
    }

    public function addTag(Request $request,$id)
    {
        //delete oll tag with same name
        if(strlen($request->name)>0){
            $name = $request->name;
            Tag::updateOrCreate([
               'name'=>$name
            ]);

            $comment = AsinComment::find($id);

            $comment->tags()->create(['name'=>$name]);

            return response()->json($comment->tags());
        }

        return $request->name;
    }

    public function removeTag(Request $request,$id)
    {
        $name = $request->name;
        $comment = AsinComment::find($id);
        $comment->tags()->where('name',$name)->delete();
        return response()->json($comment);
    }

    public function viewAnalysis($id)
    {
        $asin = Asin::with("comments")->find($id);

        if(!empty($asin)){
            $data =  DB::table("asin_comments")
                ->select(DB::raw('count(asin_comment_tags.name) as total'),'asin_comment_tags.name as name')
                ->join("asin_comment_tags","asin_comments.id","=","asin_comment_tags.asin_comment_id")
                ->where("asin_comments.asin_id","=",$id)
                ->groupBy("asin_comment_tags.name")->get();
            return view('analysis',compact('data','id','asin'));
        }else{
            return redirect()->route('home')->with('error', 'Code not valid');
        }
    }
}
