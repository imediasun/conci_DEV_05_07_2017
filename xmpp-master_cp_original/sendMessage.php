<?php
#ini_set("display_error",1);
require 'vendor/autoload.php';
error_reporting(0);
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Fabiang\Xmpp\Options;
use Fabiang\Xmpp\Client;
use Fabiang\Xmpp\Protocol\Roster;
use Fabiang\Xmpp\Protocol\Presence;
use Fabiang\Xmpp\Protocol\Message;

require_once 'config.php';

$reciever = @$_REQUEST['username'].'@'.vhost_name;
$notification  = @$_REQUEST['message'];

$logger = new Logger('xmpp');
$logger->pushHandler(new StreamHandler(dirname(__FILE__).'/log.txt', Logger::DEBUG));
$hostname       = vhost_name;
$port           = 5222;
$connectionType = 'tcp';
$address        = "$connectionType://$hostname:$port";
$username = vhost_admin_name;
$password = vhost_admin_password;
$options = new Options($address);
$options->setLogger($logger)
    ->setUsername($username)
    ->setPassword($password);
$client = new Client($options);
$client->connect();
/*$client->send(new Roster);
$client->send(new Presence);
$client->send(new Message);*/
$message = new Message;
$message->setMessage($notification)
    ->setTo($reciever);
$response = $client->send($message);
$client->disconnect();
