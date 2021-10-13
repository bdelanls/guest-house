<?php

namespace App\Controller;

use App\Model\BookingManager;
use App\Model\GuestroomManager;
use App\Model\UserManager;
use App\Model\RestorationManager;

/**
 * Class BookingController
 *
 */
class BookingController extends AbstractController
{


    /**
     * Display booking listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $bookingManager = new BookingManager();
        if ($_SESSION['user']['role'] == 2){
            $bookings = $bookingManager->selectAlloneUser($_SESSION["user"]["id"]);
        }else{
            $bookings = $bookingManager->selectAll($_SESSION["user"]["id"]);
        }

        $guestroomManager = new GuestroomManager();
        $guestrooms = $guestroomManager->selectAllGlobal();

        $userManager = new UserManager();
        $users = $userManager->allWithRole();


        if ($_SESSION['user']['role'] == 2){
            return $this->twig->render('Booking/index.html.twig', ['bookings' => $bookings, 'guestrooms' => $guestrooms, 'users' => $users]);
        }else{
            return $this->twig->render('Booking/liste.html.twig', ['bookings' => $bookings, 'guestrooms' => $guestrooms, 'users' => $users]);
 
        }
    }


    /**
     * Display booking informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(int $id)
    {
        $bookingManager = new BookingManager();
        $booking = $bookingManager->selectOneById($id);

        $guestroomManager = new GuestroomManager();
        $guestrooms = $guestroomManager->selectAllGlobal();

        $userManager = new UserManager();
        $users = $userManager->allWithRole();

        $restorationManager = new RestorationManager();
        $restorations = $restorationManager->selectAll();

        // nombre de jour entre 2 dates
        $dateArrival = strtotime($booking['arrival']);
        $dateDeparture = strtotime($booking['departure']);
        $daysBooking = ceil(abs($dateDeparture - $dateArrival) / 86400);

        return $this->twig->render('Booking/show.html.twig', ['booking' => $booking, 'guestrooms' => $guestrooms, 'users' => $users, 'restorations' => $restorations, 'daysBooking' => $daysBooking]);
    }


    /**
     * Display booking edition page specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(int $id): string
    {
        $bookingManager = new BookingManager();
        $booking = $bookingManager->selectOneById($id);

        $guestroomManager = new GuestroomManager();
        $guestrooms = $guestroomManager->selectAllGlobal();

        $userManager = new UserManager();
        $users = $userManager->allWithRole();

        $restorationManager = new RestorationManager();
        $restorations = $restorationManager->selectAll();

        !isset($_POST['taxi']) ? $taxi = false : $taxi = true;
        !isset($_POST['disabled']) ? $disabled = false : $disabled = true;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $booking['id'] = $_POST['id'];
            $booking['disabled'] = $disabled;
            $booking['user_id'] = $_POST['user_id'];
            $booking['guestroom_id'] = $_POST['guestroom_id'];
            $booking['arrival'] = $_POST['arrival'];
            $booking['departure'] = $_POST['departure'];
            $booking['num_of_persons'] = $_POST['num_of_persons'];
            $booking['taxi'] = $taxi;
            $booking['restoration_id'] = $_POST['restoration_id'];
            $bookingManager->update($booking);

            header('Location:/booking');
        }

        return $this->twig->render('Booking/edit.html.twig', ['booking' => $booking, 'guestrooms' => $guestrooms, 'users' => $users, 'restorations' => $restorations]);
    }


    /**
     * Display booking creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {
        $guestroomManager = new GuestroomManager();
        $guestrooms = $guestroomManager->selectAllGlobal();

        $userManager = new UserManager();
        $users = $userManager->allWithRole();

        $restorationManager = new RestorationManager();
        $restorations = $restorationManager->selectAll();

        !isset($_POST['taxi']) ? $taxi = false : $taxi = true;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookingManager = new BookingManager();
            $booking = [
                'user_id' => $_POST['user_id'],
                'guestroom_id' => $_POST['guestroom_id'],
                'arrival' => $_POST['arrival'],
                'departure' => $_POST['departure'],
                'num_of_persons' => $_POST['num_of_persons'],
                'taxi' => $taxi,
                'restoration_id' => $_POST['restoration_id'],
            ];
            $id = $bookingManager->insert($booking);
            header('Location:/booking/show/' . $id);
        }

        return $this->twig->render('Booking/add.html.twig',  ['guestrooms' => $guestrooms, 'users' => $users, 'restorations' => $restorations]);
    }


    /**
     * Handle booking deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $bookingManager = new BookingManager();
        $bookingManager->delete($id);
        header('Location:/booking/index');
    }
}
