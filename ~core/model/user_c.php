<?php
class Users {

        public $directory_user = '';

        public $user_arrey = [];

        public $user = [];


	  //конструктор КЛАССА
    public function __construct($directory_user, $login = ''){

        $this->directory_user = $directory_user;
        $files = array_reverse(scandir($this->directory_user, 1));

        if ($login == '') {
            foreach ($files as $key => $name) {
                $file = $this->directory_user.$name;
                //проверяем существование файла
                if (!is_file($file)) continue;
                $this->user_arrey[explode(".", $name)[0]] = Functions::readFile($file);
            }
        }else{
            $file = $this->directory_user.$login;
            $this->user = Functions::readFile($file);
        }


    }
    //деструктор КЛАССА
    public function __destruct(){
    }


    //***********************************ДОП методы****************************************************
    public function add_user($array){
        Functions::saveFile($array, $this->directory_user.$array['login'].'.txt');
    }


    public function delete_user($array){
        unlink($this->directory_user . $array['login'] . '.txt');
    }
}