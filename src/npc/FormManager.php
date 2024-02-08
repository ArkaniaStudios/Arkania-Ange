<?php

/*
 *     _      ____    _  __     _      _   _   ___      _              _____   _   _    ____   ___   _   _   _____
 *    / \    |  _ \  | |/ /    / \    | \ | | |_ _|    / \            | ____| | \ | |  / ___| |_ _| | \ | | | ____|
 *   / _ \   | |_) | | ' /    / _ \   |  \| |  | |    / _ \    _____  |  _|   |  \| | | |  _   | |  |  \| | |  _|
 *  / ___ \  |  _ <  | . \   / ___ \  | |\  |  | |   / ___ \  |_____| | |___  | |\  | | |_| |  | |  | |\  | | |___
 * /_/   \_\ |_| \_\ |_|\_\ /_/   \_\ |_| \_| |___| /_/   \_\         |_____| |_| \_|  \____| |___| |_| \_| |_____|
 *
 * ArkaniaStudios-ANGE, une API conçue pour simplifier le développement.
 * Fournissant des outils et des fonctionnalités aux développeurs.
 * Cet outil est en constante évolution et est régulièrement mis à jour,
 * afin de répondre aux besoins changeants de la communauté.
 *
 * @author Julien
 * @link https://arkaniastudios.com
 * @version 0.2.0-beta
 *
 */

declare(strict_types=1);

namespace arkania\npc;

use arkania\form\class\CustomForm;
use arkania\form\class\SimpleForm;
use arkania\form\element\button\Button;
use arkania\form\element\elements\Dropdown;
use arkania\form\element\elements\Input;
use arkania\libs\muqsit\simplepackethandler\utils\Utils;
use arkania\npc\base\CustomEntity;
use arkania\npc\base\SimpleEntity;
use arkania\npc\type\customs\FloatingText;
use arkania\npc\type\HumanEntity;
use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;

class FormManager {
	use SingletonTrait;

	public function sendNpcWithItemForm(Player $player, CustomEntity|SimpleEntity $entity) : void {
		$form = new SimpleForm(
			$player,
			'§c- §fNPC §c-',
			'',
			[
				new Button('name', '§7» §rChanger de nom'),
				new Button('size', '§7» §rChanger la taille'),
				new Button('skin', '§7» §rChanger de Skin'),
				new Button('pos', '§7» §rChanger les positions'),
				new Button('inv', '§7» §rChanger l\'inventaire'),
				new Button('acmd', '§7» §rAjouter une commande'),
				new Button('scmd', '§7» §rSupprimer une commande'),
				new Button('del', '§7» §rSupprimer le NPC')
			],
			function (Player $player, $data) use ($entity) : void {
				switch ($data) {
					case 'name':
						$this->sendNpcChangeName($player, $entity);
						break;
					case 'size':
						$this->sendNpcChangeSize($player, $entity);
						break;
					case 'skin':
						if($entity instanceof HumanEntity) {
							$this->sendNpcChangSkinForm($player, $entity);
						} else {
							$player->sendMessage('Npc pas humain');
						}
						break;
					case 'pos':
						$this->sendNpcChangePositionForm($player, $entity);
						break;
					case 'inv':
						if($entity instanceof HumanEntity) {
							$this->sendNpcChangeInventory($player, $entity);
						} else {
							$player->sendMessage('Npc pas humain');
						}
						break;
					case 'acmd':
						if(empty($entity->getCommands()) || $entity->getCommands() === null) {
							$player->sendMessage('Aucune commande.');
							return;
						}
						$this->sendNpcRemoveCommandForm($player, $entity);

						break;
					case 'delete':
						$entity->flagForDespawn();
						$player->sendMessage('Npc supprimé.');
						break;
				}
			}
		);
		$player->sendForm($form);
	}

	private function sendNpcChangeName(Player $player, CustomEntity|SimpleEntity $entity) : void {
		$form = new CustomForm(
			$player,
			'§c- §fNPC §c-',
			[
				new Input('name', 'Nom du NPC')
			],
			function (Player $player, $response) use ($entity) : void {
				$entity->setNameTag($response['name']);
				$entity->setName($response['name']);
				$player->sendMessage('Nom du NPC changé.');
			}
		);
		$player->sendForm($form);
	}

	public function sendNpcChangeSize(Player $player, CustomEntity|SimpleEntity $entity) : void {
		$form = new CustomForm(
			$player,
			'§c- §fNPC §c-',
			[
				new Input('size', 'Taille du NPC', '1.0')
			],
			function (Player $player, $response) use ($entity) : void {
				$size = (float) $response['size'];
				if($size < 0.1 || $size > 10) {
					$player->sendMessage('Taille invalide.');
					return;
				}
				$entity->setScale($size);
				$entity->setTaille($size);
				$player->sendMessage('Taille du NPC changé.');
			}
		);
		$player->sendForm($form);
	}

	private function sendNpcChangSkinForm(Player $player, CustomEntity|SimpleEntity $entity) : void {
		$form = new SimpleForm(
			$player,
			'§c- §fNPC §c-',
			'',
			[
				new Button('skinp', '§7» §rSkin de Personnel'),
				new Button('skinj', '§7» §rSkin Joueur')
			],
			function (Player $player, $data) use ($entity) : void {
				switch ($data) {
					case 'skinp':
						$entity->setSkin($player->getSkin());
						$entity->sendSkin();
						$player->sendMessage('Skin du NPC changé.');
						break;
					case 'skinj':
						$this->sendNpcChangeSkinByNameForm($player, $entity);
						break;
				}
			}
		);
		$player->sendForm($form);
	}

	private function sendNpcChangeSkinByNameForm(Player $player, CustomEntity|SimpleEntity $entity) : void {
		$list = [];
		foreach ($player->getServer()->getOnlinePlayers() as $onlinePlayer) {
			$list[] = $onlinePlayer->getName();
		}
		$form = new CustomForm(
			$player,
			'§c- §fNPC §c-',
			[
				new Dropdown('name', $list)
			],
			function (Player $player, $response) use ($list, $entity) {
				$result     = $list[$response['name']];
				$playerName = $player->getServer()->getPlayerExact($result);
				if($playerName instanceof Player) {
					$entity->setSkin($playerName->getSkin());
					$entity->sendSkin();
					$player->sendMessage('Skin du NPC changé.');
				} else {
					$player->sendMessage('Joueur non trouvé.');
				}
			}
		);
		$player->sendForm($form);
	}

	private function sendNpcChangeInventory(Player $player, CustomEntity|SimpleEntity $entity) : void {
		$form = new SimpleForm(
			$player,
			'§c- §fNPC §c-',
			'',
			[
				new Button('changehand', '§7» §rChanger l\'object en main'),
				new Button('changearmor', '§7» §rChanger l\'armure'),
				new Button('clear', '§7» §rClear')
			],
			function (Player $player, $data) use ($entity) : void {
				switch ($data) {
					case 'changehand':
						$item = $player->getInventory()->getItemInHand();
						$entity->getInventory()->setItemInHand($item);
						$player->sendMessage('Inventaire du NPC changé.');
						break;
					case 'changearmor':
						$this->sendNpcChangeInventoryArmorForm($player, $entity);
						break;
					case 'clear':
						$entity->getInventory()->clearAll();
						$entity->getArmorInventory()->clearAll();
						$player->sendMessage('Inventaire du NPC vidé.');
						break;
				}
			}
		);
		$player->sendForm($form);
	}

	public function sendNpcChangeInventoryArmorForm(Player $player, CustomEntity|SimpleEntity $entity) : void {
		$form = new SimpleForm(
			$player,
			'§c- §fNPC §c-',
			'',
			[
				new Button('changeh', '§7» §rChanger le casque'),
				new Button('changec', '§7» §rChanger la plastron'),
				new Button('changel', '§7» §rChanger le pantalon'),
				new Button('changeb', '§7» §rChanger les bottes')
			],
			function (Player $player, $data) use ($entity) : void {
				switch ($data) {
					case 'changeh':
						$item = $player->getArmorInventory()->getHelmet();
						$entity->getArmorInventory()->setHelmet($item);
						$player->sendMessage('Casque du NPC changé.');
						break;
					case 'changec':
						$item = $player->getArmorInventory()->getChestplate();
						$entity->getArmorInventory()->setChestplate($item);
						$player->sendMessage('Plastron du NPC changé.');
						break;
					case 'changel':
						$item = $player->getArmorInventory()->getLeggings();
						$entity->getArmorInventory()->setLeggings($item);
						$player->sendMessage('Pantalon du NPC changé.');
						break;
					case 'changeb':
						$item = $player->getArmorInventory()->getBoots();
						$entity->getArmorInventory()->setBoots($item);
						$player->sendMessage('Bottes du NPC changé.');
						break;
				}
			}
		);
		$player->sendForm($form);
	}

	public function sendNpcChangePositionForm(Player $player, CustomEntity|SimpleEntity $entity) : void {
		$form = new CustomForm(
			$player,
			'§c- §fNPC §c-',
			[
				new Input('yaw', 'YAW du NPC', '0'),
				new Input('pitch', 'PITCH du NPC', '0')
			],
			function (Player $player, $response) use ($entity) : void {
				$yaw   = (float) $response['yaw'];
				$pitch = (float) $response['pitch'];
				if($yaw < -180 || $yaw > 180) {
					$player->sendMessage('Yaw invalide.');
					return;
				}
				if($pitch < -90 || $pitch > 90) {
					$player->sendMessage('Pitch invalide.');
					return;
				}
				$entity->setRotation($yaw, $pitch);
				$entity->setYaw($yaw);
				$entity->setPitch($pitch);
				$player->sendMessage('Position du NPC changé.');
			}
		);
		$player->sendForm($form);
	}

	private function sendNpcAddCommandForm(Player $player, CustomEntity|SimpleEntity $entity) : void {
		$form = new CustomForm(
			$player,
			'§c- §fNPC §c-',
			[
				new Dropdown('type', ['Joueur', 'Console']),
				new Input('command', 'Commande à ajouter')
			],
			function (Player $player, $response) use ($entity) : void {
				$type    = $response['type'];
				$command = $response['command'];
				$entity->addCommand($type, $command);
				$player->sendMessage('Commande ajoutée.');
			}
		);
		$player->sendForm($form);
	}

	private function sendNpcRemoveCommandForm(Player $player, CustomEntity|SimpleEntity $entity) : void {
		$form = new CustomForm(
			$player,
			'§c- §fNPC §c-',
			[
				new Dropdown('command', $entity->getCommands())
			],
			function (Player $player, $response) use ($entity) : void {
				$command     = $response['command'];
				$commandName = $entity->getCommands()[$command];
				if(!$entity->hasCommands($commandName)) {
					$player->sendMessage('Commande non trouvée.');
					return;
				}
				$entity->removeCommand($commandName);
				$player->sendMessage('Commande supprimée.');
			}
		);
		$player->sendForm($form);
	}

	public function sendNpcCreationForm(Player $player) : void {
		$form = new SimpleForm(
			$player,
			'§c- §fNPC §c-',
			'',
			[
				new Button('simple', 'Simple Entity'),
				new Button('custom', 'Custom Entity')
			],
			function (Player $player, $data) : void {
				switch ($data) {
					case 'simple':
						$this->sendCreationChooseSimpleNpcForm($player);
						break;
					case 'custom':
						$this->sendCreationCustomNpcForm($player);
						break;
				}
			}
		);
		$player->sendForm($form);
	}

	private function sendCreationChooseSimpleNpcForm(Player $player) : void {
		$form = new SimpleForm(
			$player,
			'§c- §fNPC §c-',
			'',
			[
				new Button('passif', 'Passif'),
				new Button('agressif', 'Agressif'),
				new Button('neutre', 'Neutre')
			],
			function (Player $player, $data) : void {
				switch ($data) {
					case 'passif':
						$this->sendCreationPassifsNpcForm($player);
						break;
					case 'agressif':
						$this->sendCreationAggresifsNpcForm($player);
						break;
					case 'neutre':
						$this->sendCreationNeutralsNpcForm($player);
						break;
				}
			}
		);
		$player->sendForm($form);
	}

	private function sendCreationPassifsNpcForm(Player $player) : void {
		$form = new SimpleForm(
			$player,
			'§c- §fNPC §c-',
			'',
			[
				new Button('axolotl', 'AXOLOTL'),
				new Button('chat', 'CHAT'),
				new Button('poulet', 'POULET'),
				new Button('cod', 'COD'),
				new Button('vache', 'VACHE'),
				new Button('ane', 'ÂNE'),
				new Button('grenouille', 'GRENOUILLE'),
				new Button('poulpel', 'POULPE L.'),
				new Button('cheval', 'CHEVAL'),
				new Button('mule', 'MULE'),
				new Button('ocelot', 'OCELOT'),
				new Button('panda', 'PANDA'),
				new Button('peroquet', 'PEROQUET'),
				new Button('cochon', 'COCHON'),
				new Button('lapin', 'LAPIN'),
				new Button('saumon', 'SAUMONT'),
				new Button('mouton', 'MOUTON'),
				new Button('poulpe', 'POULPE'),
				new Button('strider', 'STRIDER'),
				new Button('tetard', 'TETARD'),
				new Button('poissont', 'POISSON TROP'),
				new Button('tortue', 'TORTUE'),
				new Button('villageois', 'VILLAGEOIS'),
				new Button('marchand', 'MARCHAND')
			],
			function (Player $player, $data) : void {
				$mobId = match ($data) {
					'axolotl'    => 'axolotl',
					'chat'       => 'cat',
					'poulet'     => 'chicken',
					'cod'        => 'cod',
					'vache'      => 'cow',
					'ane'        => 'donkey',
					'grenouille' => 'frog',
					'poulpel'    => 'glowsquid',
					'cheval'     => 'horse',
					'mule'       => 'mule',
					'ocelot'     => 'ocelot',
					'panda'      => 'panda',
					'peroquet'   => 'parrot',
					'cochon'     => 'pig',
					'lapin'      => 'rabbit',
					'saumont'    => 'salmon',
					'mouton'     => 'sheep',
					'poulpe'     => 'squid',
					'strider'    => 'strider',
					'tatard'     => 'tadpole',
					'poissont'   => 'tropicalfish',
					'tortue'     => 'turtle',
					'villageois' => 'villager',
					'marchand'   => 'wanderingtrader'
				};
				$this->sendCreateNpcByTypeForm($player, $mobId);
			}
		);
		$player->sendForm($form);
	}

	private function sendCreationAggresifsNpcForm(Player $player) : void {
		$form = new SimpleForm(
			$player,
			'§c- §fNPC §c-',
			'',
			[
				new Button('blaze', 'BLAZE'),
				new Button('araigneec', 'ARAIGNEE C.'),
				new Button('creeper', 'CREEPER'),
				new Button('znoye', 'NOYE'),
				new Button('gardiena', 'GARDIEN A.'),
				new Button('dragon', 'DRAGON'),
				new Button('enderman', 'ENDERMAN'),
				new Button('endermite', 'ENDERMITE'),
				new Button('ghast', 'GHAST'),
				new Button('gardien', 'GARDIEN'),
				new Button('hoglin', 'HOGLIN'),
				new Button('husk', 'HUSK'),
				new Button('magma', 'MAGMA C.'),
				new Button('phantom', 'PHANTOM'),
				new Button('piglin', 'PIGLIN'),
				new Button('pillard', 'PILLARD'),
				new Button('ravageur', 'RAVAGEUR'),
				new Button('shulker', 'SHULKER'),
				new Button('silver', 'SILVER F.'),
				new Button('squelette', 'SQUELETTE'),
				new Button('slime', 'SLIME'),
				new Button('araignee', 'ARAIGNEE'),
				new Button('errant', 'ERRANT'),
				new Button('vex', 'VEX'),
				new Button('vindicateur', 'VINDICATEUR'),
				new Button('warden', 'WARDEN'),
				new Button('sorciere', 'sorciere'),
				new Button('wither', 'WITHER'),
				new Button('wsquelette', 'SQUELETTE W.'),
				new Button('zoglin', 'ZOGLIN'),
				new Button('zombie', 'ZOMBIE'),
				new Button('zombiev', 'ZOMBIE V.')
			],
			function (Player $player, string $data) : void {
				$mobId = match ($data) {
					'blaze'       => 'blaze',
					'araigneec'   => 'cavespider',
					'creepe'      => 'creeper',
					'znoye'       => 'drowned',
					'gardiena'    => 'elderguardian',
					'dragon'      => 'enderdragon',
					'enderman'    => 'enderman',
					'endermite'   => 'endermite',
					'ghast'       => 'ghast',
					'gardien'     => 'guardian',
					'hoglin'      => 'hoglin',
					'husk'        => 'husk',
					'magma'       => 'magmacube',
					'phantom'     => 'phantom',
					'piglin'      => 'piglin',
					'pillager'    => 'pillager',
					'ravager'     => 'ravager',
					'shulker'     => 'shulker',
					'silver'      => 'silverfish',
					'squelette'   => 'skeleton',
					'slime'       => 'slime',
					'araignee'    => 'spider',
					'errant'      => 'stray',
					'vex'         => 'vex',
					'vindicateur' => 'vindicator',
					'warden'      => 'warden',
					'sorciere'    => 'witch',
					'wither'      => 'wither',
					'wsquelette'  => 'wither_skeleton',
					'zoglin'      => 'zoglin',
					'zombie'      => 'zombie',
					'zombiev'     => 'zombie_villager'
				};
				$this->sendCreateNpcByTypeForm($player, $mobId);
			}
		);
		$player->sendForm($form);
	}

	private function sendCreationNeutralsNpcForm(Player $player) : void {
		$form = new SimpleForm(
			$player,
			'§c- §fNPC §c-',
			'',
			[
				new Button('chauves', 'CHAUVE.S'),
				new Button('abeille', 'BEE'),
				new Button('dauphin', 'DAUPHIN'),
				new Button('fox', 'FOX'),
				new Button('chevre', 'CHEVRE'),
				new Button('golem', 'IRON GOLEM'),
				new Button('lama', 'LAMA'),
				new Button('ourse', 'POLAR BEAR'),
				new Button('chevals', 'SQUELET HORSE'),
				new Button('snow', 'SNOW GOLEM'),
				new Button('chien', 'WOLF'),
				new Button('chevalz', 'ZOMBIE HORSE'),
				new Button('zombie', 'ZOMBIE PORCIN')
			],
			function (Player $player, $data) : void {
				$mobId = match ($data) {
					'chauves' => 'bat',
					'abeille' => 'bee',
					'dauphin' => 'dolphin',
					'fox'     => 'fox',
					'chevre'  => 'goat',
					'golem'   => 'irongolem',
					'lama'    => 'llama',
					'ourse'   => 'polarbear',
					'chevals' => 'skeletonhorse',
					'snow'    => 'snowgolem',
					'chien'   => 'wolf',
					'chevalz' => 'zombiehorse',
					'zombie'  => 'zombifiedpiglin'
				};
				$this->sendCreateNpcByTypeForm($player, $mobId);
			}
		);
		$player->sendForm($form);
	}

	private function sendCreateNpcByTypeForm(Player $player, string $type) : void {
		$form = new CustomForm(
			$player,
			'§c- §fNPC §c-',
			[
				new Input('name', 'Nom du NPC', 'aucun')
			],
			function (Player $player, $response) use ($type) : void {
				$name = $response['name'];
				if($type === 'human') {
					$entity = new HumanEntity(
						$player->getLocation(),
						$player->getSkin()
					);
					$entity->setNpc();
					$entity->setNameTagAlwaysVisible();
					if($name === '') {
						$entity->setName($player->getName());
						$entity->setNameTag($player->getName());
					} else {
						$entity->setName($name);
						$entity->setNameTag($name);
					}
					$player->sendMessage('Npc créé.');
					$entity->spawnToAll();
					return;
				}
				if($type === 'floatingtext') {
					$entity = new FloatingText($player->getLocation());
					$entity->setName($name);
					$entity->spawnToAll();
					$player->sendMessage('Npc créé.');
					return;
				}
				$entity = Utils::getEntityById($player->getLocation(), $type);
				$entity->setNpc();
				$entity->setName($name);
				$entity->setNameTag($name);
				$entity->setNameTagAlwaysVisible();
				$entity->spawnToAll();
				$player->sendMessage('Npc créé.');
			}
		);
		$player->sendForm($form);
	}

	private function sendCreationCustomNpcForm(Player $player) : void {
		$form = new SimpleForm(
			$player,
			'§c- §fNPC §c-',
			'',
			[
				new Button('ballon', 'Ballon'),
				new Button('human', 'Humain'),
				new Button('floating', 'FloatingText')
			],
			function (Player $player, $data) : void {
				if($data === null) {
					return;
				}
				switch ($data) {
					case 'ballon':
						$this->sendCreateNpcByTypeForm($player, 'ballon');
						break;
					case 'human':
						$this->sendCreateNpcByTypeForm($player, 'human');
						break;
					case 'floating':
						$this->sendCreateNpcByTypeForm($player, 'floatingtext');
						break;
				}
			}
		);
		$player->sendForm($form);
	}

}
