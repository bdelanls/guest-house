<?php

namespace App\Controller;

use App\Model\GuestroomManager;
use App\Model\MediaManager;

class HomeController extends AbstractController
{

    /**
     * Display home page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    // public function index()
    // {
    //     return $this->twig->render('Home/index.html.twig');
    // }



    public function index()
    {
        $guestroomManager = new GuestroomManager();
        $guestrooms = $guestroomManager->selectAllHome();

        $mediaManager = new MediaManager();
        $medias = $mediaManager->selectAllHome();

        $result=[];

        foreach ($guestrooms as $guestroom){

            foreach ($medias as $media){

                if ($media['guestroom_id'] == $guestroom['id']){
                    $guestroom['file'] = $media['file'];
                    $guestroom['file_title'] = $media['title'];
                }
            }
            array_push($result, $guestroom);
        }

        //var_dump($result);

        return $this->twig->render('Home/index.html.twig', ['guestrooms' => $result]);
    }
}


