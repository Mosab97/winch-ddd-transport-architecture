<?php

namespace Src\Domain\Drivers\Enums;

enum DriverStatusEnum: string
{
    case Available = 'available';
    case Busy = 'busy';
    case Offline = 'offline';
}
