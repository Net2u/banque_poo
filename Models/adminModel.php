<?php

namespace App\Models;

class AdminModel extends UsersModel
{

    protected $setUsers_id;

    public function setforeignkeyUser_id()
    {
        $_SESSION['key'] = [
            'setUsers_id' => $this->setUsers_id
        ];
    }
}
