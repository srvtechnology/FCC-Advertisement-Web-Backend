<?php

namespace App\Enums;

enum UserType: string
{
    case Agent = 'agent';
    case System = 'system';
}