<?php
session_start();

require "autoloader.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(isset($_GET["func"]))
{
    if(function_exists($_GET["func"]))
    {
        if(isset($_POST['token']) && !empty($_POST['token']))
        {
            $form = unserialize($_SESSION["forms"][$_POST['token']]);
            if ($form->checkForm()) {
                $_GET["func"]($form);
            }
            else
            {
                header("HTTP/1.1 400 Bad Form");
                echo $form->getFormCorrected();
            }
        }
        else
        {
            header("HTTP/1.1 401 Unauthorized form not found");
        }
    }
    else
    {
        header("HTTP/1.1 401 Unauthorized function not found");
    }
}
else
{
    header("HTTP/1.1 401 Unauthorized no function provided");
}

function addPicture()
{
    if (isset($_FILES['img']))
    {
        $errors = array();
        $fileTmpPath = $_FILES['img']['tmp_name'];
        $fileName = $_FILES['img']['name'];
        $fileSize = $_FILES['img']['size'];
        $fileType = $_FILES['img']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $uploadFileDir = '../../res/img/pages/';
        $dest_path = $uploadFileDir . $newFileName;
        if (empty($errors) == true)
        {
            if(move_uploaded_file($fileTmpPath, $dest_path))
            {
                echo "res/img/pages/".$newFileName;
            }
            else
            {
                echo'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
            }
        }
    }
}

function createMap()
{
    if(isset($_POST["address"]))
    {
        $address = str_replace(" ", "+", preg_replace('/(\s\s+|\t|\n)/', ' ', $_POST["address"]));
        echo "<iframe frameborder='0' scrolling='no' marginheight='0' marginwidth='0'src='https://maps.google.com/maps?&amp;q=$address&amp;output=embed'></iframe>";
    }
}