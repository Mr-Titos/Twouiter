<?php

namespace App\Enum;

enum RoleEnum: string
{
    case PUBLIC = 'P';
    case USER = 'U';
    case ADMIN = 'A';
}