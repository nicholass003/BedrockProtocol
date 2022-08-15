<?php

/*
 * This file is part of BedrockProtocol.
 * Copyright (C) 2014-2022 PocketMine Team <https://github.com/pmmp/BedrockProtocol>
 *
 * BedrockProtocol is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\protocol\types\skin\SkinData;
use Ramsey\Uuid\UuidInterface;

final class PlayerListAdditionEntry{
	public function __construct(
		public UuidInterface $uuid,
		public int $actorUniqueId,
		public string $username,
		public SkinData $skinData,
		public string $xboxUserId,
		public string $platformChatId = "",
		public int $buildPlatform = DeviceOS::UNKNOWN,
		public bool $isTeacher = false,
		public bool $isHost = false
	){}
}
