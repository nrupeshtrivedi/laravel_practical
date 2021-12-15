<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  use Notifiable, HasApiTokens;

  protected $fillable = [
    'firstname', 'lastname','email','password','image','mobile_number','status'
  ];
}
