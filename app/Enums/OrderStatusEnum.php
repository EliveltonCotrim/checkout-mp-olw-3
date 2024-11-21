<?php

namespace App\Enums;

enum OrderStatusEnum: int
{
    case CART = 1;
    case PENDING = 2;
    case PAID = 3;
    case CANCELED = 4;
    case REJECTED = 5;

    public function getName(): string
    {
        return match ($this) {
            self::CART => "Carrinho",
            self::PENDING => "Pendente",
            self::PAID => "Pago",
            self::CANCELED => "Cancelado",
            self::REJECTED => "Rejeitado",
            default => "Status não encontrado",
        };
    }

    public function getStyles(): string
    {
        return match ($this) {
            self::CART => "px-2 py-0.5 text-xs rounded-md bg-gray-100 text-gray-800",
            self::PENDING => "px-2 py-0.5 text-xs rounded-md bg-yellow-100 text-yellow-800",
            self::PAID => "px-2 py-1 text-xs rounded-md bg-green-100 text-green-800",
            self::CANCELED => "px-2 py-0.5 text-xs rounded-md bg-red-100 text-red-800",
            self::REJECTED => "px-2 py-0.5 text-xs rounded-md bg-purple-100 text-purple-800",
            default => "Style não encontrado",
        };
    }

    public static function parse($status)
    {
        switch ($status) {
            case 'pending':
                return self::PENDING;
            case 'approved':
                return self::PAID;
            case 'rejected':
                return self::REJECTED;
            case 'cancelled':
                return self::CANCELED;
            default:
                return self::CANCELED;

        }
    }
}
