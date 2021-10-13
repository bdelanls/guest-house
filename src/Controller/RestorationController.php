<?php

namespace App\Controller;

use App\Model\RestorationManager;

/**
 * Class RestorationController
 *
 */
class RestorationController extends AbstractController
{


    /**
     * Display restoration listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $restorationManager = new RestorationManager();
        $restorations = $restorationManager->selectAll();

        return $this->twig->render('Restoration/index.html.twig', ['restorations' => $restorations]);
    }


    /**
     * Display restoration informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(int $id)
    {
        $restorationManager = new RestorationManager();
        $restoration = $restorationManager->selectOneById($id);

        return $this->twig->render('Restoration/show.html.twig', ['restoration' => $restoration]);
    }


    /**
     * Display restoration edition page specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(int $id): string
    {
        $restorationManager = new RestorationManager();
        $restoration = $restorationManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $restoration['name'] = $_POST['name'];
            $restorationManager->update($restoration);
        }

        return $this->twig->render('Restoration/edit.html.twig', ['restoration' => $restoration]);
    }


    /**
     * Display restoration creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $restorationManager = new RestorationManager();
            $restoration = [
                'name' => $_POST['name'],
            ];
            $id = $restorationManager->insert($restoration);
            header('Location:/restoration/show/' . $id);
        }

        return $this->twig->render('Restoration/add.html.twig');
    }


    /**
     * Handle restoration deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $restorationManager = new RestorationManager();
        $restorationManager->delete($id);
        header('Location:/restoration/index');
    }
}
