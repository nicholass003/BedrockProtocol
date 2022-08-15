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

use Ramsey\Uuid\UuidInterface;

/**
 * This concrete type exists to allow using instanceof to determine the type of entries in PlayerListPacket, since
 * Type1[]|Type2[] is the same as (Type1|Type2)[].
 *
 * A generic Set type would serve this purpose too, but would be less convenient for instanceof type verification.
 */
final class PlayerListRemovalEntries{

	/**
	 * @param UuidInterface[] $entries
	 */
	public function __construct(
		public array $entries
	){}
}
