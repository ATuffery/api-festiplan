<?php

namespace services;

class AuthService {
    public function connexion(\PDO $pdo, string $login, string $password){
        $query = "SELECT * FROM user WHERE login = :login AND mdp = :password";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":login", $login);
        $stmt->bindParam(":password", $password);

        $stmt->execute();

        $user = $stmt->fetch();

        return $user->rowCount() == 1 ? $user : null;

    }
}