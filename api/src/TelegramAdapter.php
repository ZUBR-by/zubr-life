<?php

namespace App;

use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramResponseException;

class TelegramAdapter
{
    private Api $api;
    private array $allowedGroups;

    public function __construct(string $botToken, array $allowedGroups)
    {
        $this->api           = new Api($botToken);
        $this->allowedGroups = $allowedGroups;
    }

    public function isUserInAllowedGroups(int $userId): bool
    {
        foreach ($this->allowedGroups as $group) {
            try {
                $this->api->getChatMember(
                    ['chat_id' => $group, 'user_id' => $userId]
                );
                return true;
            } catch (TelegramResponseException $e) {
                continue;
            }
        }

        return false;
    }
}
