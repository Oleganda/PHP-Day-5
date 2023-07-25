<?php

function uploadFile($picture, $source = "user")   //$_FILES[$picture"]  //add $source - we know that it comes from the user

{
    if ($picture["error"] == 4) {
        $pictureName = "profile.png";

        if ($source == "product") {
            $pictureName = "dummy.jpg";
        }
        $message = "No photo, but you can add it later";
    } else {
        $checkType = getimagesize($picture["tmp_name"]);
        $message = $checkType ? "Ok" : "Chose another type of you image";
    }

    if ($message == "Ok") {
        $ext = strtolower(pathinfo($picture["name"], PATHINFO_EXTENSION));
        $pictureName = uniqid("") . "." . $ext;

        $destination = "photos/{$pictureName}";

        if ($source == "product") {
            $destination = "../photos/{$pictureName}";
        }

        move_uploaded_file($picture["tmp_name"], $destination);
    }

    return [$pictureName, $message];
}
