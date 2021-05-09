<?php


namespace App\Controller;

use App\Entity\Video;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpFoundation\Request;
use YouTube\YouTubeDownloader as Yd;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class YoutubeDownloader extends AbstractController
{
    /**
     * @var Yd
     */
    private $youtubeDownloader;

    public function __construct(Yd $youtubeDownloader)
    {
        $this->youtubeDownloader = $youtubeDownloader;
    }

    public function __invoke(Request $request)
    {
        $video = new Video();
        $video = $request->attributes->get("data");
        try {
            $client = new CurlHttpClient();
            $downloadOptions = $this->youtubeDownloader->getDownloadLinks($video->getUrl());
            dd($downloadOptions);
            $response = $client->request('POST', $this->youtubeDownloader->getDownloadLinks($video->getUrl())->getAllFormats()[0]->url)->getContent();
//            dd($response);
            dd($this->youtubeDownloader->getDownloadLinks($video->getUrl())->getAllFormats()[0]->url);
            if ($downloadOptions->getAllFormats()) {
                echo $downloadOptions->getFirstCombinedFormat()->url;
            } else {
                echo 'No links found';
            }

        } catch (YouTubeException $e) {
            echo 'Something went wrong: ' . $e->getMessage();
        }
    }
}