<?php

 namespace App\Service;

use App\Entity\Agriculteur;
use App\Entity\Client;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

interface SecurityService
{
    public function agr_form1(Request $request);
    public function persist_agr($data);
    public function seve_agr(User $user, Agriculteur $agriculteur);
    public function persist_cli($client);
    public function save_cli(User $user, Client $client);
    public function send_validation_email(User $user, Agriculteur $agriculteur);
    public function send_forget_password_email($utilisateur, $type, $email);
    public function account_user(User $user);
    public function reset_password($email,$password);
}
