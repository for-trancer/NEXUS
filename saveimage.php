<?php
$username = $_POST['username'];
if(isset($_POST['saveTestImage']))
{
    $image_parts = explode(";base64,",$_POST['image']);
    $image_type_aux = explode("image/",$image_parts[0]);
    $image_type = $image_type_aux[1];
    $image_base64 = base64_decode($image_parts[1]);

    $test_file = "uploads/faceid/test.png";
    file_put_contents($test_file, $image_base64);
}
else
{
    $folderPath = 'uploads/faceid/';

    $image_parts = explode(";base64,",$_POST['image']);
    $image_type_aux = explode("image/",$image_parts[0]);
    $image_type = $image_type_aux[1];
    $image_base64 = base64_decode($image_parts[1]);

    $temp_file = "uploads/faceid/temp.png";
    file_put_contents($temp_file, $image_base64);
    $command = "C:\\Users\\mrfor\\AppData\\Local\\Programs\\Python\\Python38\\python.exe face-evaluate.py uploads//faceid//temp.png 2>&1";
    $output = exec($command);

    if($output=="true")
    {
        $file = $folderPath . $username . '.png';
        file_put_contents($file,$image_base64);
        echo "true";
    }
    else
    {
        echo $output;
    
    }
    if(file_exists($temp_file)) {
        unlink($temp_file);
    }
}







