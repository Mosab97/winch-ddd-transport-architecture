<?php

namespace Src\Domain\Orders\Enums;

enum OrderStatusEnum: string
{
    case Pending = 'pending';
    case Assigned = 'assigned';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}
