<?php

namespace App\Models;

abstract class UserType {
    const Normal = 0;
    const Administrator = 1;
    const Moderator = 2;
}