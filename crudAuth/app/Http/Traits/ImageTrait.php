<?php

namespace App\Http\Traits;

trait ImageTrait
{
    function saveImage($nim, $photo)
    {
        //save photo in folder
        $file_name = $nim . '.' . $photo->extension();
        $photo->move(public_path('student_photo'), $file_name);
        $photo = $file_name;

        return $file_name;

    }
}
