<style>
    a{
        color: #000;
    }
    button{
        float: right;
        padding: 10px 15px;
        margin-right: 30%;
    }
</style>
<button><a href="my_file.html">Back</a></button>

<?php

$name = $_FILES["uploads"]["name"];
$tmpName = $_FILES["uploads"]["tmp_name"];
$type = $_FILES["uploads"]["type"];
$size = $_FILES["uploads"]["size"];
$errorMsg = $_FILES["uploads"]["error"];
$explode = explode(".",$name);
$extension = end($explode);

//starting PHP image upload error handlings
if(!$tmpName)
{
    echo "ERROR: Please choose file";
    exit();
}
else if($size > 5242880)// if file size is larger than 5MB 
{
    echo "ERROR: Please choose less than 5MB file for uploading";
    unlink($tmpName);
    exit();
}
else if(!preg_match("/\.(jpg|png|jpeg)$/i",$name)) 
{
    echo "ERROR: Please choose the file only with the JPEG, PNG or JPG file format";
    unlink($tmpName);
    exit();
}
else if($errorMsg == 1)
{
    echo "ERROR: An unexpected error occured while processing the file. Please try again.";
    exit();
}
// End of PHP image upload error handlings


$uploaddir = __DIR__.'/uploads/';
$uploadfile = $uploaddir . basename($_FILES['uploads']['name']);

if (!file_exists($uploaddir)) {
    mkdir($uploaddir, 0777, true);
}

$moveFile = move_uploaded_file($tmpName,$uploadfile);

if($moveFile != true)
{
    echo "ERROR: File not uploaded. Please try again";
    unlink($tmpName);
    exit();
}


// ------Resizing image ----------- 
include_once("upld_fn.php");
$target = "uploads/$name";
$resize = "uploads/resized_$name".time();

$max_height = 360; 
$max_width  = 640;

upld_fn($target, $resize, $max_width, $max_height, $extension);
//-----------End of resizing image-----------

echo "<h2>Original image:-</h2> ";
echo "<img src='uploads/$name' /> <br/>";
echo "<h2>Resized image:-</h2> ";
$img_path = "uploads/resized_$name".time();
echo "<img src='$img_path' />"

?>