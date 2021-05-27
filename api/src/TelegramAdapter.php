<?php

namespace App;

use Psr\Log\LoggerInterface;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramResponseException;

class TelegramAdapter
{
    private Api $api;
    private array $allowedGroups;
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    public function __construct(string $botToken, array $allowedGroups, LoggerInterface $logger)
    {
        $this->api           = new Api($botToken);
        $this->allowedGroups = $allowedGroups;
        $this->logger        = $logger;
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
                $this->logger->error($e->__toString(), ['id' => $userId, 'group' => $group]);
                continue;
            }
        }

        return false;
    }
}
