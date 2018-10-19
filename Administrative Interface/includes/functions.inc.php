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
 * Generic functions to VoIP Domain framework.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Core
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Function to validate an email address.
 *
 * @param string $email Address to be validated
 * @return boolean Result
 */
function validateEmail ( $email)
{
  $isValid = true;
  $atIndex = strrpos ( $email, "@");
  if ( is_bool ( $atIndex) && ! $atIndex)
  {
    $isValid = false;
  } else {
    $domain = substr ( $email, $atIndex + 1);
    $local = substr ( $email, 0, $atIndex);
    $localLen = strlen ( $local);
    $domainLen = strlen ( $domain);
    if ( $localLen < 1 || $localLen > 64)
    {
      // local part length exceeded
      $isValid = false;
    }
    if ( $domainLen < 1 || $domainLen > 255)
    {
       // domain part length exceeded
       $isValid = false;
    }
    if ( $local[0] == "." || $local[$localLen - 1] == ".")
    {
      // local part starts or ends with "."
      $isValid = false;
    }
    if ( preg_match ( "/\\.\\./", $local))
    {
      // local part has two consecutive dots
      $isValid = false;
    }
    if ( ! preg_match ( "/^[A-Za-z0-9\\-\\.]+$/", $domain))
    {
      // character not valid in domain part
      $isValid = false;
    }
    if ( preg_match ( "/\\.\\./", $domain))
    {
      // domain part has two consecutive dots
      $isValid = false;
    }
    if ( ! preg_match ( "/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/", str_replace ( "\\\\", "", $local)))
    {
      // character not valid in local part unless
      // local part is quoted
      if ( ! preg_match ( "/^\"(\\\\\"|[^\"])+\"$/", str_replace ( "\\\\", "", $local)))
      {
        $isValid = false;
      }
    }
    /**
     * DISABLED. We can't rely that system has internet access!
     *   if ( $isValid && ! ( checkdnsrr ( $domain, "MX") || checkdnsrr ( $domain, "A")))
     *   {
     *     // domain not found in DNS
     *     $isValid = false;
     *   }
     */
  }
  return $isValid;
}

/**
 * Function to validate an E.164 string.
 *
 * @param string $number Phone number to be validated
 * @return boolean Result
 */
function validateE164 ( $number)
{
  return preg_match ( "/^\+[1-9]\d{1,14}$/", $number);
}

/**
 * Retorna a forma fonética de uma string. Esta função também aceita html
 * entities, além de permitir a configuração da página de caracter utilizada. Por
 * padrão, é utilizado UTF-8.
 *
 * @param string $texto Texto a ser fonetizado
 * @param string[optional] $encoding Página de caracter para o texto informado em $texto. Padrão UTF-8 se não informado
 * @return string Texto informado na forma fonética
 */
function fonetiza ( $texto, $encoding = "UTF-8")
{
  // Primeiramente passa-se tudo para maiúsculas:
  $texto = strtoupper ( $texto);

  // Remove as preposições dos nomes e remove espaços excedentes:
  $texto = trim ( preg_replace ( "/( |^)(DEL|DA|DE|DI|DO|DU|DAS|DOS|DEU|DER|E|LA|LE|LES|LOS|VAN|VON|EL)( |$)/i", " ", $texto));

  // Remove os acentos:
  $texto = html_entity_decode ( preg_replace ( "/&([a-zA-Z])(uml|acute|grave|circ|tilde|cedil|ring);/", "$1", htmlentities ( $texto, ENT_COMPAT, $encoding)));

  // Elimina-se quaisquer caracter que não seja de A-Z, 0-9, &, _ ou espaço:
  $texto = preg_replace ( "/[^A-Za-z0-9&_ ]/", "", $texto);

  // Elimina-se caracteres duplicados, exceto SS (mais de 2 S's transforma-se em SS) e números:
  $ultimo = " ";
  $saida = "";
  for ( $i = 0; $i < strlen ( $texto); $i++)
  {
    if ( $texto[$i] != $ultimo || $texto[$i] == " " || ( $texto[$i] >= "0" && $texto[$i] <= "9") || ( $texto[$i] == "S" && $texto[$i - 1] == "S" && $i > 1 && $texto[$i - 2] != "S"))
    {
      $saida .= $texto[$i];
    }
    $ultimo = $texto[$i];
  }

  // Separa as palavras para fonetização:
  $palavras = explode ( " ", $saida);

  // Processa cada palavra:
  foreach ( $palavras as $indexador => $palavra)
  {
    // Explode palavra em array:
    $palavra = str_split ( $palavra);
    $palavra[] = " ";

    // Reseta variáveis auxiliares:
    $saida = array ();
    $fonfon = array ();
    $fonaux = array ();
    $j = 0;

    // Se a palavra possuir apenas um caracter:
    if ( sizeof ( $palavra) == 1)
    {
      switch ( $palavra[0])
      {
        case "_":       // _ -> espaço
          $saida[0] = " ";
          break;
        case "E":       // E -> i
        case "&":       // & -> i
        case "I":       // I -> i
          $saida[0] = "i";
          break;
        default:        // Caracter não é modificado
          $saida[0] = $palavra[0];
          break;
      }
    } else {
      // Palavra possui mais de um caracter. Percorre a palavra corrente, caracter por caracter:
      for ( $i = 0; $i < sizeof ( $palavra); $i++)
      {
        switch ( $palavra[$i])
        {
          case "_":     // _ -> Y
            $fonfon[$i] = "Y";
            break;
          case "&":     // & -> i
          case "E":     // E -> i
          case "Y":     // Y -> i
          case "I":     // I -> i
            $fonfon[$i] = "i";
            break;
          case "O":     // O -> o
          case "U":     // U -> o
            $fonfon[$i] = "o";
            break;
          case "A":     // A -> a
            $fonfon[$i] = "a";
            break;
          case "S":     // S -> s
            $fonfon[$i] = "s";
            break;
          default:      // Caracter não é modificado
            $fonfon[$i] = $palavra[$i];
            break;
        }
      }
      $endfon = 0;
      $fonaux = $fonfon;

      // Palavras formadas por apenas 3 consoantes são dispensadas do processo de fonetização:
      if ( sizeof ( $fonaux) == 3)
      {
        if ( $fonaux[0] == "a" || $fonaux[0] == "i" || $fonaux[0] == "o")
        {
          $endfon = 0;
        } else {
          if ( $fonaux[1] == "a" || $fonaux[1] == "i" || $fonaux[1] == "o")
          {
            $endfon = 0;
          } else {
            if ( $fonaux[2] == "a" || $fonaux[2] == "i" || $fonaux[2] == "o")
            {
              $endfon = 0;
            } else {
              $endfon = 1;
              $saida[0] = $fonaux[0];
              $saida[1] = $fonaux[1];
              $saida[2] = $fonaux[2];
            }
          }
        }
      }

      // Se a palavra não for formada por apenas 3 consoantes:
      if ( $endfon != 1)
      {
        // Percorre a palavra corrente, caracter a caracter:
        for ( $i = 0; $i < sizeof ( $fonaux) - 1; $i++)
        {
          // Reseta variáveis de controle:
          $copfon = 0;
          $copmud = 0;
          $newmud = 0;

          switch ( $fonaux[$i])
          {
            case "a":   // Se o caracter for a
              // Se a palavra termina com As, AZ, AM ou AN, elimina a consoante do final da palavra
              if ( $fonaux[$i + 1] == "s" || $fonaux[$i + 1] == "Z" || $fonaux[$i + 1] == "M" || $fonaux[$i + 1] == "N")
              {
                if ( $fonaux[$i + 2] != " ")
                {
                  $copfon = 1;
                } else {
                  $saida[$j] = "a";
                  $saida[$j + 1] = " ";
                  $j++;
                  $i++;
                }
              } else {
                $copfon = 1;
              }
              break;

            case "B":   // Se o caracter for B, não é modificado
              $copmud = 1;
              break;

            case "C":   // Se o caracter for C
              // Ci -> si
              if ( $fonaux[$i + 1] == "i")
              {
                $saida[$j] = "s";
                $j++;
                break;
              }

              // Cois (final) -> Kao
              if ( $fonaux[$i + 1] == "o" && $fonaux[$i + 2] == "i" && $fonaux[$i + 3] == "s" && $fonaux[$i + 4] == " ")
              {
                $saida[$j] = "K";
                $saida[$j + 1] = "a";
                $saida[$j + 2] = "o";
                $i += 4;
                break;
              }

              // CT -> T
              if ( $fonaux[$i + 1] == "T")
              {
                break;
              }

              // C -> K
              if ( $fonaux[$i + 1] != "H")
              {
                $saida[$j] = "K";
                $newmud = 1;

                // CK -> K
                if ( $fonaux[$i + 1] == "K")
                {
                  $i++;
                }
                break;
              }

              // CH -> K para CHi (final), CHi (vogal), CHiNi (final) e CHiTi (final)

              // CHi (final) ou CHi (vogal)
              $x = 0;
              if ( $fonaux[$i + 1] == "H")
              {
                if ( $fonaux[$i + 2] == "i")
                {
                  if ( $fonaux[$i + 3] == "a" || $fonaux[$i + 3] == "i" || $fonaux[$i + 3] == "o")
                  {
                    $x = 1;
                  } else {
                    // CHiNi (final) ou CHiTi (final)
                    if (( $fonaux[$i + 3] == "N" || $fonaux[$i + 3] == "T") && $fonaux[$i + 4] == "i" && $fonaux[$i + 5] == " ")
                    {
                      $x = 1;
                    }
                  }
                }
              }

              if ( $x == 1)
              {
                $saida[$j] = "K";
                $j++;
                $i++;
                break;
              }

              // CHi, não CHi (final), CHi (vogal), CHiNi (final) ou CHiTi (final)
              // CH não seguido de i
              // Se anterior não é s, CH -> X

              // sCH: fonema recua uma posição
              if ( $j > 0 && $saida[$j - 1] == "s")
              {
                $j--;
              }

              $saida[$j] = "X";
              $newmud = 1;
              $i++;
              break;

            case "D":   // Se o caracter for D
              // Procura por DoR
              $x = 0;
              if ( $fonaux[$i + 1] != "o")
              {
                $copmud = 1;
                break;
              } else {
                if ( $fonaux[$i + 2] == "R")
                {
                  if ( $i != 0)
                  {
                    $x = 1;             // DoR (não inicial)
                  } else {
                    $copfon = 1;        // DoR (inicial)
                  }
                } else {
                  $copfon = 1;          // Não é DoR
                }
              }

              if ( $x == 1)
              {
                if ( $fonaux[$i + 3] == "i")
                {
                  if ( $fonaux[$i + 4] == "s")  // dores
                  {
                    if ( $fonaux[$i + 5] != " ")
                    {
                      $x = 0;           // nao e dores
                    }
                  } else {
                    $x = 0;
                  }
                } else {
                  if ( $fonaux[$i + 3] == "a")
                  {
                    if ( $fonaux[$i + 4] != " ")
                    {
                      if ( $fonaux[$i + 4] != "s")
                      {
                        $x = 0;
                      } else {
                        if ( $fonaux[$i + 5] != " ")
                        {
                          $x = 0;
                        }
                      }
                    }
                  } else {
                    $x = 0;
                  }
                }
              } else {
                $x = 0;
              }

              if ( $x == 1)
              {
                $saida[$j] = "D";
                $saida[$j + 1] = "o";
                $saida[$j + 2] = "R";
                $i += 5;
              } else {
                $copfon = 1;
              }
              break;

            case "F":   // Se o caracter for F, não é modificado
              $copmud = 1;
              break;

            case "G":   // Se o caracter for G
              // Gui -> Gi
              if ( $fonaux[$i + 1] == "o")
              {
                if ( $fonaux[$i + 2] == "i")
                {
                  $saida[$j] = "G";
                  $saida[$j + 1] = "i";
                  $j += 2;
                  $i +=2;
                } else {
                  // Diferente de Gui, copia como consoante muda
                  $copmud = 1;
                }
              } else {
                // GL
                if ( $fonaux[$i + 1] == "L")
                {
                  if ( $fonaux[$i + 2] == "i")
                  {
                    //gli + vogal -> li + vogal
                    if ( $fonaux[$i + 3] == "a" || $fonaux[$i + 3] == "i" || $fonaux[$i + 3] == "o")
                    {
                      $saida[$j] = $fonaux[$i + 1];
                      $saida[$j + 1] = $fonaux[$i + 2];
                      $j += 2;
                      $i += 2;
                    } else {
                      //glin -> lin
                      if ( $fonaux[$i + 3] == "N")
                      {
                        $saida[$j] = $fonaux[$i + 1];
                        $saida[$j + 1] = $fonaux[$i + 2];
                        $j += 2;
                        $i += 2;
                      } else {
                        $copmud = 1;
                      }
                    }
                  } else {
                    $copmud = 1;
                  }
                } else {
                  // GN + vogal -> Ni + vogal
                  if ( $fonaux[$i + 1] == "N")
                  {
                    if ( $fonaux[$i + 2] != "a" && $fonaux[$i + 2] != "i" && $fonaux[$i + 2] != "o")
                    {
                      $copmud = 1;
                    } else {
                      $saida[$j] = "N";
                      $saida[$j + 1] = "i";
                      $j += 2;
                      $i++;
                    }
                  } else {
                    // GHi -> Gi
                    if ( $fonaux[$i + 1] == "H")
                    {
                      if ( $fonaux[$i + 2] == "i")
                      {
                        $saida[$j] = "G";
                        $saida[$j + 1] = "i";
                        $j += 2;
                        $i +=2;
                      } else {
                        $copmud = 1;
                      }
                    } else {
                      $copmud = 1;
                    }
                  }
                }
              }
              break;

            case "H":   // Se o caracter for H, é desconsiderado
              break;

            case "i":   // Se o caracter for i
              if ( $fonaux[$i + 2] == " ")
              {
                // is ou iZ final perde a consoante
                if ( $fonaux[$i + 1] == "s" || $fonaux[$i + 1] == "Z")
                {
                  $saida[$j] = "i";
                  break;
                }
              }

              // iX
              if ( $fonaux[$i + 1] != "X")
              {
                $copfon = 1;
              } else {
                if ( $i != 0)
                {
                  $copfon = 1;
                } else {
                  // iX vogal no início torna-se iZ
                  if ( $fonaux[$i + 2] == "a" || $fonaux[$i + 2] == "i" || $fonaux[$i + 2] == "o")
                  {
                    $saida[$j] = "i";
                    $saida[$j + 1] = "Z";
                    $j += 2;
                    $i++;
                    break;
                  } else {
                    //ix consoante no inicio torna-se is
                    if ( $fonaux[$i + 2] == "C" || $fonaux[$i + 2] == "s")
                    {
                      $saida[$j] = "i";
                      $j++;
                      $i++;
                    } else {
                      $saida[$j] = "i";
                      $saida[$j + 1] = "s";
                      $j += 2;
                      $i++;
                    }
                    break;
                  }
                }
              }
              break;

            case "J":   // Se o caracter for J
              // J -> Gi
              $saida[$j] = "G";
              $saida[$j + 1] = "i";
              $j += 2;
              break;

            case "K":   // Se o caracter for K
              // KT -> T
              if ( $fonaux[$i + 1] != "T")
              {
                $copmud = 1;
              }
              break;

            case "L":   // Se o caracter for L
              // L + vogal não é modificado
              if ( $fonaux[$i + 1] == "a" || $fonaux[$i + 1] == "i" || $fonaux[$i + 1] == "o")
              {
                $copfon = 1;
              } else {
                // L + consoante -> U + consoante
                if ( $fonaux[$i + 1] != "H")
                {
                  $saida[$j] = "o";
                  $j++;
                  break;
                } else {
                  // LH + consoante não é modificado
                  if ( $fonaux[$i + 2] != "a" && $fonaux[$i + 2] != "i" && $fonaux[$i + 2] != "o")
                  {
                    $copfon = 1;
                  } else {
                    // LH + vogal -> LI + vogal
                    $saida[$j] = "L";
                    $saida[$j + 1] = "i";
                    $j += 2;
                    $i++;
                    break;
                  }
                }
              }
              break;

            case "M":   // Se o caracter for M
              // M + consoante -> N + consoante
              // M final -> N
              if (( $fonaux[$i + 1] != "a" && $fonaux[$i + 1] != "i" && $fonaux[$i + 1] != "o") || $fonaux[$i + 1] == " ")
              {
                $saida[$j] = "N";
                $j++;
              } else {
                // Restante não é alterado
                $copfon = 1;
              }
              break;

            case "N":   // Se o caracter for N
              // NGT -> NT
              if ( $fonaux[$i + 1] == "G" && $fonaux[$i + 2] == "T")
              {
                $fonaux[$i + 1] = "N";
                $copfon = 1;
              } else {
                // NH + consoante não é modificado
                if ( $fonaux[$i + 1] == "H")
                {
                  if ( $fonaux[$i + 2] != "a" && $fonaux[$i + 2] != "i" && $fonaux[$i + 2] != "o")
                  {
                    $copfon = 1;
                  } else {
                    // NH + vogal -> Ni + vogal
                    $saida[$j] = "N";
                    $saida[$j + 1] = "i";
                    $j += 2;
                    $i++;
                  }
                } else {
                  $copfon = 1;
                }
              }
              break;

            case "o":   // Se o caracter for o
              // os (final) -> o e oZ (final) -> o
              if (( $fonaux[$i + 1] == "s" || $fonaux[$i + 1] == "Z") && $fonaux[$i + 2] == " ")
              {
                $saida[$j] = "o";
              } else {
                $copfon = 1;
              }
              break;

            case "P":   // Se o caracter for P
              // PH -> F
              if ( $fonaux[$i + 1] == "H")
              {
                $saida[$j] = "F";
                $i++;
                $newmud = 1;
              } else {
                $copmud = 1;
              }
              break;

            case "Q":   // Se o caracter for Q
              // Koi -> Ki (QUE, QUI -> KE, KI)
              if ( $fonaux[$i + 1] == "o" && $fonaux[$i + 2] == "i")
              {
                $saida[$j] = "K";
                $j++;
                $i++;
                break;
              }

              // QoA -> KoA (QUA -> KUA)
              $saida[$j] = "K";
              $j++;
              break;

            case "R":   // Se o caracter for R, não é modificado
              $copfon = 1;
              break;

            case "s":   // Se o caracter for s
              // s final é ignorado
              if ( $fonaux[$i + 1] == " ")
              {
                break;
              }

              // s inicial + vogal não é modificado
              if ( $fonaux[$i + 1] == "a" || $fonaux[$i + 1] == "i" || $fonaux[$i + 1] == "o")
              {
                if ( $i == 0)
                {
                  $copfon = 1;
                  break;
                } else {
                  // s entre duas vogais -> z
                  if ( $fonaux[$i - 1] != "a" && $fonaux[$i - 1] != "i" && $fonaux[$i - 1] != "o")
                  {
                    $copfon = 1;
                    break;
                  } else {
                    // soL não é modificado
                    if ( $fonaux[$i + 1] == "o" && $fonaux[$i + 2] == "L" && $fonaux[$i + 3] == " ")
                    {
                      $copfon = 1;
                    } else {
                      $saida[$j] = "Z";
                      $j++;
                    }
                    break;
                  }
                }
              }

              // ss -> s
              if ( $fonaux[$i + 1] == "s")
              {
                if ( $fonaux[$i + 2] != " ")
                {
                  $copfon = 1;
                  $i++;
                } else {
                  $fonaux[$i + 1] = " ";
                }
                break;
              }

              // s inicial seguido de consoante fica precedido de i
              // se não for sCi, sH ou sCH não seguido de vogal
              if ( $i == 0)
              {
                if ( ! ( $fonaux[$i + 1] == "C" && $fonaux[$i + 2] == "i"))
                {
                  if ( $fonaux[$i + 1] != "H")
                  {
                    if ( ! ( $fonaux[$i + 1] == "C" && $fonaux[$i + 2] == "H" && ( $fonaux[$i + 3] != "a" && $fonaux[$i + 3] != "i" && $fonaux[$i + 3] != "o")))
                    {
                      $saida[$j] = "i";
                      $j++;
                      $copfon = 1;
                      break;
                    }
                  }
                }
              }

              // sH -> X;
              if ( $fonaux[$i + 1] == "H")
              {
                $saida[$j] = "X";
                $i++;
                $newmud = 1;
                break;
              }
              if ( $fonaux[$i + 1] != "C")
              {
                $copfon = 1;
                break;
              }

              // sCh não seguido de i torna-se X
              if ( $fonaux[$i + 2] == "H")
              {
                $saida[$j] = "X";
                $i += 2;
                $newmud = 1;
                break;
              }
              if ( $fonaux[$i + 2] != "i")
              {
                $copfon = 1;
                break;
              }

              // sCi final -> Xi
              if ( $fonaux[$i + 3] == " ")
              {
                $saida[$j] = "X";
                $saida[$j + 1] = "i";
                $i += 3;
                break;
              }

              // sCi final -> Xi
              if ( $fonaux[$i + 3] == " ")
              {
                $saida[$j] = "X";
                $saida[$j + 1] = "i";
                $i += 3;
                break;
              }

              // sCi vogal -> X
              if ( $fonaux[$i + 3] == "a" || $fonaux[$i + 3] == "i" || $fonaux[$i + 3] == "o")
              {
                $saida[$j] = "X";
                $j++;
                $i += 2;
                break;
              }

              // sCi consoante -> si
              $saida[$j] = "s";
              $saida[$j + 1] = "i";
              $j += 2;
              $i += 2;
              break;

            case "T":   // Se o caracter for T
              // Ts -> s, TZ -> Z
              if ( $fonaux[$i + 1] == "s" || $fonaux[$i + 1] == "Z")
              {
                break;
              }
              $copmud = 1;
              break;

            case "V":   // Se o caracter for V
            case "W":   // ou se o caracter for W
              // V,W inicial + vogal -> o + vogal (U + vogal)
              if ( $fonaux[$i + 1] == "a" || $fonaux[$i + 1] == "i"|| $fonaux[$i + 1] == "o")
              {
                if ( $i == 0)
                {
                  $saida[$j] = "o";
                  $j++;
                } else {
                  // V,W não inicial + vogal -> V + vogal
                  $saida[$j] = "V";
                  $newmud = 1;
                }
              } else {
                $saida[$j] = "V";
                $newmud = 1;
              }
              break;

            case "X":   // Se o caracter for X, não é modificado
              $copmud = 1;
              break;

            case "Y":   // Se o caracter for Y, já foi tratado acima
              break;

            case "Z":   // Se o caracter for Z
              // Z final é eliminado
              if ( $fonaux[$i + 1] == " ")
              {
                break;
              } else {
                // Z + vogal nao eh modificado
                if ( $fonaux[$i + 1] == "a" || $fonaux[$i + 1] == "i" || $fonaux[$i + 1] == "o")
                {
                  $copfon = 1;
                } else {
                  // Z + consoante -> S + consoante
                  $saida[$j] = "s";
                  $j++;
                }
              }
              break;

            default:    // Se o caracter não for um dos já relacionados, não é modificado
              $saida[$j] = $fonaux[$i];
              $j++;
              break;
          }

          // Copia caracter corrente
          if ( $copfon == 1)
          {
            $saida[$j] = $fonaux[$i];
            $j++;
          }

          // Inserção de i após consoante muda
          if ( $copmud == 1)
          {
            $saida[$j] = $fonaux[$i];
          }

          if ( $copmud == 1 || $newmud == 1)
          {
            $j++;
            $k = 0;
            while ( $k == 0)
            {
              if ( $fonaux[$i + 1] == " ")
              {
                // É final mudo
                $saida[$j] = "i";
                $k = 1;
              } else {
                if ( $fonaux[$i + 1] == "a" || $fonaux[$i + 1] == "i" || $fonaux[$i + 1] == "o")
                {
                  $k = 1;
                } else {
                  if ( $saida[$j - 1] == "X")
                  {
                    $saida[$j] = "i";
                    $j++;
                    $k = 1;
                  } else {
                    if ( $fonaux[$i + 1] == "R" || $fonaux[$i + 1] == "L")
                    {
                      $k = 1;
                    } else {
                      if ( $fonaux[$i + 1] != "H")
                      {
                        $saida[$j] = "i";
                        $j++;
                        $k = 1;
                      } else {
                        $i++;
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
    }

    // Percorre toda a palavra, letra a letra
    for ( $i = 0; $i < sizeof ( $saida); $i++)
    {
      switch ( $saida[$i])
      {
        case "i":       // i -> I
          $saida[$i] = "I";
          break;
        case "a":       // a -> A
          $saida[$i] = "A";
          break;
        case "o":       // o -> U
          $saida[$i] = "U";
          break;
        case "s":       // s -> S
          $saida[$i] = "S";
          break;
        case "E":       // E -> espaço
          $saida[$i] = " ";
          break;
        case "Y":       // Y -> _
          $saida[$i] = "_";
          break;
      }
    }

    // Retorna a palavra, modificada, ao vetor que contém o texto:
    $palavras[$indexador] = implode ( "", $saida) . " ";

    // Zera o contador:
    $j = 0;
  }

  // Junta as palavras em uma frase novamente:
  $texto = implode ( "", $palavras);

  // Elimina-se caracteres duplicados, exceto SS (mais de 2 S's transforma-se em SS) e números:
  $ultimo = " ";
  $saida = "";
  for ( $i = 0; $i < strlen ( $texto); $i++)
  {
    if ( $texto[$i] != $ultimo || $texto[$i] == " " || ( $texto[$i] >= "0" && $texto[$i] <= "9") || ( $texto[$i] == "S" && $texto[$i - 1] == "S" && $i > 1 && $texto[$i - 2] != "S"))
    {
      $saida .= $texto[$i];
    }
    $ultimo = $texto[$i];
  }

  // Retira os espaços excedentes, transforma em maiúscula e retorna:
  return trim ( strtoupper ( $saida));
}

/**
 * Return an random alphanumeric password. Default to 12 characters length
 * otherwise set another size as parameter.
 *
 * @param int[optional] $size Size of password to be generated
 * @return string Alphanumeric password
 */
function randomPassword ( $size = 12)
{
  $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
  $pass = array ();
  $alphaLength = strlen ( $alphabet) - 1;
  for ( $x = 0; $x < $size; $x++)
  {
    $n = rand ( 0, $alphaLength);
    $pass[] = $alphabet[$n];
  }
  return implode ( $pass);
}

/**
 * Retorna uma chave AES (16 bytes).
 *
 * @return string Chave 16 bytes
 */
function randomAESKey ()
{
  $alphabet = "0123456789abcdef";
  $pass = array ();
  $alphaLength = strlen ( $alphabet) - 1;
  for ( $x = 0; $x < 16; $x++)
  {
    $n = rand ( 0, $alphaLength);
    $pass[] = $alphabet[$n];
  }
  return implode ( $pass);
}

/**
 * Função para transformar data do idioma atual do sistema para padrão MySQL.
 *
 * @param string $date Data no formato atual
 * @return string String Data informada no padrão MySQL
 */
function format_form_date ( $date)
{
  // Verifica se não é uma data em branco:
  if ( empty ( $date))
  {
    return "";
  }

  // Fragmenta data:
  if ( strpos ( $date, "/") !== FALSE)
  {
    $date = preg_split ( "/\//", $date);
  } else {
    $date = preg_split ( "/-/", $date);
  }

  // Verifica separador da data:
  if ( strpos ( __ ( "m/d/Y"), "/") !== FALSE)
  {
    $sep = "/";
  } else {
    $sep = "-";
  }

  // Verifica primeiro campo:
  switch ( strtolower ( substr ( __ ( "m/d/Y"), 0, strpos ( __ ( "m/d/Y"), $sep))))
  {
    case "d":
      $day = $date[0];
      break;
    case "m":
      $month = $date[0];
      break;
    case "y":
      $year = $date[0];
      break;
  }

  // Verifica segundo campo:
  $tmp = substr ( __ ( "m/d/Y"), strpos ( __ ( "m/d/Y"), $sep) + 1);
  switch ( strtolower ( substr ( $tmp, 0, strpos ( $tmp, $sep))))
  {
    case "d":
      $day = $date[1];
      break;
    case "m":
      $month = $date[1];
      break;
    case "y":
      $year = $date[1];
      break;
  }

  // Verifica terceiro campo:
  switch ( strtolower ( substr ( __ ( "m/d/Y"), strrpos ( __ ( "m/d/Y"), $sep) + 1)))
  {
    case "d":
      $day = $date[2];
      break;
    case "m":
      $month = $date[2];
      break;
    case "y":
      $year = $date[2];
      break;
  }

  // Arruma ano em 4 dígitos:
  if ( strlen ( $year) < 4)
  {
    if ( $year < 10)
    {
      $year = "20" . $year;
    } else {
      $year = "19" . $year;
    }
  }

  // Retorna data no formato MySQL:
  return sprintf ( "%04d-%02d-%02d", $year, $month, $day);
}

/**
 * Função para transformar data e hora do idioma atual do sistema para padrão MySQL.
 *
 * @param string $date Data e hora no formato atual
 * @return string String Data informada no padrão MySQL
 */
function format_form_datetime ( $date)
{
  // Verifica se não é uma data em branco:
  if ( empty ( $date))
  {
    return "";
  }

  return substr ( $date, 6, 4) . "-" . substr ( $date, 3, 2) . "-" . substr ( $date, 0, 2) . substr ( $date, 10);

  // Separa hora:
  $hour = substr ( $date, strpos ( $date, " ") + 1);
  $date = substr ( $date, 0, strpos ( $date, " "));

  // Fragmenta data:
  if ( strpos ( $date, "/") !== FALSE)
  {
    $date = preg_split ( "/\//", $date);
  } else {
    $date = preg_split ( "/-/", $date);
  }

  // Verifica separador da data:
  if ( strpos ( __ ( "m/d/Y"), "/") !== FALSE)
  {
    $sep = "/";
  } else {
    $sep = "-";
  }

  // Verifica primeiro campo:
  switch ( strtolower ( substr ( __ ( "m/d/Y"), 0, strpos ( __ ( "m/d/Y"), $sep))))
  {
    case "d":
      $day = $date[0];
      break;
    case "m":
      $month = $date[0];
      break;
    case "y":
      $year = $date[0];
      break;
  }

  // Verifica segundo campo:
  $tmp = substr ( __ ( "m/d/Y"), strpos ( __ ( "m/d/Y"), $sep) + 1);
  switch ( strtolower ( substr ( $tmp, 0, strpos ( $tmp, $sep))))
  {
    case "d":
      $day = $date[1];
      break;
    case "m":
      $month = $date[1];
      break;
    case "y":
      $year = $date[1];
      break;
  }

  // Verifica terceiro campo:
  switch ( strtolower ( substr ( __ ( "m/d/Y"), strrpos ( __ ( "m/d/Y"), $sep) + 1)))
  {
    case "d":
      $day = $date[2];
      break;
    case "m":
      $month = $date[2];
      break;
    case "y":
      $year = $date[2];
      break;
  }

  // Arruma ano em 4 dígitos:
  if ( strlen ( $year) < 4)
  {
    if ( $year < 10)
    {
      $year = "20" . $year;
    } else {
      $year = "19" . $year;
    }
  }

  // Retorna data no formato MySQL:
  return sprintf ( "%04d-%02d-%02d %s", $year, $month, $day, $hour);
}

/**
 * Função para transformar data e hora do idioma atual do sistema para timestamp.
 *
 * @param string $date Data e hora no formato atual
 * @return int Data informada no padrão UNIX timestamp
 */
function format_form_timestamp ( $date)
{
  // Verifica se não é uma data em branco:
  if ( empty ( $date))
  {
    return "";
  }

  return substr ( $date, 6, 4) . "-" . substr ( $date, 3, 2) . "-" . substr ( $date, 0, 2) . substr ( $date, 10);

  // Separa hora:
  $hour = (int) substr ( $date, strpos ( $date, ":"));
  $minute = (int) substr ( $date, strpos ( $date, ":") + 1, 2);
  $date = substr ( $date, 0, strpos ( $date, " "));

  // Fragmenta data:
  if ( strpos ( $date, "/") !== FALSE)
  {
    $date = preg_split ( "/\//", $date);
  } else {
    $date = preg_split ( "/-/", $date);
  }

  // Verifica separador da data:
  if ( strpos ( __ ( "m/d/Y"), "/") !== FALSE)
  {
    $sep = "/";
  } else {
    $sep = "-";
  }

  // Verifica primeiro campo:
  switch ( strtolower ( substr ( __ ( "m/d/Y"), 0, strpos ( __ ( "m/d/Y"), $sep))))
  {
    case "d":
      $day = $date[0];
      break;
    case "m":
      $month = $date[0];
      break;
    case "y":
      $year = $date[0];
      break;
  }

  // Verifica segundo campo:
  $tmp = substr ( __ ( "m/d/Y"), strpos ( __ ( "m/d/Y"), $sep) + 1);
  switch ( strtolower ( substr ( $tmp, 0, strpos ( $tmp, $sep))))
  {
    case "d":
      $day = $date[1];
      break;
    case "m":
      $month = $date[1];
      break;
    case "y":
      $year = $date[1];
      break;
  }

  // Verifica terceiro campo:
  switch ( strtolower ( substr ( __ ( "m/d/Y"), strrpos ( __ ( "m/d/Y"), $sep) + 1)))
  {
    case "d":
      $day = $date[2];
      break;
    case "m":
      $month = $date[2];
      break;
    case "y":
      $year = $date[2];
      break;
  }

  // Arruma ano em 4 dígitos:
  if ( strlen ( $year) < 4)
  {
    if ( $year < 10)
    {
      $year = "20" . $year;
    } else {
      $year = "19" . $year;
    }
  }

  // Retorna data no formato MySQL:
  return mktime ( $hour, $minute, 0, $month, $day, $year);
}

/**
 * Função para retornar data do banco de dados para o formato do idioma atual.
 *
 * @param string $date Data no formato MySQL.
 * @return string Data informada no formato do idioma atual.
 */
function format_db_date ( $date)
{
  // Retorna vazio se data invalida:
  if ( $date == "0" || empty ( $date) || $date == "0000-00-00")
  {
    return "";
  }

  $year = substr ( $date, 0, 4);
  $month = substr ( $date, 5, 2);
  $day = substr ( $date, 8, 2);

  return $day . "/" . $month . "/" . $year;

  $output = "";
  for ( $x = 0; $x <= strlen ( __ ( "m/d/Y")); $x++)
  {
    switch ( substr ( __ ( "m/d/Y"), $x, 1))
    {
      case "d":
        $output .= $day;
        break;
      case "m":
        $output .= $month;
        break;
      case "y":
        $output .= $year % 100;
        break;
      case "Y":
        $output .= $year;
        break;
      default:
        $output .= substr ( __ ( "m/d/Y"), $x, 1);
        break;
    }
  }

  return $output;
}

/**
 * Função para retornar data e hora do banco de dados para o formato do idioma atual.
 *
 * @param string $datetime Data e hora no formato MySQL.
 * @return string Data e hora informada no formato do idioma atual.
 */
function format_db_datetime ( $datetime)
{
  // Retorna vazio se data invalida:
  if ( $datetime == "0" || empty ( $datetime) || $datetime == "0000-00-00 00:00:00")
  {
    return "";
  }

  $year = substr ( $datetime, 0, 4);
  $month = substr ( $datetime, 5, 2);
  $day = substr ( $datetime, 8, 2);
  $hour = substr ( $datetime, 11, 2);
  $min = substr ( $datetime, 14, 2);
  $sec = substr ( $datetime, 17, 2);

  return $day . "/" . $month . "/" . $year . " " . $hour . ":" . $min . ( ! empty ( $sec) ? ":" . $sec : "");

  $output = "";
  for ( $x = 0; $x <= strlen ( __ ( "m/d/Y")); $x++)
  {
    switch ( substr ( __ ( "m/d/Y"), $x, 1))
    {
      case "d":
        $output .= $day;
        break;
      case "m":
        $output .= $month;
        break;
      case "y":
        $output .= $year % 100;
        break;
      case "Y":
        $output .= $year;
        break;
      default:
        $output .= substr ( __ ( "m/d/Y"), $x, 1);
        break;
    }
  }

  return $output . " " . $hour . ":" . $min . ":" . $sec;
}

/**
 * Função para retornar data e hora do banco de dados para o formato UNIX timestamp.
 *
 * @param string $datetime Data e hora no formato MySQL.
 * @return int Data e hora informada no formato UNIX timestamp.
 */
function format_db_timestamp ( $datetime)
{
  // Retorna vazio se data invalida:
  if ( $datetime == "0" || empty ( $datetime) || $datetime == "0000-00-00 00:00:00")
  {
    return "";
  }

  $year = substr ( $datetime, 0, 4);
  $month = substr ( $datetime, 5, 2);
  $day = substr ( $datetime, 8, 2);
  $hour = substr ( $datetime, 11, 2);
  $min = substr ( $datetime, 14, 2);
  $sec = substr ( $datetime, 17, 2);

  return mktime ( $hour, $min, $sec, $month, $day, $year);
}

/**
 * Função para retornar string ([[DD:]HH:]MM:SS) a partir de um inteiro.
 *
 * @param int $time Tempo em segundos.
 * @return string Tempo formatado.
 */
function format_secs_to_string ( $time)
{
  // Se maior que um dia, adiciona dia:
  $result = "";
  if ( $time >= 86400)
  {
    $result = sprintf ( "%02d:", floor ( $time / 86400));
    $time = $time % 86400;
    // Adiciona hora:
    $result .= sprintf ( "%02d:", floor ( $time / 3600));
    $time = $time % 3600;
  } else {
    // Se maior que uma hora, adiciona hora:
    if ( $time >= 3600)
    {
      $result .= sprintf ( "%02d:", floor ( $time / 3600));
      $time = $time % 3600;
    }
  }

  // Adiciona minuto:
  $result .= sprintf ( "%02d:", floor ( $time / 60));
  $time = $time % 60;

  // Adiciona segundos:
  $result .= sprintf ( "%02d", $time);

  return $result;
}

/**
 * Function to return a secure random string.
 *
 * @param $size int[optional] Size of the string (default 64 bytes)
 * @param $raw_output boolean[optional] Return raw data (default false)
 * @return string
 */
function secure_rand ( $size = 64, $raw_output = false)
{
  if ( function_exists ( "openssl_random_pseudo_bytes"))
  {
    $rnd = openssl_random_pseudo_bytes ( $size, $strong);
    if ( $strong === TRUE)
    {
      return $raw_output != false ? $rnd : bin2hex ( $rnd);
    }
  }
  $sha = "";
  $rnd = "";
  if ( file_exists ( "/dev/urandom"))
  {
    if ( $fp = fopen ( "/dev/urandom", "rb"))
    {
      if ( function_exists ( "stream_set_read_buffer"))
      {
        stream_set_read_buffer ( $fp, 0);
      }
      $sha = fread ( $fp, $size);
      fclose ( $fp);
    }
  }
  for ( $i = 0; $i < $size; $i++)
  {
    $sha = hash ( "sha256", $sha . mt_rand ());
    $char = mt_rand ( 0, 62);
    $rnd .= chr ( hexdec ( $sha[$char] . $sha[$char + 1]));
  }
  return $raw_output != false ? $rnd : bin2hex ( $rnd);
}

/**
 * Função para enviar notificações (server-push) para servidores.
 *
 * @global array $_in Framework global configuration variable
 * @param $serverid int ID do servidor a ser notificado. Se 0, notifica todos servidores.
 * @param $event string Nome do evento a ser disparado no servidor.
 * @param $data array[optional] Parâmetros a serem enviados ao evento.
 * @return boolean
 */
function notify_server ( $serverid, $event, $data = array ())
{
  global $_in;

  /**
   * Get server configuration cryptography key
   */
  if ( $serverid != 0)
  {
    $where = " WHERE `ID` ";
    if ( $serverid < 0)
    {
      $where .= "!= " . $_in["mysql"]["id"]->real_escape_string ( (int) $serverid * -1);
    } else {
      $where .= "= " . $_in["mysql"]["id"]->real_escape_string ( (int) $serverid);
    }
  } else {
    $where = "";
  }
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Servers`" . $where))
  {
    return false;
  }
  if ( $result->num_rows == 0)
  {
    return false;
  }

  /**
   * Notify each server
   */
  while ( $server = $result->fetch_assoc ())
  {
    /**
     * Add event to database
     */
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Commands` (`Server`,`Event`,`Data`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( (int) $server["ID"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $event) . "', '" . $_in["mysql"]["id"]->real_escape_string ( serialize ( $data)) . "')"))
    {
      return false;
    }
    $eventid = $_in["mysql"]["id"]->insert_id;

    /**
     * Send event data
     */
    $ch = curl_init ();
    curl_setopt_array ( $ch, array (
                               CURLOPT_URL => $_in["general"]["pushpub"] . "?id=" . urlencode ( $server["ID"]),
                               CURLOPT_RETURNTRANSFER => true,
                               CURLOPT_POST => true,
                               CURLOPT_POSTFIELDS => serialize ( array ( "event" => @openssl_encrypt ( serialize ( array ( "event" => $event, "id" => $eventid, "data" => is_array ( $data) ? $data : array ( $data))), "AES-256-CBC", $server["Password"], OPENSSL_RAW_DATA)))
                      ));
    @curl_exec ( $ch);
    curl_close ( $ch);
  }

  /**
   * Return OK
   */
  return true;
}

/**
 * Function to calculate the distance between two geographic points (longitude/latitude).
 *
 * @param $lat1 float Latitude of origin point.
 * @param $lon1 float Longitude of origin point.
 * @param $lat2 float Latitude of destination point.
 * @param $lon2 float Longitude of destination point.
 * @param $unit[optional] string Return unity (default "miles"), as "M" for miles, "K" for kilometers and "N" for nautic miles.
 * @return float
 */
function distance ( $lat1, $lon1, $lat2, $lon2, $unit = "m")
{
  $miles = rad2deg ( acos ( sin ( deg2rad ( $lat1)) * sin ( deg2rad ( $lat2)) + cos ( deg2rad ( $lat1)) * cos ( deg2rad ( $lat2)) * cos ( deg2rad ( $lon1 - $lon2)))) * 60 * 1.1515;

  switch ( strtolower ( $unit))
  {
    case "k":
      return $miles * 1.609344;
      break;
    case "n":
      return $miles * 0.8684;
      break;
    default:
      return $miles;
      break;
  }
}

/**
 * Function to convert degree's geographic points to decimal format.
 *
 * @param $deg int Degrees.
 * @param $min int Minutes.
 * @param $sec int Seconds.
 * @return float
 */
function DMStoDEC ( $deg, $min, $sec)
{
  return $deg + ((( $min * 60) + ( $sec)) / 3600);
}

/**
 * Function to convert geographic coordenates from decimal format to degree's.
 *
 * @param $dec float Longitude/Latitude.
 * @return array ( $deg, $min, $sec)
 */
function DECtoDMS ( $dec)
{
  $vars = explode ( ".", $dec);
  $deg = $vars[0];
  $tempma = "0." . $vars[1];

  $tempma = $tempma * 3600;
  $min = floor ( $tempma / 60);
  $sec = $tempma - ( $min * 60);

  return array ( "deg" => $deg, "min" => $min, "sec" => $sec);
}

/**
 * Function to compare two arrays. If equals, return true, otherwise return false.
 *
 * @param $array1 array First array.
 * @param $array2 array Second array.
 * @param $strict[optional] boolean Use strict compare (compare variable type too).
 * @return boolean
 */
function array_compare ( $array1, $array2, $strict = false)
{
  if ( ! is_array ( $array1) || ! is_array ( $array2))
  {
    return false;
  }
  foreach ( $array1 as $value)
  {
    $key = array_search ( $value, $array2, $strict);
    if ( $key === false)
    {
      return false;
    }
    unset ( $array2[$key]);
  }
  return sizeof ( $array2) == 0;
}

/**
 * Função para comparar dois arrays, incluindo chaves. Se iguais, retorna true,
 * caso contrário retorna false.
 *
 * @param $array1 array Primeiro array.
 * @param $array2 array Segundo array.
 * @param $strict[optional] boolean Compara também o tipo da variável.
 * @return boolean
 */
function array_compare_with_keys ( $array1, $array2, $strict = false)
{
  if ( ! is_array ( $array1) || ! is_array ( $array2))
  {
    return false;
  }
  foreach ( $array1 as $key => $value)
  {
    $search = array_search ( $value, $array2, $strict);
    if ( $search != $key)
    {
      return false;
    }
    unset ( $array2[$key]);
  }
  return sizeof ( $array2) == 0;
}

/**
 * Função para explodir uma string em um array, respeitando a chave e o valor de cada campo. Utilizado para campos de QOS do Asterisk.
 *
 * @param $string string String a ser explodida.
 * @return array
 */
function explodeQOS ( $string)
{
  $array = explode ( ";", $string);
  $result = array ();
  foreach ( $array as $value)
  {
    $result[substr ( $value, 0, strpos ( $value, "="))] = substr ( $value, strpos ( $value, "=") + 1);
  }

  /**
   * Existe um bug no RTCP do Asterisk onde o RTT pode ser marcado com valor absurdo como 65535.999000, mas na verdade é 0. Corrigindo aqui.
   */
  if ( array_key_exists ( "rtt", $result) && $result["rtt"] >= 65535)
  {
    $result["rtt"] = "0.000000";
  }

  return $result;
}

/**
 * Function to calculate the MOS of a call.
 *
 * @param $qosa string Asterisk RTCP QOS call information (side a).
 * @param $qosb[optional] string Asterisk RTCP QOS call information (side b, optional).
 * @return float Call MOS
 */
function calculateMOS ( $qosa, $qosb = "")
{

  // Extract values from QOS:
  $qosa = explodeQOS ( $qosa);
  if ( ! empty ( $qosb))
  {
    $qosb = explodeQOS ( $qosb);
    $AverageLatency = (( $qosa["rtt"] + $qosb["rtt"]) / 2) / 2 * 1000;
    $Jitter = ( $qosa["txjitter"] + $qosb["txjitter"]) / 2;
    $PacketLoss = (( $qosa["lp"] + $qosb["lp"]) / 2 * 100) / ( $qosa["rxcount"] + $qosb["rxcount"]) / 2;
  } else {
    $AverageLatency = $qosa["rtt"] / 2 * 1000;
    $Jitter = $qosa["txjitter"];
    $PacketLoss = ( $qosa["lp"] * 100) / $qosa["rxcount"];
  }

  // MOS Formula (source: https://sillycodes.com/mean-opinion-score-or-mos-calculation-asterisk-voip-calls/):
  $EffectiveLatency = $AverageLatency + $Jitter * 2 + 10;
  if ( $EffectiveLatency < 160)
  {
    $R = 93.2 - ( $EffectiveLatency / 40);
  } else {
    $R = 93.2 - ( $EffectiveLatency - 120) / 10;
  }
  $R = $R - ( $PacketLoss * 2.5);
  return 1 + ( 0.035) * $R + ( .000007) * $R * ( $R - 60) * ( 100 - $R);
}

/**
 * Function to add system audit informations ticket.
 *
 * @global array $_in Framework global configuration variable
 * @param string $module Called module
 * @param string $function[optional] Called function
 * @param array $data[optional] Data to be stored
 * @return int ID of new database audit record
 */
function audit ( $module, $function = "", $data = array ())
{
  global $_in;

  @$_in["mysql"]["id"]->query ( "INSERT INTO `Auditory` (`Author`, `Type`, `Module`, `Function`, `Data`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( (int) ( in_array ( "user", $_in["permissions"]) ? $_in["session"]["ID"] : $_in["token"]["ID"])) . ", '" . $_in["mysql"]["id"]->real_escape_string ( ( in_array ( "user", $_in["permissions"]) ? "U" : "T")) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $module) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $function) . "', '" . $_in["mysql"]["id"]->real_escape_string ( serialize ( $data)) . "')");

  return $_in["mysql"]["id"]->insert_id;
}

/**
 * Função para converter tons da tabela interna (formato Sipura/Linksys) para formato Zaptel/Asterisk.
 *
 * @param $tones string Conjunto de parâmetros para tons.
 * @return string
 */
function tone2zaptel ( $tones)
{
  // Execução única?
  if ( substr ( $tones, 0, 1) == "!")
  {
    $onetime = true;
    $tones = substr ( $tones, 1);
  } else {
    $onetime = false;
  }

  // Processa as frequências:
  $freqs = array ();
  $levels = array ();
  foreach ( explode ( ",", substr ( $tones, 0, strpos ( $tones, ";"))) as $freq)
  {
    $freqs[] = substr ( $freq, 0, strpos ( $freq, "@"));
    $levels[] = substr ( $freq, strpos ( $freq, "@") + 1);
  }

  // Timeout:
  $timeout = substr ( $tones, strpos ( $tones, ";") + 1, strpos ( $tones, "(", strpos ( $tones, ";" + 1)) - strpos ( $tones, ";") - 1);

  // Processa as cadências:
  $cadences = array ();
  foreach ( explode ( ",", substr ( $tones, strpos ( $tones, "(") + 1, strpos ( $tones, ")") - strpos ( $tones, "(") - 1)) as $cadence)
  {
    $cadences[] = array ( "on" => ( substr ( $cadence, 0, strpos ( $cadence, "/")) == "*" ? 30000 : (float) substr ( $cadence, 0, strpos ( $cadence, "/")) * 1000), "off" => (float) substr ( $cadence, strpos ( $cadence, "/") + 1, strpos ( $cadence, "/", strpos ( $cadence, "/") + 1) - strpos ( $cadence, "/") - 1) * 1000, "tones" => substr ( $cadence, strrpos ( $cadence, "/") + 1));
  }

  // Gera resultado:
  $buffer = "";
  foreach ( $cadences as $cid => $cadence)
  {
    if ( $cid > 0)
    {
      $buffer .= ",";
    }
    foreach ( explode ( "+", $cadence["tones"]) as $tid => $tone)
    {
      if ( $tid > 0)
      {
        $buffer .= "+";
      }
      $buffer .= $freqs[$tone - 1];
    }
    $buffer .= "/" . $cadence["on"];
    if ( $cadence["off"] != 0)
    {
      $buffer .= ",0/" . $cadence["off"];
    }
  }
  return $buffer;
}

/**
 * Função para converter tons da tabela interna (formato Sipura/Linksys) para formato Polycom.
 *
 * @param $tones string Conjunto de parâmetros para tons.
 * @return string
 */
function tone2polycom ( $tones)
{
  // Execução única?
  if ( substr ( $tones, 0, 1) == "!")
  {
    $onetime = true;
    $tones = substr ( $tones, 1);
  } else {
    $onetime = false;
  }

  // Processa as frequências:
  $freqs = array ();
  $levels = array ();
  foreach ( explode ( ",", substr ( $tones, 0, strpos ( $tones, ";"))) as $freq)
  {
    $freqs[] = substr ( $freq, 0, strpos ( $freq, "@"));
    $levels[] = substr ( $freq, strpos ( $freq, "@") + 1);
  }

  // Timeout:
  $timeout = substr ( $tones, strpos ( $tones, ";") + 1, strpos ( $tones, "(", strpos ( $tones, ";" + 1)) - strpos ( $tones, ";") - 1);

  // Processa as cadências:
  $cadences = array ();
  foreach ( explode ( ",", substr ( $tones, strpos ( $tones, "(") + 1, strpos ( $tones, ")") - strpos ( $tones, "(") - 1)) as $cadence)
  {
    $cadences[] = array ( "on" => ( substr ( $cadence, 0, strpos ( $cadence, "/")) == "*" ? 30000 : (float) substr ( $cadence, 0, strpos ( $cadence, "/")) * 1000), "off" => (float) substr ( $cadence, strpos ( $cadence, "/") + 1, strpos ( $cadence, "/", strpos ( $cadence, "/") + 1) - strpos ( $cadence, "/") - 1) * 1000, "tones" => substr ( $cadence, strrpos ( $cadence, "/") + 1));
  }

  // Gera resultado:
  $result = array ();
  $result["freqs"] = array ();
  $result["levels"] = array ();
  $result["on"] = array ();
  $result["off"] = array ();
  $result["repeat"] = array ();
  foreach ( $cadences as $cid => $cadence)
  {
    foreach ( explode ( "+", $cadence["tones"]) as $tid => $tone)
    {
      $result["freqs"][$tid] = $freqs[$tone - 1];
      $result["levels"][$tid] = $freqs[$tone - 1];
    }
    $buffer["on"][] = $cadence["on"];
    $buffer["off"][] = $cadence["on"];
    $buffer["repeat"][] = ( $onetime == true ? 1 : 0);
  }
  return $buffer;
}

/**
 * Function to check if a given string is a JSON encoded string. Regular
 * expression got from https://regex101.com/library/tA9pM8
 *
 * @param string $json String to test for
 * @return boolean True if is a JSON encoded string, otherwise, false
 */
function isJSON ( $json)
{
  json_decode ( $json);
  return ( json_last_error () == JSON_ERROR_NONE) && ( substr ( $json, 0, 1) == "{");
}

/**
 * Function to check if a given string is a XML encoded string.
 *
 * @param string $xml String to test for
 * @return boolean True if is a XML encoded string, otherwise, false
 */
function isXML ( $xml)
{
  if ( @simplexml_load_string ( $xml))
  {
    return true;
  } else {
    return false;
  }
}

/**
 * Function to convert an array into a XML document.
 * Originally from https://stackoverflow.com/questions/9152176/convert-an-array-to-xml-or-json
 *
 * @param $array array The content to be converted.
 * @param $node_name[optional] string The root node name. Default "root".
 * @return string XML document
 */
function array2xml ( $array, $node_name = "root")
{
  $dom = new DOMDocument ( "1.0", "UTF-8");
  $dom->formatOutput = true;
  $root = $dom->createElement ( $node_name);
  $dom->appendChild ( $root);

  $array2xml = function ( $node, $array) use ( $dom, &$array2xml)
  {
    foreach ( $array as $key => $value)
    {
      if ( is_array ( $value))
      {
        $n = $dom->createElement ( $key);
        $node->appendChild ( $n);
        $array2xml ( $n, $value);
      } else {
        $attr = $dom->createAttribute ( $key);
        if ( is_bool ( $value))
        {
          $attr->value = ( $value ? "true" : "false");
        } else {
          $attr->value = $value;
        }
        $node->appendChild ( $attr);
      }
    }
  };

  $array2xml ( $root, $array);

  return $dom->saveXML ();
}

/**
 * Function to check if an IP address is on a CIDR.
 *
 * $param $ip string IP address
 * $param $cidr string The network CID
 * @return boolean
 */
function cidrMatch ( $ip, $cidr)
{
  list ( $subnet, $bits) = split ( "/", $cidr);
  $ip = ip2long ( $ip);
  $subnet = ip2long ( $subnet);
  $mask = -1 << (32 - $bits);
  $subnet &= $mask;
  return ( $ip & $mask) == $subnet;
}

/**
 * Function to dump hex string to text (using . as non-printable characters).
 *
 * $param $hex string Hex representation string
 * @return string Text view of Hex string
 */
function hex2str ( $hex)
{
  $string = "";

  for ( $i = 0; $i < strlen ( $hex) - 1; $i += 2)
  {
    $d = hexdec ( $hex[$i] . $hex[$i + 1]);

    // Show only if character is printable
    if( ( $d >= 48 && $d <= 57) || ( $d >= 65 && $d <= 90) || ( $d >= 97 && $d <= 122))
    {
      $string .= chr ( hexdec ( $hex[$i] . $hex[$i + 1]));
    } else {
      $string .= ".";
    }
  }

  return $string;
}

/**
 * For PHP versions <= 5.5.0, we doesn't have hash_pbkdf2 function, so, implement it if needed.
 */
if ( ! function_exists ( "hash_pbkdf2"))
{
  /**
   * Function to generate PBKDF2 hash.
   *
   * @param $algo String Cryptografic hash algorith to be used
   * @param $password String The password to be hashed
   * @param $salt String Salt to be used
   * @param $count int Number of iterations
   * @param $length int Length of the desired hash (in bytes)
   * @param $raw_output boolean[optional] Raw output option (default false)
   * @return string Resulting hash
   */
  function hash_pbkdf2 ( $algo, $password, $salt, $count, $length = 0, $raw_output = false)
  {
    if ( ! in_array ( strtolower ( $algo), hash_algos ()))
    {
      trigger_error ( __FUNCTION__ . "(): Unknown hashing algorithm: " . $algo, E_USER_WARNING);
    }
    if ( ! is_numeric ( $count))
    {
      trigger_error ( __FUNCTION__ . "(): expects parameter 4 to be long, " . gettype ( $count) . " given", E_USER_WARNING);
    }
    if ( ! is_numeric ( $length))
    {
      trigger_error ( __FUNCTION__ . "(): expects parameter 5 to be long, " . gettype ( $length) . " given", E_USER_WARNING);
    }
    if ( $count <= 0)
    {
      trigger_error ( __FUNCTION__ . "(): Iterations must be a positive integer: " . $count, E_USER_WARNING);
    }
    if ( $length < 0)
    {
      trigger_error ( __FUNCTION__ . "(): Length must be greater than or equal to 0: " . $length, E_USER_WARNING);
    }

    $output = "";
    $block_count = $length ? ceil ( $length / strlen ( hash ( $algo, "", $raw_output))) : 1;
    for ( $i = 1; $i <= $block_count; $i++)
    {
      $last = $xorsum = hash_hmac ( $algo, $salt . pack ( "N", $i), $password, true);
      for ( $j = 1; $j < $count; $j++)
      {
        $xorsum ^= ( $last = hash_hmac ( $algo, $last, $password, true));
      }
      $output .= $xorsum;
    }

    if ( ! $raw_output)
    {
      $output = bin2hex ( $output);
    }
    return $length ? substr ( $output, 0, $length) : $output;
  }
}

/**
 * Function to return a system translation to current user language.
 *
 * @global array $_in Framework global configuration variable
 * @param string $text Text to be translated
 * @param boolean $strip[optional] Strip HTML tags (default true)
 * @param boolean $slashes[optional] Add slashes before " or ' (default true)
 * @return string Translated text (if not found, the text itself)
 */
function __ ( $text, $strip = true, $slashes = true)
{
  global $_in;

  if ( array_key_exists ( $_in["general"]["language"] . ( $_in["module"] != "" ? "-" . $_in["module"] : ""), $_in["i18n"][$text]))
  {
    $return = $_in["i18n"][$text][$_in["general"]["language"] . ( $_in["module"] != "" ? "-" . $_in["module"] : "")];
  } else {
    if ( array_key_exists ( $_in["general"]["language"], $_in["i18n"][$text]))
    {
      $return = $_in["i18n"][$text][$_in["general"]["language"]];
    } else {
      $return = $text;
    }
  }
  if ( $strip)
  {
    $return = strip_tags ( $return);
  }
  if ( $slashes)
  {
    $return = addslashes ( $return);
  }
  return $return;
}

/**
 * Function to echo a system translation to current user language.
 *
 * @param string $text Text to be translated
 * @param boolean $strip[optional] Strip HTML tags (default true)
 * @param boolean $slashes[optional] Add slashes before " or ' (default true)
 * @return void
 */
function _e ( $text, $strip = true, $slashes = true)
{
  echo __ ( $text, $strip, $slashes);
}

/**
 * Function to return a system translation to current user language with support
 * based on supplied number (singular or plural).
 *
 * @global array $_in Framework global configuration variable
 * @param string $singular Singular text to be translated
 * @param string $plural Plural text to be translated
 * @param int $count Count value
 * @param boolean $strip[optional] Strip HTML tags (default true)
 * @param boolean $slashes[optional] Add slashes before " or ' (default true)
 * @return string Translated text (if not found, the text itself)
 */
function _n ( $singular, $plural, $count, $strip = true, $slashes = true)
{
  global $_in;

  if ( $count == 1)
  {
    if ( array_key_exists ( $_in["general"]["language"] . ( $_in["module"] != "" ? "-" . $_in["module"] : ""), $_in["i18n"][$singular]))
    {
      $return = $_in["i18n"][$singular][$_in["general"]["language"] . ( $_in["module"] != "" ? "-" . $_in["module"] : "")];
    } else {
      if ( array_key_exists ( $_in["general"]["language"], $_in["i18n"][$singular]))
      {
        $return = $_in["i18n"][$singular][$_in["general"]["language"]];
      } else {
        $return = $singular;
      }
    }
  } else {
    if ( array_key_exists ( $_in["general"]["language"] . ( $_in["module"] != "" ? "-" . $_in["module"] : ""), $_in["i18n"][$plural]))
    {
      $return = $_in["i18n"][$plural][$_in["general"]["language"] . ( $_in["module"] != "" ? "-" . $_in["module"] : "")];
    } else {
      if ( array_key_exists ( $_in["general"]["language"], $_in["i18n"][$plural]))
      {
        $return = $_in["i18n"][$plural][$_in["general"]["language"]];
      } else {
        $return = $plural;
      }
    }
  }
  if ( $strip)
  {
    $return = strip_tags ( $return);
  }
  if ( $slashes)
  {
    $return = addslashes ( $return);
  }
  return $return;
}

/**
 * Function to echo a system translation to current user language with support
 * based on supplied number (singular or plural).
 *
 * @param string $singular Singular text to be translated
 * @param string $plural Plural text to be translated
 * @param int $count Count value
 * @param boolean $strip[optional] Strip HTML tags (default true)
 * @param boolean $slashes[optional] Add slashes before " or ' (default true)
 * @return void
 */
function _ne ( $singular, $plural, $count, $strip = true, $slashes = true)
{
  echo _n ( $singular, $plural, $count, $strip, $slashes);
}

/**
 * Function to add a new translation string to the system. This function will
 * create the necessary internationalization system array to the desired string.
 *
 * @global array $_in Framework global configuration variable
 * @param string $text Text to be added (in en_US)
 * @param string[optional] $translation The translation text for $language
 * @param string[optional] $language The language of translated text
 * @return void
 */
function i18n_add ( $text, $translation = "", $language = "")
{
  global $_in;

  if ( ! array_key_exists ( $text, $_in["i18n"]))
  {
    $_in["i18n"][$text] = array ();
  }
  if ( $translation != "" && $language != "")
  {
    $_in["i18n"][$text][$language . ( $_in["module"] != "" ? "-" . $_in["module"] : "")] = $translation;
  }
}
?>
