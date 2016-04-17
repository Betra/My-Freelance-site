<?php

if($_POST) {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['text']);
    $text = addslashes($_POST['text']);

    $json = array();
    if (!isset($name) or !isset($email) or !isset($text)) { //проверяем заполнение полей
        $json['error'] = 'Вы заполнили не все поля';
        echo json_encode($json);
        die(); //Умираем :(
    }
    if (!preg_match("|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", $email)) { // прoвeряем email нa вaлиднoсть
        $json['error'] = 'Нeправильный email!';
        echo json_encode($json); // вывoдим мaссив oтвeтa
        die(); // Умираем :(
    }
    function mime_header_encode($str, $data_charset, $send_charset)
    {
        if ($data_charset != $send_charset) $str = iconv($data_charset, $send_charset . '//IGNORE', $str);
        return ('=?' . $send_charset . '?B?' . base64_encode($str) . '=?');
    }

    class TEmail {
        public $from_email;
        public $from_name;
        public $to_email;
        public $to_name;
        public $subject;
        public $data_charset = 'UTF-8';
        public $send_charset = 'windows-1251';
        public $body = '';
        public $type = 'text/plain';

        function send() {
            $dc = $this->data_charset;
            $sc = $this->send_charset;
            $enc_to = mime_header_encode($this->to_name,$dc,$sc).' <'.$this->to_email.'>';
            $enc_subject=mime_header_encode($this->subject,$dc,$sc);
            $enc_from = mime_header_encode($this->from_name,$dc,$sc).' <'.$this->from_email.'>';
            $enc_body = $dc == $sc ? $this->body: iconv($dc, $sc. '//IGNORE', $this->body);
            $headers = '';
            $headers .="Mime version: 1.0\r\n";
            $headers.= "Content-type: ".$this->type."; charset =".$sc."\r\n";
            $headers .= "From: ".$enc_from."\r\n";
            return mail($enc_to,$enc_subject,$enc_from,$enc_body,$headers);
        }
    }
    $emailgo = new TEmail;
    $emailgo->from_email = $email;
    $emailgo->from_name = $name;
    $emailgo->to_email = 'selfbetray@gmail.com';
    $emailgo->to_name = 'Ivan Dorofeev';
    $emailgo->subject = 'Работа';
    $emailgo->body = $message;
    $emailgo->send();
    $json['error'] = 0; //Ошибок не было

    echo json_encode($json);
}
else {
    echo 'Вы как сюда попали?';
}
