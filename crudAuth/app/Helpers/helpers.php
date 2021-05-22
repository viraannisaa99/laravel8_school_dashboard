<?php

function imageUpload($name, $nim){
    if ($photo = $request->file($name)) {
        $fileName       = Request()->$nim . '.' . $photo->extension();
                          $photo->move(public_path('student_photo'), $fileName);
        $input[$name] = $fileName;
    }
}
?>