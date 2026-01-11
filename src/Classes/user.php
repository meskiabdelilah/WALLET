<?php

namespace Src\Classes;

use Exception;

class User
{
    private $db;
    private $id;
    private $nom;
    private $email;
    private $createdAt;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setEmail($email)
    {
        if (Security::validateEmail($email)) {
            $this->email = $email;
            return true;
        }
        return false;
    }

    private function hydrate($data)
    {
        $this->id = $data = ['id'] ?? null;
        $this->nom = $data = ['nom'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->createdAt = $data['created_at'] ?? '';
    }

    public function create($nom, $email, $password)
    {
        if (empty($nom) || empty($email) || empty($password)) {
            return false;
        }

        if (!Security::validateEmail($email)) {
            return false;
        }

        if (!Security::validatePassword($password)) {
            return false;
        }

        if ($this->emailExists($email)) {
            return false;
        }

        $passwordHash = Security::hashPassword($password);

        $sql = " INSERT INTO users (nom, email, password_hash) VALUES(?, ?, ?)";
        try {
            $this->db->query($sql, [$nom, $email, $passwordHash]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }


    // for checks if an email exists
    public function emailExists($email)
    {
        $sql = "SELECT id FROM users WHERE email = ? AND deleted_at IS NULL";
        $result = $this->db->query($sql, [$email]);
        $user = $result->fetch();
    }

    public function login($email, $password)
    {
        $sql = "SELECT * FROM users WHERE email = ? AND deleted_at IS NULL";
        $result = $this->db->query($sql, [$email]);
        $user = $result->fetch();

        if ($user && Security::verifyPassword($password, $user['password_hash'])) {
            $this->hydrate($user);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_email'] = $user['email'];

            session_regenerate_id(true);

            return $user;
        }

        return false;
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        session_start();
        session_regenerate_id(true);
    }

    public function getById($id)
    {
        $sql = "SELECT id, nom, email, role, created_at FROM users WHERE id = ? AND deleted_at IS NULL";
        $result = $this->db->query($sql, [$id]);
        $data = $result->fetch();

        if ($data) {
            $this->hydrate($data);
        }

        return $data;
    }

    public function update($id, $nom, $email)
    {
        $sql = "UPDATE users SET nom = ?, email = ? WHERE id = ?";
        try {
            $this->db->query($sql);
            return true;
        } catch (Exception $e) 
        {
            return false;
        }
    }

    public function softDelete($id)
    {
        $sql = " UPDATE user SET delete_at = NEW() WHERE id = ?";
        try
        {
            $this->db->query($sql, [$id]);
            return true;
        } catch (Exception $e)
        {
            return false;
        }
    }
}
