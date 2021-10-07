<?php

namespace App\Controller;

use App\Model\MediaManager;

use App\Service\Functions;

/**
 * Class MediaController
 *
 */
class MediaController extends AbstractController
{


    /**
     * Display media listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $mediaManager = new MediaManager();
        $medias = $mediaManager->selectAll();

        return $this->twig->render('Media/index.html.twig', ['medias' => $medias]);
    }


    /**
     * Display media informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(int $id)
    {
        $mediaManager = new MediaManager();
        $media = $mediaManager->selectOneById($id);

        return $this->twig->render('Media/show.html.twig', ['media' => $media]);
    }


    /**
     * Display media edition page specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(int $id): string
    {
        $mediaManager = new MediaManager();
        $media = $mediaManager->selectOneById($id);
        $guestroom = $mediaManager->selectAllGuestroom();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $result=[];
            
            if($_FILES['file']['tmp_name'] != ""){
                $test = new Functions();
                $result = $test->testFile($_FILES['file']);
            }


            isset($_POST['featured']) ? $featured = true : $featured = false;

            if ($result['errorMessage'] != ""){
                return $this->twig->render('Media/edit.html.twig', ['guestrooms' => $guestroom, 'message' => $result['errorMessage']]);
            }else{

                $media['id'] = $_POST['id'];
                if (isset($result['finalName'])){
                    $media['file'] = $result['finalName'];
                }
                $media['title'] = $_POST['title'];
                $media['featured'] = $featured;
                $media['guestroom_id'] = $_POST['guestroom_select'];
                
                $mediaManager->update($media);
                $id = $media['id'];
                header('Location:/media/show/' . $id);
            }
        }

        return $this->twig->render('Media/edit.html.twig', ['media' => $media, 'guestrooms' => $guestroom]);
    }


    /**
     * Display media creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {
        $mediaManager = new MediaManager();
        $guestroom = $mediaManager->selectAllGuestroom();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mediaManager = new MediaManager();
            
            $result=[];
            $test = new Functions();
            $result = $test->testFile($_FILES['file']);

            isset($_POST['featured']) ? $featured = true : $featured = false;
          
            if ($result['errorMessage'] != ""){
                return $this->twig->render('Media/add.html.twig', ['guestrooms' => $guestroom, 'message' => $result['errorMessage']]);
            }else{

                $media = [
                    'file' => $result['finalName'],
                    'title' => $_POST['title'],
                    'featured' => $featured,
                    'guestroom_id' => $_POST['guestroom_select'],
                ];
    
                $id = $mediaManager->insert($media);
                header('Location:/media/show/' . $id);
            }

        }

        return $this->twig->render('Media/add.html.twig', ['guestrooms' => $guestroom]);
    }


    /**
     * Handle media deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $mediaManager = new MediaManager();
        $mediaManager->delete($id);
        header('Location:/media/index');
    }
}
