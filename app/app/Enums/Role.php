<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Agent = 'agent';
    case User = 'user';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Agent => 'Support Agent',
            self::User => 'User',
        };
    }

    public function isAdmin(): bool
    {
        return $this === self::Admin;
    }

    public function isAgent(): bool
    {
        return $this === self::Agent;
    }

    public function isUser(): bool
    {
        return $this === self::User;
    }
}
