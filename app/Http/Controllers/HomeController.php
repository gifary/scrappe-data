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
use App\Http\Models\AsinCommentImage;
use App\Jobs\ProcessScrapeData;
use Carbon\Carbon;
use Goutte\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use Goutte;

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

        $validCode = $crawler->filter('#g')->each(function ($node) {
            Log::debug("Code not valid ".$node->text());
            return true;
        });

        if(empty($validCode)){
            $asin= Asin::create($request->only(['code']));
            //attached job for getting data
            ProcessScrapeData::dispatch($asin)->delay(Carbon::now()->addMinutes(1))->onConnection('database');
            return redirect()->route('home')->with('message', 'Please click this <a href="view/'.$asin->id.'"> link </a> for see the result');
        }else{
            return redirect()->route('home')->with('error', 'ASIN Code not valid');
        }
    }

    public function view($id){
        //find code
        $asin = Asin::find($id);

        if(!empty($asin)){
            return view('detail',compact('asin','id'));
        }else{
            return redirect()->route('home')->with('error', 'Code not valid');
        }
    }

    public function data(Datatables $datatables,$id){
        ini_set('memory_limit', '-1');
        $asinComment = AsinComment::where('asin_id',$id)->get();
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
            ->rawColumns(['title','author'])
            ->make(true);
    }

    public function scrapeData(){
        $client = new Client();
        $client->setHeader('User-Agent', "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36");

        $crawler = $client->request('GET', 'https://www.amazon.com/product-reviews/B0742HBFWW');
        //get max page number first

        $crawler->filter('#cm_cr-pagination_bar .a-pagination .page-button a')->each(function ($node) {
           return $node->text();
        });
//        dump($arrayMaxPageNumber);
        $asin = Asin::where('code','B0742HBFWW')->first();
        //$max = $arrayMaxPageNumber[sizeof($arrayMaxPageNumber)-1];

       /* if(!empty($asin)){
            for ($i=1;$i<=(int)str_replace(",","",$max);$i++){
                try{
                    $url = Goutte::request('GET', 'https://www.amazon.com/product-reviews/'.$this->asin->code.'?pageNumber='.$i);

                    $url->filter('#cm_cr-review_list .review')->each(function ($node) use($asin) {
                        $userIdComment =$node->attr('id');
                        $node->filter('#customer_review-'.$userIdComment)->each(function ($com) use($userIdComment,$asin){

                            $rating =$com->filter('.review-rating')->each(function ($rating){
                                return $rating->text();
                            });


                            $dataTitle = $com->filter('.review-title')->each(function ($rating){
                                return [$rating->text(),$rating->attr('href')];
                            });


                            $dataAuthor = $com->filter('.author')->each(function ($rating){
                                return [$rating->text(),$rating->attr('href')];
                            });


                            $reviewDate = $com->filter('.review-date')->each(function ($rating){
                                return $rating->text();
                            });


                            $childAsin = $com->filter('.review-format-strip a')->each(function ($rating){
                                return [$rating->attr('href'),$rating->text()];
                            });

                            $reviewData = $com->filter('.review-text')->each(function ($text){
                                $isVideo = $text->filter('.video-url')->each(function ($video){
                                    return $video->attr('value');
                                });
                                return [$text->text(),$isVideo];
                            });

                            $videoUrl = null;
                            if(!empty($reviewData[0][1])){
                                $videoUrl = implode($reviewData[1],"");
                            }
                            $isVerified = false;
                            if(!empty($childAsin[1][1])){
                                if(str_contains ($childAsin[1][1],"Verified")){
                                    $isVerified = true;
                                }
                            }

                            $asinChild = null;
                            if(!empty($childAsin[0][0])){
                                $codeChild = explode("/",$childAsin[0][0]);
                                $asinChild  = $codeChild[3];
                            }

                            $asinCommentData = new AsinComment([
                                'title'             => $dataTitle[0][0],
                                'link_review_page'  => $dataTitle[0][1],
                                'body'              => $reviewData[0][0],
                                'video_url'         => $videoUrl,
                                'review_score'      => (int)substr($rating[0],0,1),
                                'date_of_review'    => trim(str_replace("on ","",$reviewDate[0])),
                                'author'            => $dataAuthor[0][0],
                                'link_author'       => $dataAuthor[0][1],
                                'is_verified'       => $isVerified,
                                'asin_child'        => $asinChild
                            ]);

                            if($asin instanceof Asin){
                                $asinComment = $asin->comments()->save($asinCommentData);
                                if($asinComment  instanceof AsinComment){
                                    $imageData = $com->filter('#'.$userIdComment."_imageSection_main > .review-image-tile-section img")->each(function ($rating){
                                        return $rating->attr('src');
                                    });

                                    foreach ($imageData as $image){
                                        $asinImage = new AsinCommentImage(['url'=>$image]);
                                        $asinComment->images()->save($asinImage);
                                    }
                                }
                            }


                        });
                    });
                }catch (\Exception $e){
                    Log::error('job scraped error ',['data'=>$e->getMessage()]);
                }
            }

            $asin->number_of_comment = $asin->comments()->count();
            $asin->is_finished = true;
            $asin->save();

        }*/
    }
}
