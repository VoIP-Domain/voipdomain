#!/usr/bin/php -q
<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2018 Ernani José Camargo Azevedo
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * This daemon auto detects VoIP devices provisioning at the local network.
 * You must execute this daemon at same server where TFTP server is running, and
 * with verbose execution enabled.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage VoIP Hardware Detect Daemon
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Set error reporting level
 */
error_reporting ( E_ERROR);
ini_set ( "display_errors", "false");
// error_reporting ( E_ALL); ini_set ( "display_errors", "true");

/**
 * Check if script is running from CLI
 */
if ( ! defined ( "STDIN"))
{
  echo "This script must be executed into CLI!\n";
  exit ( 1);
}

/**
 * Configura encoding
 */
mb_internal_encoding ( "UTF-8");

/**
 * Seta locale para Brasil
 */
setlocale ( LC_ALL, "en_US.UTF-8");

/**
 * Seta variáveis de configurações do sistema com informações estáticas
 */
$_sys = array ();
$_sys["version"] = "1.0";

/**
 * Parse do arquivo de configurações
 */
$configs = parse_ini_file ( "/etc/voipdomain/autodetect.conf", true);

/**
 * Valida sessão de MySQL
 */
if ( ! is_array ( $configs["mysql"]))
{
  echo "Erro: Não foi possível encontrar a sessão \"mysql\" no arquivo de configurações.\n";
  exit ( 1);
}

/**
 * Mescla configurações
 */
$_sys = $_sys + $configs;
unset ( $configs);

/**
 * Declara constantes para uso no sistema de registros
 */
define ( "VoIP_LOG_NOTICE", 0);
define ( "VoIP_LOG_WARNING", 1);
define ( "VoIP_LOG_ERROR", 2);
define ( "VoIP_LOG_FATAL", 3);

/**
 * Função para enviar mensagens para registros, com níveis pré-definidos em constantes.
 *
 * @param $msg String Mensagem a ser registrada.
 * @param $severity int[Optional] Nível de erro. Padrão VoIP_LOG_NOTICE, apenas registra. Se for VoIP_LOG_ERROR, finaliza o processo atual. Se for VoIP_LOG_FATAL, finaliza todos os processos do sistema.
 * @return void
 */
function writeLog ( $msg, $severity = VoIP_LOG_NOTICE)
{
  global $_sys, $_lastlog, $_lasttime;

  // Verifica se última mensagem é igual:
  if ( $_lastlog == $msg)
  {
    $_lasttime++;
    return;
  } else {
    if ( $_lasttime > 0)
    {
      // Registra número de ocorrências:
      $msgdate = date ( "M") . " " . sprintf ( "% 2s", date ( "j")) . " " . date ( "H:i:s") . " " . php_uname ( "n") . " voipdomain[" . getmypid () . "]: ";
      $message = "AVISO: Última mensagem repetiu " . $_lasttime . " vez" . ( $_lasttime == 1 ? "" : "es") . ".\n";
      file_put_contents ( $_sys["autodetect"]["logfile"], $msgdate . $message, FILE_APPEND);
      echo $message;
      $_lasttime = 0;
    }
    $_lastlog = $msg;
  }

  // Grava mensagem:
  $msgdate = date ( "M") . " " . sprintf ( "% 2s", date ( "j")) . " " . date ( "H:i:s") . " " . php_uname ( "n") . " voipdomain[" . getmypid () . "]: ";
  $message = ( $severity == VoIP_LOG_ERROR ? "ERRO: " : "") . ( $severity == VoIP_LOG_WARNING ? "AVISO: " : "") . ( $severity == VoIP_LOG_FATAL ? "FATAL: " : "") . $msg . "\n";
  file_put_contents ( $_sys["autodetect"]["logfile"], $msgdate . $message, FILE_APPEND);
  echo $message;
  if ( $severity == VoIP_LOG_FATAL || $severity == VoIP_LOG_ERROR)
  {
    exit ( 1);
  }
}

/**
 * Altera nome do processo, se possível
 */
if ( function_exists ( "setproctitle"))
{
  setproctitle ( "VoIP Domain AutoDetect daemon");
}

/**
 * Imprime cabeçalho do software
 */
echo chr ( 27) . "[1;37mVoIP Domain AutoDetect Daemon" . chr ( 27) . "[1;0m v" . $_sys["version"] . "\n";
echo "\n";

/**
 * Verifica parâmetros
 */
$debug = false;
for ( $x = 1; $x < $argc; $x++)
{
  switch ( $argv[$x])
  {
    case "--debug":
    case "-d":
      $debug = true;
      break;
    case "--help":
    case "-h":
      echo "utilização: " . basename ( $argv[0]) . " [--help|-h] [--debug|-d]\n";
      echo "  --help|-h:    Exibe esta lista de ajuda\n";
      echo "  --debug|-d:   Habilita mensagens de depuração (não executa em segundo plano)\n";
      exit ();
      break;
    default:
      echo "ERRO: Parâmetro inválido \"" . $argv[$x] . "\"!\n";
      exit ( -1);
      break;
  }
}

/**
 * Conecta ao banco de dados
 */
echo "Executando: Conectando ao banco de dados... ";
if ( ! $_sys["mysql"]["id"] = @mysql_connect ( $_sys["mysql"]["hostname"], $_sys["mysql"]["username"], $_sys["mysql"]["password"]))
{
  writeLog ( "Não foi possível conectar ao banco de dados!", $VoIP_LOG_FATAL);
}
if ( ! @mysql_select_db ( $_sys["mysql"]["database"], $_sys["mysql"]["id"]))
{
  writeLog ( "Não foi possível selecionar a base de dados!", $VoIP_LOG_FATAL);
}
echo chr ( 27) . "[1;37m" . gettext ( "OK") . chr ( 27) . "[1;0m\n";

/**
 * Popula base de equipamentos
 */
echo "Executando: Requisitando equipamentos ao banco de dados... ";
if ( ! $result = @mysql_query ( "SELECT `ID`, `Modelo`, `Nome` FROM `Equipamentos` WHERE `AP` = 'S'", $_sys["mysql"]["id"]))
{
  writeLog ( "Não foi possível requisitar os equipamentos ao banco de dados!", VoIP_LOG_FATAL);
}
$equipamentos = array ();
while ( $equipamento = mysql_fetch_assoc ( $result))
{
  $equipamentos[$equipamento["Modelo"]] = array ( "ID" => $equipamento["ID"], "Nome" => $equipamento["Nome"]);
}
echo chr ( 27) . "[1;37m" . gettext ( "OK") . chr ( 27) . "[1;0m\n";

/**
 * Abre arquivo de log e fica monitorando
 */
echo "Executando: Monitorando arquivo de log... ";
if ( ! $fp = @fopen ( "/var/log/messages", "r"))
{
  writeLog ( "Não foi possível abrir o arquivo de logs \"/var/log/messages\"!", VoIP_LOG_FATAL);
}
$position = filesize ( "/var/log/messages");
fseek ( $fp, $position);
$fd = inotify_init ();
$wd = inotify_add_watch ( $fd, "/var/log/messages", IN_MODIFY);
echo chr ( 27) . "[1;37m" . gettext ( "OK") . chr ( 27) . "[1;0m\n";

/**
 * Mostra mensagem de início de operação
 */
echo "Tudo pronto. Aguardando por mensagens!\n\n";

/**
 * Alterna processo para execução em segundo plano (exceto se em modo debug)
 */
error_reporting ( E_ERROR);
set_time_limit ( 0);
if ( ! $debug)
{
  if ( $pid = pcntl_fork ())
  {
    exit ();
  }
  if ( posix_setsid () < 0)
  {
    exit ();
  }
  if ( $pid = pcntl_fork ())
  {
    exit ();
  }
}

/**
 * Inicializa array de telefones
 */
$telefones = array ();

/**
 * Inicia loop infinito para tratamento de conexões
 */
while ( true)
{
  $events = inotify_read ( $fd);
  if ( filesize ( "/var/log/messages") < $position)
  {
    fclose ( $fp);
    usleep ( 1000);
    if ( ! $fp = @fopen ( "/var/log/messages", "r"))
    {
      writeLog ( "Não foi possível abrir o arquivo de logs \"/var/log/messages\"!", VoIP_LOG_FATAL);
    }
  }
  $position = filesize ( "/var/log/messages");
  $data = str_replace ( "\n", "", str_replace ( "\r", "", fgets ( $fp, 1024)));

  // Verifica se é log do TFTPD e se é requisição de leitura:
  if ( strpos ( $data, "in.tftpd") !== false && strpos ( $data, "RRQ from ") !== false)
  {
    // Transforma data e hora:
    $date = strtotime ( substr ( $data, 0, 15));

    // Pega IP:
    $ip = substr ( $data, strpos ( $data, " from ") + 6);
    $ip = substr ( $ip, 0, strpos ( $ip, " "));

    // Pega nome do arquivo:
    $filename = substr ( $data, strpos ( $data, " filename ") + 10);

    // Verifica o tipo de equipamento:
    switch ( $filename)
    {
      case "y000000000007.cfg":
        $modelo = "t20p";
        break;
      case "y000000000034.cfg":
        $modelo = "t21p";
        break;
      case "y000000000005.cfg":
        $modelo = "t22p";
        break;
      case "y000000000004.cfg":
        $modelo = "t26p";
        break;
      case "y000000000023.cfg":
        $modelo = "vp530";
        break;
      case "y000000000025.cfg":
        $modelo = "w52p";
        break;
      case "sip_301.cfg":
        $modelo = "spip301";
        break;
      case "sip_320.cfg":
        $modelo = "spip320";
        break;
      case "sip_321.cfg":
        $modelo = "spip321";
        break;
      case "sip_330.cfg":
        $modelo = "spip330";
        break;
      case "sip_331.cfg":
        $modelo = "spip331";
        break;
      case "sip_430.cfg":
        $modelo = "spip430";
        break;
      default:
        $modelo = "";
        break;
    }

    // Verifica se é um pedido de configuração com endereço MAC:
    if ( preg_match ( "/^[0-9a-f]{12}\.cfg$/", strtolower ( $filename)) && substr ( $filename, 0, 12) != "000000000000")
    {
      $mac = strtoupper ( substr ( $filename, 0, 12));
    } else {
      $mac = "";
    }

    // Caso tenha detectado um MAC ou Modelo, trata o valor:
    if ( ! empty ( $modelo) || ! empty ( $mac))
    {
      if ( array_key_exists ( $ip, $telefones) && $telefones[$ip]["Timestamp"] >= $timestamp - 300)
      {
        if ( ! empty ( $modelo))
        {
          $telefones[$ip]["Modelo"] = $modelo;
        }
        if ( ! empty ( $mac))
        {
          $telefones[$ip]["MAC"] = $mac;
        }
        if ( ! empty ( $telefones[$ip]["Modelo"]) && ! empty ( $telefones[$ip]["MAC"]))
        {
          if ( ! $result = @mysql_query ( "SELECT `ID` FROM `Telefones` WHERE `MAC` = '" . mysql_real_escape_string ( $telefones[$ip]["MAC"], $_sys["mysql"]["id"]) . "'", $_sys["mysql"]["id"]))
          {
            writeLog ( "Erro ao requisitar telefone na base de dados!", VoIP_LOG_FATAL);
          }
          if ( mysql_num_rows ( $result) == 0)
          {
            writeLog ( "Novo telefone detectado no IP " . $ip . ", modelo " . $equipamentos[$telefones[$ip]["Modelo"]]["Nome"] . " (ID " . $equipamentos[$telefones[$ip]["Modelo"]]["ID"] . ", código " . $telefones[$ip]["Modelo"] . "), MAC " . $telefones[$ip]["MAC"]);
            if ( ! @mysql_query ( "INSERT INTO `Telefones` (`Tipo`,`MAC`,`Template`,`NP`,`Descricao`) VALUES (" . mysql_real_escape_string ( $equipamentos[$telefones[$ip]["Modelo"]]["ID"], $_sys["mysql"]["id"]) . ", '" . mysql_real_escape_string ( $telefones[$ip]["MAC"], $_sys["mysql"]["id"]) . "', '', NULL, '" . mysql_real_escape_string ( $equipamentos[$telefones[$ip]["Modelo"]]["Nome"] . " (Auto detectado)", $_sys["mysql"]["id"]) . "')", $_sys["mysql"]["id"]))
            {
              writeLog ( "Não foi possível incluir o telefone na base de dados!");
            }
          } else {
            writeLog ( "Telefone existente detectado no IP " . $ip . ", modelo " . $equipamentos[$telefones[$ip]["Modelo"]]["Nome"] . " (ID " . $equipamentos[$telefones[$ip]["Modelo"]]["ID"] . ", código " . $telefones[$ip]["Modelo"] . "), MAC " . $telefones[$ip]["MAC"]);
          }
          unset ( $telefones[$ip]);
        }
      } else {
        $telefones[$ip] = array ( "Timestamp" => $date, "Modelo" => $modelo, "MAC" => $mac);
      }
    }
  }
}
inotify_rm_watch ( $fd, $wd);
fclose ( $fd)
?>
