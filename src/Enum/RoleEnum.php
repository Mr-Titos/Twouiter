<?php

namespace App\Enum;

enum RoleEnum: string
{
    case PUBLIC = 'ROLE_P';
    case USER = 'ROLE_U';
    case ADMIN = 'ROLE_A';
}