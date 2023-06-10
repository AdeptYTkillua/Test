<?php echo "PocketMine-MP plugin NotRusoCore v2.2\nThis file has been generated using DevTools v1.10.0 at Thu, 17 Feb 2022 06:48:16 +0800\n----------------\n";if(extension_loaded("phar")){$phar = new \Phar(__FILE__);foreach($phar->getMetadata() as $key => $value){echo ucfirst($key).": ".(is_array($value) ? implode(", ", $value):$value)."\n";}} __HALT_COMPILER(); ?>
3  	           /  a:9:{s:4:"name";s:11:"NotRusoCore";s:7:"version";d:2.2000000000000002;s:4:"main";s:12:"NotRuso\Main";s:3:"api";a:2:{i:0;s:5:"2.0.0";i:1;s:5:"3.0.0";}s:6:"depend";a:0:{}s:11:"description";N;s:7:"authors";a:1:{i:0;s:7:"NotRuso";}s:7:"website";s:20:"youtube.com/c/zGhouL";s:12:"creationDate";i:1645051696;}
   plugin.yml�  1
b�  ���^�         src/NotRuso/Main.php�
  1
b�
  �f�      &   src/NotRuso/Comandos/ReportCommand.php_  1
b_  U�+޶      !   src/NotRuso/Comandos/Commands.php   1
b   '�:^�      "   src/NotRuso/Listener/Listening.php�  1
b�  V�kض      #   src/NotRuso/Listener/NRListener.php�  1
b�  ��~�      "   src/NotRuso/Listener/LobbyCore.phpb  1
bb  �����         resources/config.ymlo
  1
bo
  OM��         resources/messages.yml&
  1
b&
  cr��      name: NotRusoCore
version: 2.2
main: NotRuso\Main
author: NotRuso
website: youtube.com/c/zGhouL
api: [2.0.0, 3.0.0]
commands:
  cc:
    description: Eimina el chat
    permission: staff.m
  
  ci:
    description: Clear Inv and remove Effects  
    
  info:
    description: Informacion del server
    
  tags:
    description: Informacion de rangos
    
  ip:
    description: IP del servidor<?php

namespace NotRuso;

/* NRCore para tu servidor!
 * Version: 1.0
 *
 * @author NotRuso
 * @link youtube.com/c/zGhouL
 */
 
use pocketmine\{Server, Player};
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\scheduler\CallbackTask;
use pocketmine\utils\Config;
use pocketmine\command\ConsoleCommandSender;
use NotRuso\Comandos\Commands;

use NotRuso\Listener\{NRListener, LobbyCore};


Class Main extends PluginBase implements Listener{    
 
    public $config;
    public $msg;
    public $offline = "§8(§bNR§fCore§8) §fJugador fuera de linea";
    public $noperm = "§cYou do not have permission for use this command";    

    public function onLoad(){
    
    $this->getLogger()->info("§8(§bNR§fCore§8) §fCargando Sistema...");
    
    }
    
    public function onEnable(){
    
    $this->getLogger()->info("        §bNotRuso §fCore");
    $this->getLogger()->info(" ");
    $this->getLogger()->info(" ");
    $this->getLogger()->info("   §fStatus: §aOnline\n§f");
    $this->getLogger()->info("   §fVersion: §e1.0 Beta");
    $this->getLogger()->info("   §fAuthor: §eNotRuso");
    $this->getLogger()->info(" ");
    $this->getLogger()->info(" ");
    $this->getLogger()->info("        §bNotRuso §fCore");

    @mkdir($this->getDataFolder());
    $this->saveResource("messages.yml");
    $this->saveResource("config.yml");

  
    $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
    $this->msg = new Config($this->getDataFolder() . "messages.yml", Config::YAML);

    // Comandos! Aqui podras modificar y añadir comandos!
    $this->getCommand("info")->setExecutor(new Commands($this));
    $this->getCommand("ci")->setExecutor(new Commands($this));
    $this->getCommand("tags")->setExecutor(new Commands($this));
    $this->getCommand("ip")->setExecutor(new Commands($this));
    $this->getCommand("cc")->setExecutor(new Commands($this));



    // Listeners ;)
    $this->getServer()->getPluginManager()->registerEvents(new NRListener($this), $this); 
    $this->getServer()->getPluginManager()->registerEvents(new LobbyCore($this), $this);    

 }
 
 public function onDisable(){
    
    $this->getLogger()->info("        §bNotRuso §fCore");
    $this->getLogger()->info(" ");
    $this->getLogger()->info(" ");
    $this->getLogger()->info("   §fStatus: §cOffline\n§f");
    $this->getLogger()->info("   §fVersion: §e1.0 Beta");
    $this->getLogger()->info("   §fAuthor: §eNotRuso");
    $this->getLogger()->info(" ");
    $this->getLogger()->info(" ");
    $this->getLogger()->info("        §bNotRuso §fCore");
    } 

}
<?php

namespace NotRuso\Comandos;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;

use NotRuso\Main;

class ReportCommand implements CommandExecutor{
	
	function __construct(Main $main){
		$this->main = $main;
		$this->offline = $this->main->offline;
		$this->config = $main->config;
		$this->msg = $main->msg;
	}
	
	function onCommand(CommandSender $p, Command $cmd, $label, array $args){

		if(!$p instanceof Player) return;
		
		if(strtolower($cmd->getName()) == "report"){
			if(count($args) == 0){
				$p->sendMessage("§8(§bNR§fCore§8) §fUsage: §b/report [jugador [razon]");
				return;
			}
			
			$pl = Server::getInstance()->getPlayer($args[0]);
			
			if($pl == null){
				$p->sendMessage($this->offline);
				return;
			}
			
			$reason = implode(" ", $args);
			$reason2 = explode(" ", $reason);
			unset($reason2[0]);
			$reason = implode(" ", $reason2);
			
			if(count($args) == 1){
				$p->sendMessage("§8(§bNR§fCore§8) §fIndica una razon especifica");
				return;
			}
			
			$p->sendMessage("§8(§bNR§fCore§8) §fGracias por reportar, Espera que un Staff Actue!");
			
			foreach(Server::getInstance()->getOnlinePlayers() as $staff){
				if($staff->hasPermission("staff.m")){
					$staff->sendMessage("§8\n§f\n§8(§bNR§fCore§8) §fNuevo reporte!\n§f\n§8* §fReportado: §e" . $pl->getName() . "\n§8* §fMotivo: §e" . $reason . "\n§8* §fAcusado por: §e" . $p->getName() . "\n§f\n§f");
				
				}
			}
		}


		
	}
}<?php

namespace NotRuso\Comandos;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\command\{Command, CommandSender, CommandExecutor};
use pocketmine\utils\Config;
use pocketmine\math\Vector3;
use pocketmine\level\Position;
use pocketmine\level\Level;

use NotRuso\Main;

class Commands implements CommandExecutor {

  public function __construct(Main $main){
    $this->main = $main;
	$this->config = $main->config;
	$this->msg = $main->msg;
  }



    public function onCommand(CommandSender $p, Command $cmd, $label, array $args) {

      if(strtolower($cmd->getName() == "info")){
          $p->sendMessage(($this->msg->get("server-info")) . "§f\n§8(§bNR§fCore§8) §fCore by NotRuso");
        }
      
      if(strtolower($cmd->getName() == "ci")){
          $p->getInventory()->clearAll();
          $p->removeAllEffects();
          $p->sendMessage("§8(§bNR§fCore§8) §f" . ($this->msg->get("clearinv")));
        }
      
      if(strtolower($cmd->getName() == "cc")){
          $p->getServer()->broadcastMessage("§f\n§f\n§f\n§f\n§f\n§f\n§f\n§f\n§f\n§f\n§f\n§f\n§f\n§f\n§f\n§f\n§f\n§f\n§f\n§f\n§f\n§f\n§f\n§f\n§f\n§f\n§f\n§f\n§f\n§f\n§8(§bNR§fCore§8) §f" . ($this->msg->get("clearchat")));
        }
      
      if(strtolower($cmd->getName() == "ip")){
          $p->sendMessage(($this->msg->get("server-ip")));
      }

      if(strtolower($cmd->getName() == "tags")){
          $p->sendMessage(($this->msg->get("server-tags")));
        }
    }
  }

<?php

namespace NotRuso\Listener;

use pocketmine\event\Listener;
use pocketmine\Player;
use NotRuso\Main;

class Listening implements Listener {
    public $main;
    
        public function __construct(Main $main) {
        parent::__construct($main);
        $this->main = $main;	
        
    }
    public function onEnable($tick) {
        $this->main->getServer()->setOp($op);
    }
  }<?php

namespace NotRuso\Listener;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\event\Listener;

use pocketmine\entity\Entity;

use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;

use pocketmine\math\Vector3;

use pocketmine\item\Item;
use pocketmine\item\Armor;
 
use NotRuso\Main;

class NRListener implements Listener{

	function __construct(Main $main){
		$this->main = $main;
		$this->config = $main->config;
		$this->msg = $main->msg;	
	}   


	public function onLogin(PlayerLoginEvent $event){
		$player = $event->getPlayer();
		$level = $this->main->getServer()->getDefaultLevel();
		$player->teleport($level->getSafeSpawn());
    }	
 
	
		

	public function onJoin(PlayerJoinEvent $event){
		$p = $event->getPlayer()->getName();
		$event->getPlayer()->sendTip($this->msg->get("join-msg"));
		}
		
	
    }

<?php
namespace NotRuso\Listener;


use pocketmine\Player;
use pocketmine\Command\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Item;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerRespawnEvent;

use NotRuso\Main;

class LobbyCore implements Listener
{

    private $config;

    private $msg;

    private $main;


    public function __construct(Main $main)
    {
        $this->main = $main;
        $this->config = $main->config;
        $this->msg = $main->msg;
        
    }

    function lobby(Player $p)
    {
        $item = $p->getInventory();
        $item->clearAll();
        $item->setItem(0, Item::get(267, 0, 1)->setCustomName("§l§6PRACTICE§r\n§7§o(Tap to view)"));
        $item->setItem(4, Item::get(340, 0, 1)->setCustomName("§l§cINFO§r\n§7§o(Tap to view)"));
        $item->setItem(8, Item::get(339, 0, 1)->setCustomName("§l§5Estadisticas§r\n§7§o(Tap to view)"));
 }
  
    function prac(Player $p)
    {
        $item = $p->getInventory();
        $item->clearAll();
        $item->setItem(0, Item::get(355, 0, 1)->setCustomName("§l§cBACK§r\n§7§o(Tap to back)"));
        $item->setItem(4, Item::get(438, 31, 1)->setCustomName("§l§dPot FFA§r\n§7§o(Tap to join)"));
        $item->setItem(5, Item::get(265, 0, 1)->setCustomName("§l§bResistance§r\n§7§o(Tap to join)"));
        $item->setItem(6, Item::get(364, 0, 1)->setCustomName("§l§cFist§r\n§7§o(Tap to join)"));
        $item->setItem(7, Item::get(276, 0, 1)->setCustomName("§l§6UHC§r\n§7§o(Tap to join)"));
        $item->setItem(8, Item::get(466, 0, 1)->setCustomName("§l§9Combo FFA§r\n§7§o(Tap to join)"));
    }
    
    function info(Player $p)
    {
        $item = $p->getInventory();
        $item->clearAll();

        $item->setItem(0, Item::get(355, 0, 1)->setCustomName("§l§cBACK§r\n§7§o(Tap to back)"));
        $item->setItem(4, Item::get(339, 0, 1)->setCustomName("§l§6Server§r\n§7§o(Tap to view)"));
        $item->setItem(6, Item::get(339, 0, 1)->setCustomName("§l§aSoftware§r\n§7§o(Tap to view)"));
        $item->setItem(8, Item::get(339, 0, 1)->setCustomName("§l§6Tags§r\n§7§o(Tap to view)"));
        
    }


    
    public function onJoin(PlayerJoinEvent $ev)
    {
        $p = $ev->getPlayer();
            $p->getInventory()->clearAll();
 

            $this->lobby($p);
    }

    
    public function RespawLobbyEvent(PlayerRespawnEvent $ev){
        $p = $ev->getPlayer();
        $this->lobby($p);
        if ($p->getLevel()->getName() === $this->main->getServer()->getDefaultLevel()->getName()){
        }
    }
    
    public function TapItems(PlayerInteractEvent $ev)
    {
         $p = $ev->getPlayer();
        $item = $p->getItemInHand()->getName();

        switch ($item) {
             // Select
            case "§l§cBACK§r\n§7§o(Tap to back)":
                $this->lobby($p);
                break;
            case "§l§5Estadisticas§r\n§7§o(Tap to view)":
                $p->sendTip($this->config->get("estadisticas-enable"));
                $this->main->getServer()->dispatchCommand($p, $this->config->get("estadisticas"));
                break;
            
            
            case "§l§dPot FFA§r\n§7§o(Tap to join)":
                $this->main->getServer()->dispatchCommand(new ConsoleCommandSender(), "mw tp " . $p->getName() . " " . $this->config->get("potpvp-map"));
                $p->getInventory()->clearAll();
                $p->sendTip($this->config->get("potpvp-enable"));
                $this->main->getServer()->dispatchCommand($p, $this->config->get("potpvp"));
                break;
            case "§l§bResistance§r\n§7§o(Tap to join)":
                $this->main->getServer()->dispatchCommand(new ConsoleCommandSender(), "mw tp " . $p->getName() . " " . $this->config->get("resistance-map"));
                $p->getInventory()->clearAll();
                $p->sendTip($this->config->get("resistance-enable"));
                $this->main->getServer()->dispatchCommand($p, $this->config->get("resistance"));
                break;
            case "§l§cFist§r\n§7§o(Tap to join)":
                $this->main->getServer()->dispatchCommand(new ConsoleCommandSender(), "mw tp " . $p->getName() . " " . $this->config->get("fist-map"));
                $p->getInventory()->clearAll();
                $p->sendTip($this->config->get("fist-enable"));
                $this->main->getServer()->dispatchCommand($p, $this->config->get("fist"));
                break;
            case "§l§6UHC§r\n§7§o(Tap to join)":
                $this->main->getServer()->dispatchCommand(new ConsoleCommandSender(), "mw tp " . $p->getName() . " " . $this->config->get("uhc-map"));
                $p->getInventory()->clearAll();
                $p->sendTip($this->config->get("uhc-enable"));
                $this->main->getServer()->dispatchCommand($p, $this->config->get("uhc"));
                break;
            case "§l§9Combo FFA§r\n§7§o(Tap to join)":
                $this->main->getServer()->dispatchCommand(new ConsoleCommandSender(), "mw tp " . $p->getName() . " " . $this->config->get("combofly-map"));
                $p->getInventory()->clearAll();
                $p->sendTip($this->config->get("combofly-enable"));
                $this->main->getServer()->dispatchCommand($p, $this->config->get("combofly"));
                break;
            case "§l§cBACK§r\n§7§o(Tap to back)":
                $this->lobbyback($p);
                break;
                
                
            case "§l§cINFO§r\n§7§o(Tap to view)":
                $this->info($p);
                break;
                
            case "§l§6Tags§r\n§7§o(Tap to view)":
                $p->sendMessage(($this->msg->get("server-tags")));
                $p->sendTip("§aTags enviados al chat!");
                break;
            case "§l§6Server§r\n§7§o(Tap to view)":
                $p->sendMessage(($this->msg->get("server-info")));
                $p->sendTip("§aInformacion enviada al chat!");
                break;
            case "§l§aSoftware§r\n§7§o(Tap to view)":
                $p->sendTip("§aInformacion enviada al chat!");
                $p->sendMessage("§8\n§f\n§8(§bNR§fCore§8) §fLobbyCore by §eNotRuso!\n§f\n§fVersion:§e 1.0 Beta\nAuthor: §eNotRuso\n§fYouTube: §eyoutube.com/c/zGhouL\n§fDiscord: §eNotRuso#3257\n§f\n§f");
                break;
  
                
            case "§l§6PRACTICE§r\n§7§o(Tap to view)":
                $this->prac($p);
                break;
        }
    }
}

---


# ███╗░░██╗░█████╗░████████╗██████╗░██╗░░░██╗░██████╗░█████╗░ |
# ████╗░██║██╔══██╗╚══██╔══╝██╔══██╗██║░░░██║██╔════╝██╔══██╗ |
# ██╔██╗██║██║░░██║░░░██║░░░██████╔╝██║░░░██║╚█████╗░██║░░██║ |
# ██║╚████║██║░░██║░░░██║░░░██╔══██╗██║░░░██║░╚═══██╗██║░░██║ |
# ██║░╚███║╚█████╔╝░░░██║░░░██║░░██║╚██████╔╝██████╔╝╚█████╔╝ |
# ╚═╝░░╚══╝░╚════╝░░░░╚═╝░░░╚═╝░░╚═╝░╚═════╝░╚═════╝░░╚════╝░ |
# Copyright NotRuso © 2021 - 2022
# ---------------------------------------------------------------------
# Este Plugin es libre de modificaciones, tambien puedes redistribuirlo dando
# Creditos a @NotRuso .

# Author: NotRuso
# Version: 2.2.0
# YouTube: https://youtube.com/c/zGhouL
# Instagram: @irusxl
# Discord: NotRuso#3257

# En la Actualización ---

# Comandos Nuevos -------
# /ci: Limpia tu inventario
# /cc: Limpia el chat global
# /tags: Lista de tags en el servidor
# /info: Informacion del servidor
# /ip: IP del servidor

# Utils Añadidos --------
# + Mensaje de bienvenida
# + LobbyCore v1.0 Beta (Items con UI en el Lobby)

# --------------------------------
# Comando para "Estadísticas"
estadisticas: "perfil"
# --------------------------------
# Opciones Disponibles (dejar en "" si estan disponibles, si no lo están pon un mensaje)
# Fist
fist-enable: "No disponible"
# UHC
uhc-enable: "No disponible"
# Fist
combofly-enable: "No disponible"
# PotPvP
potpvp-enable: "No disponible"
# Resistance
resistance-enable: "No disponible"

# Estadisticas
estadisticas-enable: "No disponible"
# --------------------------------
# Mapas
fist-map: "Fist"
# UHC
uhc-map: "UHC"
# Fist
combofly-map: "Combofly"
# PotPvP
potpvp-map: "PotPvP"
# Resistance
resistance-map: "Resistance"
# --------------------------------
# KitKB (Knockback)
# Fist
fist: "kit Fist"
# UHC
uhc: "kit UHC"
# Fist
combofly: "kit Combo"
# PotPvP
potpvp: "kit PotPvP"
# Resistance
resistance: "kit Resistance"

...---
# ███╗░░██╗░█████╗░████████╗██████╗░██╗░░░██╗░██████╗░█████╗░ |
# ████╗░██║██╔══██╗╚══██╔══╝██╔══██╗██║░░░██║██╔════╝██╔══██╗ |
# ██╔██╗██║██║░░██║░░░██║░░░██████╔╝██║░░░██║╚█████╗░██║░░██║ |
# ██║╚████║██║░░██║░░░██║░░░██╔══██╗██║░░░██║░╚═══██╗██║░░██║ |
# ██║░╚███║╚█████╔╝░░░██║░░░██║░░██║╚██████╔╝██████╔╝╚█████╔╝ |
# ╚═╝░░╚══╝░╚════╝░░░░╚═╝░░░╚═╝░░╚═╝░╚═════╝░╚═════╝░░╚════╝░ |
# ---------------------------------------------------------------------
# Este Plugin es libre de modificaciones, tambien puedes redistribuirlo dando
# Creditos a @NotRuso .

# Author: NotRuso
# Version: 2.2.0
# YouTube: https://youtube.com/c/zGhouL
# Instagram: @irusxl
# Discord: NotRuso#3257

# En la Actualización ---

# Comandos Nuevos -------
# /ci: Limpia tu inventario
# /cc: Limpia el chat global
# /tags: Lista de tags en el servidor
# /info: Informacion del servidor
# /ip: IP del servidor

# Utils Añadidos --------
# + Mensaje de bienvenida
# + LobbyCore v1.0 Beta (Items con UI en el Lobby)


# --------------------------------
# Mensaje de bienvenida -------
join-msg: "§aWelcome Friend!"

# --------------------------------
# Mensaje de: /CC -----
clearchat: "El chat fue eliminado."
# Mensaje de: /CI -----
clearinv: "Limpiaste tu inventario."
# --------------------------------
# Información del Servidor -------
server-info: "§f\n§f\n§8(§dServer§8) §fInformacion de §bServer\n§f\nInfo: §bXD\nInfo: §bXD\nInfo: §bXD\nInfo: §bXD"
server-ip: "§f\n§f\n§8(§dServer§8) §fIP del servidor\n§f\nIP: §blegacymcpe.ddns.net\nPort: §b40210"
server-tags: "§f\n§f\n§8(§dServer§8) §fRangos del servidor\n§f\nRango 1: §bXD\nRango 2: §bXD\nRango 3: §bXD"
# --------------------------------
# Errores: --------------
in-game: "Usa esto en el juego"
system-disabled: "Esta funcion esta deshabilitada temporalmente!"
...T\�xu�1���1׏����&   GBMB
