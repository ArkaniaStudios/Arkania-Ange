<?php
declare(strict_types=1);

namespace arkania\player;

use arkania\Engine;
use arkania\form\BaseForm;
use arkania\lang\KnownTranslationsFactory;
use arkania\lang\Language;
use JsonException;
use pocketmine\form\Form;
use pocketmine\lang\Translatable;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\PermissibleBase;
use pocketmine\permission\PermissibleDelegateTrait;
use pocketmine\player\XboxLivePlayerInfo;

class Session {
    use SessionTrait {
        SessionTrait::__construct as private __sessionConstruct;
    }
    use PermissibleDelegateTrait;

    private XboxLivePlayerInfo $xboxLivePlayerInfo;
    private Language $language;

    /** @var Form[]|BaseForm[] */
    private array $forms = [];
    private int $formIdCounter = 0;

    public function __construct(
        NetworkSession $session,
        string $id
    ) {
        $this->__sessionConstruct($session, $id);
        $infos = $session->getPlayerInfo();
        if($infos instanceof XboxLivePlayerInfo) {
            $this->xboxLivePlayerInfo = $infos;
        }
        $rootPermissions = [DefaultPermissions::ROOT_USER => true];
        if(Engine::getInstance()->getServer()->isOp($this->getPlayer()->getName())){
            $rootPermissions[DefaultPermissions::ROOT_OPERATOR] = true;
        }
        $this->perm = new PermissibleBase($rootPermissions);
    }

    public function setLanguage(Language $language) : void {
        $this->language = $language;
    }

    public function getLanguage() : Language {
        return $this->language;
    }

    public function sendMessage(Translatable|string $message) : void {
        if($message instanceof Translatable) {
            $message = $this->getLanguage()->translate($message);
        }
        $this->networkSession->onChatMessage($message);
    }

    public function sendPopup(Translatable|string $message) : void {
        if($message instanceof Translatable) {
            $message = $this->getLanguage()->translate($message);
        }
        $this->networkSession->onPopup($message);
    }

    public function sendTip(Translatable|string $message) : void {
        if($message instanceof Translatable) {
            $message = $this->getLanguage()->translate($message);
        }
        $this->networkSession->onTip($message);
    }

    public function sendTitle(Translatable|string $title, Translatable|string $subtitle = "") : void {
        if($title instanceof Translatable) {
            $title = $this->getLanguage()->translate($title);
        }
        if($subtitle instanceof Translatable) {
            $subtitle = $this->getLanguage()->translate($subtitle);
        }
        $this->networkSession->onTitle($title);
        $this->networkSession->onSubtitle($subtitle);
    }

    public function getXboxLivePlayerInfo() : XboxLivePlayerInfo {
        return $this->xboxLivePlayerInfo;
    }

    public function disconnect(Translatable|string $reason, Translatable|string|null $disconnectScreenMessage = null, bool $notify = true) : void {
        if($reason instanceof Translatable) {
            $reason = $this->getLanguage()->translate($reason);
        }
        if($disconnectScreenMessage instanceof Translatable) {
            $disconnectScreenMessage = $this->getLanguage()->translate($disconnectScreenMessage);
        }
        $this->networkSession->disconnect($reason, $disconnectScreenMessage, $notify);
    }

    /**
     * @throws JsonException
     */
    public function sendForm(BaseForm|Form $form) : void {
        if($form instanceof BaseForm){
            if(!$this->hasPermission($form->getPermission())) {
                $this->sendMessage(
                    KnownTranslationsFactory::form_open_no_permission(
                        $form->getTitle()
                    )
                );
                return;
            }
        }
        $id = $this->formIdCounter++;
        if($this->getNetworkSession()->onFormSent($id, $form)){
            $this->forms[$id] = $form;
        }
    }

}