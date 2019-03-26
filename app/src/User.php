<?php

namespace App;

class User extends \Wex\Base\User
{
    protected function describe(\Wex\ActiveRecord\Blueprint &$blueprint): void
    {
        parent::describe($blueprint);

        $blueprint->string('name')->required();
    }
}