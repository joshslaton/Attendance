<?php

class User
{
    public function get_user($username)
    {
        $user_data = DB::query(array("SELECT * FROM proj_users WHERE email = \"$username\""));
        if (count($user_data) > 0) {
            return $user_data[0];
        }
        return array();
    }
}
