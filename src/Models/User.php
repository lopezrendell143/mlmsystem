<?php
namespace Src\Models;

class User {
    public $id;
    public $username;
    public $email;
    public $sponsorId;
    public $rank;

    public function __construct($id = null, $username = '', $email = '', $sponsorId = null, $rank = 'Bronze') {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->sponsorId = $sponsorId;
        $this->rank = $rank;
    }
}