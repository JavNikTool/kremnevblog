<?php

namespace core\Registration;

class RegValidator
{
    private ?string $log = null;
    private ?string $pass = null;
    private ?string $pass_conf = null;
    private ?\PDO $conn = null;


    public function __construct($log, $pass, $pass_conf, $conn)
    {
        $this->log = $log;
        $this->pass = $pass;
        $this->pass_conf = $pass_conf;
        $this->conn = $conn;
    }


    // проверяем совпадает ли пароль с паролем-подтверждением
    public function checkPassConfirm(): void
    {
        if ($this->pass !== $this->pass_conf) {
            header('Location: /?reload=true&reg_err=true');
            die();
        }
    }

    // проверка на кириллицу
    public function checkCyrillic(): void
    {
        if (preg_match("/[а-яА-Я]/", $this->log) || preg_match("/[а-яА-Я]/", $this->pass)) {
            header('Location: /?kirillica=true&reg_err=true');
            die();
        }
    }

    // проверка кол-ва символов логина
    public function checkLogSymbolLen($logMin, $logMax): void
    {
        if (strlen($this->log) < $logMin || strlen($this->log) > $logMax) {
            header('Location: /?count=true&reg_err=true');
            die();
        }
    }

    // проверка кол-ва символов пароля
    public function checkPassSymbolLen($passMin, $passMax): void
    {
        if (strlen($this->pass) < $passMin || strlen($this->pass) > $passMax) {
            header('Location: /?count=true&reg_err=true');
            die();
        }
    }

    // проверка уникальности логина
    public function checkLoginUnuq(): void
    {
        $sth = $this->conn->prepare('SELECT id FROM users where login = :login');
        $sth->execute(['login' => $this->log]);

        if (count($sth->fetchAll()) > 0) {
            $sth = null;
            header('Location: /?uniq=false&reg_err=true');
            die();
        }
    }


    // проверка уникальности пароля
    public function checkPassUnuq(): void
    {
        $sth = $this->conn->query('SELECT password FROM users');

        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)) {
            if (password_verify($this->pass, $res['password'])) {
                $sth = null;
                header('Location: /?uniqp=false&reg_err=true');
                die();
            }
        }
    }
}