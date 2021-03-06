<?php

namespace App\Controller;

use App\Model\GuestroomManager;

use App\Model\BookingManager;
use App\Model\UserManager;
use App\Model\RestorationManager;
use App\Model\MediaManager;

/**
 * Class GuestroomController
 *
 */
class GuestroomController extends AbstractController
{


    /**
     * Display guestroom listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $guestroomManager = new GuestroomManager();
        $guestrooms = $guestroomManager->selectAll();

        return $this->twig->render('Guestroom/index.html.twig', ['guestrooms' => $guestrooms]);
    }


    /**
     * Display guestroom informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(int $id)
    {
        $guestroomManager = new GuestroomManager();
        $guestroom = $guestroomManager->selectOneById($id);

        $mediaManager = new MediaManager();
        $media = $mediaManager->selectOneByGuestroom($id);
        $photos = $mediaManager->selectAllPhoto($id);

        $guestroom['disabled'] = $guestroom['disabled'] == true ? "Oui" : "Non";
        $guestroom['wifi'] = $guestroom['wifi'] == true ? "Oui" : "Non";
        $guestroom['tv'] = $guestroom['tv'] == true ? "Oui" : "Non";
        $guestroom['clim'] = $guestroom['clim'] == true ? "Oui" : "Non";
        $guestroom['pets'] = $guestroom['pets'] == true ? "Oui" : "Non";

        return $this->twig->render('Guestroom/show.html.twig', ['guestroom' => $guestroom, 'photos' => $photos]);
    }


    /**
     * Display guestroom edition page specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(int $id): string
    {
        $guestroomManager = new GuestroomManager();
        $guestroom = $guestroomManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            !isset($_POST['wifi']) ? $wifi = false : $wifi = true;
            !isset($_POST['tv']) ? $tv = false : $tv = true;
            !isset($_POST['clim']) ? $clim = false : $clim = true;
            !isset($_POST['disabled']) ? $disabled = false : $disabled = true;
            !isset($_POST['pets']) ? $pets = false : $pets = true;

            $guestroom['id'] = $_POST['id'];
            $guestroom['title'] = $_POST['title'];
            $guestroom['description'] = $_POST['description'];
            $guestroom['max_persons'] = $_POST['max_persons'];
            $guestroom['num_bed'] = $_POST['num_bed'];
            $guestroom['add_bed'] = $_POST['add_bed'];
            $guestroom['wifi'] = $wifi;
            $guestroom['tv'] = $tv;
            $guestroom['clim'] = $clim;
            $guestroom['area'] = $_POST['area'];
            $guestroom['price'] = $_POST['price'];
            $guestroom['promotion'] = $_POST['promotion'];
            $guestroom['disabled'] = $disabled;
            $guestroom['pets'] = $pets;
            
            $guestroomManager->update($guestroom);
            header('Location:/guestroom/show/' . $id);
        }

        return $this->twig->render('Guestroom/edit.html.twig', ['guestroom' => $guestroom]);
    }


    /**
     * Display guestroom creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $guestroomManager = new GuestroomManager();

            !isset($_POST['wifi']) ? $wifi = false : $wifi = true;
            !isset($_POST['tv']) ? $tv = false : $tv = true;
            !isset($_POST['clim']) ? $clim = false : $clim = true;
            !isset($_POST['disabled']) ? $disabled = false : $disabled = true;
            !isset($_POST['pets']) ? $pets = false : $pets = true;
            

            $guestroom = [
                'description' => $_POST['description'],
                'title' => $_POST['title'],
                'max_persons' => $_POST['max_persons'],
                'num_bed' => $_POST['num_bed'],
                'add_bed' => $_POST['add_bed'],
                'wifi' => $wifi,
                'tv' => $tv,
                'clim' => $clim,
                'area' => $_POST['area'],
                'price' => $_POST['price'],
                'promotion' => $_POST['promotion'],
                'disabled' => $disabled,
                'pets' => $pets,
            ];
            $id = $guestroomManager->insert($guestroom);
            header('Location:/guestroom/show/' . $id);
        }

        return $this->twig->render('Guestroom/add.html.twig');
    }


    /**
     * Handle guestroom deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $guestroomManager = new GuestroomManager();
        $guestroomManager->delete($id);
        header('Location:/guestroom/index');
    }

    
    
    public function booking(int $id)
    {
        $guestroomManager = new GuestroomManager();
        $guestroom = $guestroomManager->selectOneById($id);

        $mediaManager = new MediaManager();
        $media = $mediaManager->selectOneByGuestroom($id);
        $photos = $mediaManager->selectAllPhoto($id);

        $restorationManager = new RestorationManager();
        $restorations = $restorationManager->selectAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $arrival = $_POST['arrival'];
            $departure = $_POST['departure'];

            $guestroomId = $_POST['guestroom_id'];

            $bookingManager = new BookingManager();
            $bookingDates = $bookingManager->selectAllCross($guestroomId);

            $result = 0;

            $today = date("Y-m-d");

            foreach($bookingDates as $bookingDate){

                if (($arrival < $bookingDate['arrival']) && ($departure > $bookingDate['arrival']) && ($departure < $bookingDate['departure'])){
                    $result = 1;
                } else if (($arrival < $bookingDate['arrival']) && ($departure > $bookingDate['departure'])){
                    $resut = 1;
                } else if (($arrival > $bookingDate['arrival']) && ($arrival < $bookingDate['departure']) && ($departure > $bookingDate['departure'])){
                    $result = 1;
                } else if (($arrival >= $bookingDate['arrival']) && ($arrival < $bookingDate['departure']) && ($departure <= $bookingDate['departure'])){
                    $result = 1;
                } else if ($arrival <= $today || $departure <= $today || $departure <= $arrival){
                    $result = 2;
                }
            }

            /* Enregistrement de la r??servation */
            if (!$result){
                !isset($_POST['taxi']) ? $taxi = false : $taxi = true;

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
            }


            

            return $this->twig->render('Guestroom/booking.html.twig', [
                'guestroom' => $guestroom, 
                'media' => $media, 
                'photos' => $photos, 
                'restorations' => $restorations,
                'messageReservation' => $result,
            ]);

            

        }


        return $this->twig->render('Guestroom/booking.html.twig', [
                'guestroom' => $guestroom, 
                'media' => $media, 
                'photos' => $photos, 
                'restorations' => $restorations
            ]);
    }

}
