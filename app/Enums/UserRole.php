<?php

namespace App\Enums;

enum UserRole: string
{
    case CUSTOMER = 'customer';
    case RESTAURANT_OWNER = 'restaurant_owner';
    case RESTAURANT_STAFF = 'restaurant_staff';
    case RIDER = 'rider';
    case SUPER_ADMIN = 'super_admin';
}
