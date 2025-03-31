<?php

namespace Threls\ThrelsInvoicingModule\Enums;

enum TransactionStatusEnum: string
{
    case SCHEDULED = 'scheduled';
    case PENDING = 'pending';
    case ON_HOLD = 'on_hold';
    case PAID = 'paid';
    case CANCELLED = 'cancelled';
    case FAILED = 'failed';
    case SUPERSEDED = 'superseded';


    public function allowedTransitions(): array
    {
        return match ($this) {
            self::SCHEDULED,self::PENDING => [self::ON_HOLD, self::PAID, self::CANCELLED, self::FAILED, self::SUPERSEDED],
            self::FAILED => [self::ON_HOLD, self::CANCELLED],
            self::ON_HOLD => [self::PAID, self::SUPERSEDED, self::CANCELLED, self::FAILED],
            default => [],
        };
    }

    public function canTransitionTo(self $newState): bool
    {
        return in_array($newState, $this->allowedTransitions(), true);
    }
}
