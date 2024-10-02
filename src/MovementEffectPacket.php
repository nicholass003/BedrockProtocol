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

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\types\MovementEffectType;

class MovementEffectPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::MOVEMENT_EFFECT_PACKET;

	private int $actorRuntimeId;
	private MovementEffectType $effectType;
	private int $effectDuration;
	private int $tick;

	public static function create(int $actorRuntimeId, MovementEffectType $effectType, int $effectDuration, int $tick) : self{
		$result = new self;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->effectType = $effectType;
		$result->effectDuration = $effectDuration;
		$result->tick = $tick;
		return $result;
	}

	public function getActorRuntimeId() : int{
		return $this->actorRuntimeId;
	}

	public function getEffectType() : MovementEffectType{
		return $this->effectType;
	}

	public function getEffectDuration() : int{
		return $this->effectDuration;
	}

	public function getTick() : int{
		return $this->tick;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->actorRuntimeId = $in->getActorRuntimeId();
		$this->effectType = MovementEffectType::fromPacket($in->getByte());
		$this->effectDuration = $in->getByte();
		$this->tick = $in->readPlayerInputTick();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putActorRuntimeId($this->actorRuntimeId);
		$out->putByte($this->effectType->value);
		$out->putByte($this->effectDuration);
		$out->writePlayerInputTick($this->tick);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleMovementEffect($this);
	}
}
