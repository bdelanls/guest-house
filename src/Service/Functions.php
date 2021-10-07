<?php

namespace App\Service;

class Functions{

    public function test($i){
        return $i * 10;


    }


    public function testFile($file){
        $errorMessage = "";
        $message = "";
        $finalName = "";

        $fileOnServer = $file['tmp_name'];

        $autorizemime = ["image/jpeg", "image/jpg", "image/gif", "image.png"];
        // test MIME
        $testMime = mime_content_type($fileOnServer);
        if (!in_array($testMime, $autorizemime)) {
            $errorMessage = "Le fichier n'est pas reconnu comme une image";
        }
        // test uploaded file
        if (!is_uploaded_file(($fileOnServer))){
            $errorMessage = "Il y a eu une erreur d'upload du fichier";
        }

        // test size
        $fileSize = filesize($fileOnServer);
        if ($fileSize > 1000000) {
            $errorMessage = "Le fichier est trop volumineux";
        }

        if (!$errorMessage) {
            $originalFileName = basename($file['name']);
            $ext = pathinfo($originalFileName, PATHINFO_EXTENSION);
            $mainName = pathinfo($originalFileName, PATHINFO_FILENAME);
            $tmpcleanedName = preg_replace("/\s/", "-", $mainName);
            $tmpcleanedName = trim($tmpcleanedName, "-");
            $finalName = $tmpcleanedName . "-" .uniqid() . "." . $ext;
            $destination = "assets/images/" . $finalName;
            $successUpload = move_uploaded_file($fileOnServer, $destination);
            if ($successUpload){
                $message = "Le fichier a bien été envoyé.";
            }else{
                $errorMessage = "Il y a eu un problème lors de l'upload";
            }
        }
        $result = ['message' => $message, 'errorMessage' => $errorMessage, 'finalName' => $finalName];
        return $result;
    }


}