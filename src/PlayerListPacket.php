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
use pocketmine\network\mcpe\protocol\types\PlayerListAdditionEntries;
use pocketmine\network\mcpe\protocol\types\PlayerListAdditionEntry;
use pocketmine\network\mcpe\protocol\types\PlayerListRemovalEntries;
use function count;

class PlayerListPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::PLAYER_LIST_PACKET;

	private const TYPE_ADD = 0;
	private const TYPE_REMOVE = 1;

	public PlayerListAdditionEntries|PlayerListRemovalEntries $list;

	/**
	 * @generate-create-func
	 */
	public static function create(PlayerListAdditionEntries|PlayerListRemovalEntries $list) : self{
		$result = new self;
		$result->list = $list;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$type = $in->getByte();
		$count = $in->getUnsignedVarInt();
		if($type === self::TYPE_ADD){
			$entries = [];
			for($i = 0; $i < $count; ++$i){
				$uuid = $in->getUUID();
				$actorUniqueId = $in->getActorUniqueId();
				$username = $in->getString();
				$xboxUserId = $in->getString();
				$platformChatId = $in->getString();
				$buildPlatform = $in->getLInt();
				$skinData = $in->getSkin();
				$isTeacher = $in->getBool();
				$isHost = $in->getBool();
				$entries[$i] = new PlayerListAdditionEntry($uuid, $actorUniqueId, $username, $skinData, $xboxUserId, $platformChatId, $buildPlatform, $isTeacher, $isHost);
			}

			for($i = 0; $i < $count; ++$i){
				$entries[$i]->skinData->setVerified($in->getBool());
			}

			$this->list = new PlayerListAdditionEntries($entries);
		}elseif($type === self::TYPE_REMOVE){
			$entries = [];
			for($i = 0; $i < $count; ++$i){
				$entries[] = $in->getUUID();
			}

			$this->list = new PlayerListRemovalEntries($entries);
		}else{
			throw new PacketDecodeException("Unknown PlayerListPacket type $type");
		}
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putByte($this->list instanceof PlayerListAdditionEntries ? self::TYPE_ADD : self::TYPE_REMOVE);
		$out->putUnsignedVarInt(count($this->list->entries));
		if($this->list instanceof PlayerListAdditionEntries){
			foreach($this->list->entries as $entry){
				$out->putUUID($entry->uuid);
				$out->putActorUniqueId($entry->actorUniqueId);
				$out->putString($entry->username);
				$out->putString($entry->xboxUserId);
				$out->putString($entry->platformChatId);
				$out->putLInt($entry->buildPlatform);
				$out->putSkin($entry->skinData);
				$out->putBool($entry->isTeacher);
				$out->putBool($entry->isHost);
			}

			foreach($this->list->entries as $entry){
				$out->putBool($entry->skinData->isVerified());
			}
		}else{
			foreach($this->list->entries as $entry){
				$out->putUUID($entry);
			}
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePlayerList($this);
	}
}
