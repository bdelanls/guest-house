<?php

namespace App\Controller;

use App\Model\UserManager;

/**
 * Class UserController
 *
 */
class UserController extends AbstractController
{

    /**
     * Display user LOGIN
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userManager = new UserManager();

            $user = [
                'login' => $_POST['login'],
                'password' => $_POST['password'],
            ];


            $log = $userManager->selectLogin($user);

            $alert = false;

            if ($log){
                if( password_verify($user['password'], $log['password']) ) {
                    $_SESSION["user"] = [
                        "firstname" => $log["firstname"],
                        "lastname" => $log['lastname'],
                        "login" => $log['login'],
                        "email" => $log["email"],
                        "role" => $log["role_id"],
                    ];
                }else{
                    $alert = true;
                }
            }else{
                $alert = true;
            }

            if($alert){
                echo '<div class="alert alert-danger" role="alert">
                  Le login et/ou le mot de passe n\'est pas bon.
                </div>';
            }else{
                return $this->twig->render('User/show.html.twig', ['user' => $log]);
            }
        }
       return $this->twig->render('User/login.html.twig');
        
    }


    /**
     * Display user listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $userManager = new UserManager();
        // $users = $userManager->selectAll();
        $users = $userManager->allWithRole();

        return $this->twig->render('User/index.html.twig', ['users' => $users]);
    }


    /**
     * Display user informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(int $id)
    {
        $userManager = new UserManager();
        $user = $userManager->selectOneById($id);

        return $this->twig->render('User/show.html.twig', ['user' => $user]);
    }


    /**
     * Display user edition page specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(int $id): string
    {
        $userManager = new UserManager();
        $user = $userManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userUpdate['id'] = $_POST['id'];
            $userUpdate['firstname'] = $_POST['firstname'];
            $userUpdate['lastname'] = $_POST['lastname'];
            $userUpdate['login'] = $_POST['login'];
            if ($_POST['password'] != ""){
                $passwordHash = password_hash($_POST['password'], PASSWORD_ARGON2ID);
                $userUpdate['password'] = $passwordHash;
            }
            $userUpdate['email'] = $_POST['email'];
            $userUpdate['phone'] = $_POST['phone'];

            // test login and email
            $loginExist = $userManager->selectLogin($userUpdate);
            $emailExist = $userManager->selectLogin($userUpdate);
            if ($loginExist && $user['login'] != $userUpdate['login']){
                echo '<div class="alert alert-danger" role="alert">
                  Le login existe déjà !
                </div>';
            }else if ($emailExist && $user['email'] != $userUpdate['email']){
                echo '<div class="alert alert-danger" role="alert">
                  L\'email existe déjà !
                </div>';
            }else {
                $userManager->update($userUpdate);
                header('Location:/user/show/' . $id);
            }
            
        }

        return $this->twig->render('User/edit.html.twig', ['user' => $user]);
    }


    /**
     * Display user creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userManager = new UserManager();

            if(!isset($_POST['role_id'])) {
                $role_id = 2;
            }else{
                $role_id = $_POST['role_id'];
            }

            $passwordHash = password_hash($_POST['password'], PASSWORD_ARGON2ID);


            $user = [
                'firstname' => $_POST['firstname'],
                'lastname' => $_POST['lastname'],
                'login' => $_POST['login'],
                'password' => $passwordHash,
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'role_id' => $role_id,
            ];

            // test login and email
            $loginExist = $userManager->selectLogin($user);
            $emailExist = $userManager->selectLogin($user);
            if ($loginExist){
                echo '<div class="alert alert-danger" role="alert">
                  Le login existe déjà !
                </div>';
            }else if ($emailExist){
                echo '<div class="alert alert-danger" role="alert">
                  L\'email existe déjà !
                </div>';
            }else {
                $id = $userManager->insert($user);
                header('Location:/user/show/' . $id);
            }

        }

        return $this->twig->render('User/add.html.twig');
    }


    /**
     * Handle user deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $userManager = new UserManager();
        $userManager->delete($id);
        header('Location:/user/index');
    }
}
