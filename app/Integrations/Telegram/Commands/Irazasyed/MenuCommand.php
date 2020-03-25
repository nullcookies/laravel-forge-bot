<?php

namespace App\Integrations\Telegram\Commands\Irazasyed;

use App\Integrations\Telegram\MenuManager;
use Telegram\Bot\Actions;
use Telegram\Bot\Objects\Message;

class MenuCommand extends Command
{
    /**
     * @var string Command name.
     */
    protected $name = "menu";

    /**
     * @var string Command description.
     */
    protected $description = "Shows a menu for managing your Laravel Forge servers.";

    /**
     * Handle the command.
     *
     * @param $arguments
     */
    public function handle($arguments)
    {
        /** @var Message $message */
        $message = $this->replyWithMessage([
            'text' => 'Creating a new menu...',
        ]);

        $this->replyWithChatAction([
            'action' => Actions::TYPING,
        ]);

        MenuManager::make($this->user(), $message->getMessageId());
    }
}