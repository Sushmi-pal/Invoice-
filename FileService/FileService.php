<?php

class FileService
{

    /**
     *
     * Creating and updating image
     *
     * @param $company_name
     * @param $file_name
     * @param $file_image
     * @return string
     */
    public function upload($company_name, $file_name, $file_image)
    {
        $content = file_get_contents($file_image);
        $this->file_name = $file_name;
        $this->file_image = $file_image;
        $file_ext = explode(".", $this->file_name);
        $file_actual_ext = strtolower(end($file_ext));
        if ($file_actual_ext === 'png' or $file_actual_ext === 'jpg' or $file_actual_ext === 'jpeg') {
            $file_name_new = uniqid(' ', true) . "$company_name" . "." . $file_actual_ext;
        } else {
            echo "Allowed file extension (jpg, jpeg, png)";
            $file_name_new = "false";
        }
        if ($file_name_new !== "false") {
            define('DIRECTORY', 'C:/xampp/htdocs/api/uploads/');
            file_put_contents(DIRECTORY . $file_name_new, $content);
        }
        return $file_name_new;
    }

    public function deleteimage($data)
    {
        foreach ($data as $k => $v) {
            $file_name_old = $v['file_name'];
        }
        if ($file_name_old) {
            try {
                unlink("uploads/" . $file_name_old);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }

    }


}