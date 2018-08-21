<?php

namespace App\Jobs;

use App\Http\Models\Asin;
use App\Http\Models\AsinComment;
use App\Http\Models\AsinCommentImage;
use Goutte\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Goutte;
use Illuminate\Support\Facades\Log;

class ProcessScrapeData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $asin;
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;


    /**
     * Create a new job instance.
     *
     * @param Asin $asin
     */
    public function __construct(Asin $asin)
    {
        $this->asin = $asin;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $client = new Client();
        $client->setHeader('User-Agent', "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36");


        $crawler = $client->request('GET', 'https://www.amazon.com/product-reviews/'.$this->asin->code);

        //get max page number first
        $arrayMaxPageNumber = $crawler->filter('#cm_cr-pagination_bar .a-pagination .page-button a')->each(function ($node) {
            return $node->text(); //need to replace , and convert to int
        });

        //$asin = Asin::where('id',$this->asin->id)->first();
        $maxLoop=1000; //set default total page if max page can't take from page
        if(!empty($arrayMaxPageNumber)){
            $max = $arrayMaxPageNumber[sizeof($arrayMaxPageNumber)-1];
            $maxLoop=(int)str_replace(",","",$max);
        }else{
            Log::debug('Paging no valid.');
        }

        Log::debug('total page.'.$maxLoop);
        sleep(3);
        if(!empty($this->asin)){
            gc_enable();
            for ($i=1;$i<=$maxLoop;$i++){
                try{
                    Log::debug('page . '.$i);
                    $base_url = 'https://www.amazon.com/product-reviews/'.$this->asin->code.'/ref=cm_cr_arp_d_paging_btm_'.$i.'?pageNumber='.$i;
                    Log::debug('get data from. '.$base_url);
                    $url = $client->request('GET', $base_url);
                    //check page still exist or not
                    $stillExist = $url->filter('#cm_cr-review_list .no-reviews-section')->each(function ($node) {
                        return true;
                    });
                    if(!empty($stillExist)){
                        Log::debug('page not found. '.$i); //checking for not available comment
                        break;
                    }

                    $url->filter('#cm_cr-review_list .review')->each(function ($node)  {
                        $userIdComment =$node->attr('id');
                        Log::debug('user id comment '.$userIdComment);

                        $node->filter('#customer_review-'.$userIdComment)->each(function ($com) use($userIdComment){

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
                                'link_review_page'  => (!empty($dataTitle[0][1])) ? $dataTitle[0][1] : null,
                                'body'              => $reviewData[0][0],
                                'video_url'         => $videoUrl,
                                'review_score'      => (int)substr($rating[0],0,1),
                                'date_of_review'    => trim(str_replace("on ","",$reviewDate[0])),
                                'author'            => $dataAuthor[0][0],
                                'link_author'       => (!empty($dataAuthor[0][1])) ? $dataAuthor[0][1]:null,
                                'is_verified'       => $isVerified,
                                'asin_child'        => $asinChild
                            ]);

                            if($this->asin instanceof Asin){
                                $asinComment = $this->asin->comments()->save($asinCommentData);
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

                    sleep(3);

                    if($i%20==0){
                        gc_collect_cycles();
                    }
                }catch (\Exception $e){
                    Log::error('job scraped error ',['data'=>$e->getMessage()]);
                }
            }

            $this->asin->number_of_comment = $this->asin->comments()->count();
            $this->asin->is_finished = true;
            $this->asin->save();
            gc_disable();
        }
    }
}
