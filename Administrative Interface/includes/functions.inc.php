<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2026 Ernani José Camargo Azevedo
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
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Function to validate an email address.
 *
 * @param string $email Address to be validated
 * @return boolean Result
 */
function validate_email ( $email)
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
function validate_E164 ( $number)
{
  return preg_match ( "/^\+\d{1,15}$/", $number);
}

/**
 * Return the phonetic form of a string. This function also accepts HTML
 * entities, and you can also provide a character encoding. The functions uses
 * UTF-8 as default character set.
 *
 * @param string $text Text to be phonetized
 * @param string[optional] $encoding Character set to the provided $text. Default
 *                                   to UTF-8 if empty
 * @return string Provided text into phonetic version
 */
function phonetize ( $text, $encoding = "UTF-8")
{
  // First step, turn everything upcase:
  $text = strtoupper ( $text);

  // Remove name prepositions and excessive spaces:
  $text = trim ( preg_replace ( "/( |^)(DEL|DA|DE|DI|DO|DU|DAS|DOS|DEU|DER|E|LA|LE|LES|LOS|VAN|VON|EL)( |$)/i", " ", $text));

  // Remove accentuation:
  $text = html_entity_decode ( preg_replace ( "/&([a-zA-Z])(uml|acute|grave|circ|tilde|cedil|ring);/", "$1", htmlentities ( $text, ENT_COMPAT, $encoding)));

  // Remove any character except A-Z, 0-9, &, _ or space:
  $text = preg_replace ( "/[^A-Za-z0-9&_ ]/", "", $text);

  // Remove duplicated characters, except SS (more than 2 S's convert to SS) and numbers:
  $last = " ";
  $output = "";
  for ( $i = 0; $i < strlen ( $text); $i++)
  {
    if ( $text[$i] != $last || $text[$i] == " " || ( $text[$i] >= "0" && $text[$i] <= "9") || ( $text[$i] == "S" && $text[$i - 1] == "S" && $i > 1 && $text[$i - 2] != "S"))
    {
      $output .= $text[$i];
    }
    $last = $text[$i];
  }

  // Split words to phonetize:
  $words = explode ( " ", $output);

  // Process each word:
  foreach ( $words as $index => $word)
  {
    // Explode word into array:
    $word = str_split ( $word);
    $word[] = " ";

    // Reset auxiliar variables:
    $output = array ();
    $fonfon = array ();
    $fonaux = array ();
    $j = 0;

    // If word has only one character:
    if ( sizeof ( $word) == 1)
    {
      switch ( $word[0])
      {
        case "_":       // _ -> space
          $output[0] = " ";
          break;
        case "E":       // E -> i
        case "&":       // & -> i
        case "I":       // I -> i
          $output[0] = "i";
          break;
        default:        // Character was not modified
          $output[0] = $word[0];
          break;
      }
    } else {
      // Word has more than one character. Loop throught current word, character by character:
      for ( $i = 0; $i < sizeof ( $word); $i++)
      {
        switch ( $word[$i])
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
          default:      // Caracter was not modified
            $fonfon[$i] = $word[$i];
            break;
        }
      }
      $endfon = 0;
      $fonaux = $fonfon;

      // Words formed by only 3 consonants are exempt from the phonetization process:
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
              $output[0] = $fonaux[0];
              $output[1] = $fonaux[1];
              $output[2] = $fonaux[2];
            }
          }
        }
      }

      // If the word is not made up of only 3 consonants:
      if ( $endfon != 1)
      {
        // Loop throught current word, character by character:
        for ( $i = 0; $i < sizeof ( $fonaux) - 1; $i++)
        {
          // Reset control variables:
          $copfon = 0;
          $copmud = 0;
          $newmud = 0;

          switch ( $fonaux[$i])
          {
            case "a":   // If character is a
              // If the word ends with As, AZ, AM or AN, it eliminates the consonant of the end of the word:
              if ( $fonaux[$i + 1] == "s" || $fonaux[$i + 1] == "Z" || $fonaux[$i + 1] == "M" || $fonaux[$i + 1] == "N")
              {
                if ( $fonaux[$i + 2] != " ")
                {
                  $copfon = 1;
                } else {
                  $output[$j] = "a";
                  $output[$j + 1] = " ";
                  $j++;
                  $i++;
                }
              } else {
                $copfon = 1;
              }
              break;

            case "B":   // If character is B, it's not modified
              $copmud = 1;
              break;

            case "C":   // If character is C
              // Ci -> si
              if ( $fonaux[$i + 1] == "i")
              {
                $output[$j] = "s";
                $j++;
                break;
              }

              // Cois (final) -> Kao
              if ( $fonaux[$i + 1] == "o" && $fonaux[$i + 2] == "i" && $fonaux[$i + 3] == "s" && $fonaux[$i + 4] == " ")
              {
                $output[$j] = "K";
                $output[$j + 1] = "a";
                $output[$j + 2] = "o";
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
                $output[$j] = "K";
                $newmud = 1;

                // CK -> K
                if ( $fonaux[$i + 1] == "K")
                {
                  $i++;
                }
                break;
              }

              // CH -> K to CHi (final), CHi (vowel), CHiNi (final) and CHiTi (final)

              // CHi (final) or CHi (vowel)
              $x = 0;
              if ( $fonaux[$i + 1] == "H")
              {
                if ( $fonaux[$i + 2] == "i")
                {
                  if ( $fonaux[$i + 3] == "a" || $fonaux[$i + 3] == "i" || $fonaux[$i + 3] == "o")
                  {
                    $x = 1;
                  } else {
                    // CHiNi (final) or CHiTi (final)
                    if (( $fonaux[$i + 3] == "N" || $fonaux[$i + 3] == "T") && $fonaux[$i + 4] == "i" && $fonaux[$i + 5] == " ")
                    {
                      $x = 1;
                    }
                  }
                }
              }

              if ( $x == 1)
              {
                $output[$j] = "K";
                $j++;
                $i++;
                break;
              }

              // CHi, not CHi (final), CHi (vowel), CHiNi (final) or CHiTi (final)
              // CH not followed by i
              // If previous was not s, CH -> X

              // sCH: phoneme moves back one position
              if ( $j > 0 && $output[$j - 1] == "s")
              {
                $j--;
              }

              $output[$j] = "X";
              $newmud = 1;
              $i++;
              break;

            case "D":   // If the character is D
              // Search for DoR
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
                    $x = 1;             // DoR (not initial)
                  } else {
                    $copfon = 1;        // DoR (initial)
                  }
                } else {
                  $copfon = 1;          // It's not DoR
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
                      $x = 0;           // It's not dores
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
                $output[$j] = "D";
                $output[$j + 1] = "o";
                $output[$j + 2] = "R";
                $i += 5;
              } else {
                $copfon = 1;
              }
              break;

            case "F":   // If character is F, it's not modified
              $copmud = 1;
              break;

            case "G":   // If character is G
              // Gui -> Gi
              if ( $fonaux[$i + 1] == "o")
              {
                if ( $fonaux[$i + 2] == "i")
                {
                  $output[$j] = "G";
                  $output[$j + 1] = "i";
                  $j += 2;
                  $i +=2;
                } else {
                  // Unlike Gui, copy as mute consonant
                  $copmud = 1;
                }
              } else {
                // GL
                if ( $fonaux[$i + 1] == "L")
                {
                  if ( $fonaux[$i + 2] == "i")
                  {
                    //gli + vowel -> li + vowel
                    if ( $fonaux[$i + 3] == "a" || $fonaux[$i + 3] == "i" || $fonaux[$i + 3] == "o")
                    {
                      $output[$j] = $fonaux[$i + 1];
                      $output[$j + 1] = $fonaux[$i + 2];
                      $j += 2;
                      $i += 2;
                    } else {
                      //glin -> lin
                      if ( $fonaux[$i + 3] == "N")
                      {
                        $output[$j] = $fonaux[$i + 1];
                        $output[$j + 1] = $fonaux[$i + 2];
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
                  // GN + vowel -> Ni + vowel
                  if ( $fonaux[$i + 1] == "N")
                  {
                    if ( $fonaux[$i + 2] != "a" && $fonaux[$i + 2] != "i" && $fonaux[$i + 2] != "o")
                    {
                      $copmud = 1;
                    } else {
                      $output[$j] = "N";
                      $output[$j + 1] = "i";
                      $j += 2;
                      $i++;
                    }
                  } else {
                    // GHi -> Gi
                    if ( $fonaux[$i + 1] == "H")
                    {
                      if ( $fonaux[$i + 2] == "i")
                      {
                        $output[$j] = "G";
                        $output[$j + 1] = "i";
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

            case "H":   // If character is H, it's discarted
              break;

            case "i":   // If character is i
              if ( $fonaux[$i + 2] == " ")
              {
                // is or iZ final loses it consonant
                if ( $fonaux[$i + 1] == "s" || $fonaux[$i + 1] == "Z")
                {
                  $output[$j] = "i";
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
                  // iX vowel at start turns into iZ
                  if ( $fonaux[$i + 2] == "a" || $fonaux[$i + 2] == "i" || $fonaux[$i + 2] == "o")
                  {
                    $output[$j] = "i";
                    $output[$j + 1] = "Z";
                    $j += 2;
                    $i++;
                    break;
                  } else {
                    // ix consonant at start turns into is
                    if ( $fonaux[$i + 2] == "C" || $fonaux[$i + 2] == "s")
                    {
                      $output[$j] = "i";
                      $j++;
                      $i++;
                    } else {
                      $output[$j] = "i";
                      $output[$j + 1] = "s";
                      $j += 2;
                      $i++;
                    }
                    break;
                  }
                }
              }
              break;

            case "J":   // If character is J
              // J -> Gi
              $output[$j] = "G";
              $output[$j + 1] = "i";
              $j += 2;
              break;

            case "K":   // If character is K
              // KT -> T
              if ( $fonaux[$i + 1] != "T")
              {
                $copmud = 1;
              }
              break;

            case "L":   // If character is L
              // L + vowel it's not modified
              if ( $fonaux[$i + 1] == "a" || $fonaux[$i + 1] == "i" || $fonaux[$i + 1] == "o")
              {
                $copfon = 1;
              } else {
                // L + consonant -> U + consonant
                if ( $fonaux[$i + 1] != "H")
                {
                  $output[$j] = "o";
                  $j++;
                  break;
                } else {
                  // LH + consonante it's not modified
                  if ( $fonaux[$i + 2] != "a" && $fonaux[$i + 2] != "i" && $fonaux[$i + 2] != "o")
                  {
                    $copfon = 1;
                  } else {
                    // LH + vowel -> LI + vowel
                    $output[$j] = "L";
                    $output[$j + 1] = "i";
                    $j += 2;
                    $i++;
                    break;
                  }
                }
              }
              break;

            case "M":   // If character is M
              // M + consonant -> N + consonant
              // M final -> N
              if (( $fonaux[$i + 1] != "a" && $fonaux[$i + 1] != "i" && $fonaux[$i + 1] != "o") || $fonaux[$i + 1] == " ")
              {
                $output[$j] = "N";
                $j++;
              } else {
                // Everything else is not modified
                $copfon = 1;
              }
              break;

            case "N":   // If character is N
              // NGT -> NT
              if ( $fonaux[$i + 1] == "G" && $fonaux[$i + 2] == "T")
              {
                $fonaux[$i + 1] = "N";
                $copfon = 1;
              } else {
                // NH + consonant is not modified
                if ( $fonaux[$i + 1] == "H")
                {
                  if ( $fonaux[$i + 2] != "a" && $fonaux[$i + 2] != "i" && $fonaux[$i + 2] != "o")
                  {
                    $copfon = 1;
                  } else {
                    // NH + vowel -> Ni + vowel
                    $output[$j] = "N";
                    $output[$j + 1] = "i";
                    $j += 2;
                    $i++;
                  }
                } else {
                  $copfon = 1;
                }
              }
              break;

            case "o":   // If character is o
              // os (final) -> o and oZ (final) -> o
              if (( $fonaux[$i + 1] == "s" || $fonaux[$i + 1] == "Z") && $fonaux[$i + 2] == " ")
              {
                $output[$j] = "o";
              } else {
                $copfon = 1;
              }
              break;

            case "P":   // If character is P
              // PH -> F
              if ( $fonaux[$i + 1] == "H")
              {
                $output[$j] = "F";
                $i++;
                $newmud = 1;
              } else {
                $copmud = 1;
              }
              break;

            case "Q":   // If character is Q
              // Koi -> Ki (QUE, QUI -> KE, KI)
              if ( $fonaux[$i + 1] == "o" && $fonaux[$i + 2] == "i")
              {
                $output[$j] = "K";
                $j++;
                $i++;
                break;
              }

              // QoA -> KoA (QUA -> KUA)
              $output[$j] = "K";
              $j++;
              break;

            case "R":   // If character is R, it's not modified
              $copfon = 1;
              break;

            case "s":   // If character is s
              // s final is ignored
              if ( $fonaux[$i + 1] == " ")
              {
                break;
              }

              // s initial + vowel it's not modified
              if ( $fonaux[$i + 1] == "a" || $fonaux[$i + 1] == "i" || $fonaux[$i + 1] == "o")
              {
                if ( $i == 0)
                {
                  $copfon = 1;
                  break;
                } else {
                  // s between two vowels -> z
                  if ( $fonaux[$i - 1] != "a" && $fonaux[$i - 1] != "i" && $fonaux[$i - 1] != "o")
                  {
                    $copfon = 1;
                    break;
                  } else {
                    // soL it's not modified
                    if ( $fonaux[$i + 1] == "o" && $fonaux[$i + 2] == "L" && $fonaux[$i + 3] == " ")
                    {
                      $copfon = 1;
                    } else {
                      $output[$j] = "Z";
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

              // s initial followed by consonant is preceeded by i
              // If it's not sCi, sH or sCH not followed by vowel
              if ( $i == 0)
              {
                if ( ! ( $fonaux[$i + 1] == "C" && $fonaux[$i + 2] == "i"))
                {
                  if ( $fonaux[$i + 1] != "H")
                  {
                    if ( ! ( $fonaux[$i + 1] == "C" && $fonaux[$i + 2] == "H" && ( $fonaux[$i + 3] != "a" && $fonaux[$i + 3] != "i" && $fonaux[$i + 3] != "o")))
                    {
                      $output[$j] = "i";
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
                $output[$j] = "X";
                $i++;
                $newmud = 1;
                break;
              }
              if ( $fonaux[$i + 1] != "C")
              {
                $copfon = 1;
                break;
              }

              // sCh not followed by i turns into X
              if ( $fonaux[$i + 2] == "H")
              {
                $output[$j] = "X";
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
                $output[$j] = "X";
                $output[$j + 1] = "i";
                $i += 3;
                break;
              }

              // sCi final -> Xi
              if ( $fonaux[$i + 3] == " ")
              {
                $output[$j] = "X";
                $output[$j + 1] = "i";
                $i += 3;
                break;
              }

              // sCi vowel -> X
              if ( $fonaux[$i + 3] == "a" || $fonaux[$i + 3] == "i" || $fonaux[$i + 3] == "o")
              {
                $output[$j] = "X";
                $j++;
                $i += 2;
                break;
              }

              // sCi consonant -> si
              $output[$j] = "s";
              $output[$j + 1] = "i";
              $j += 2;
              $i += 2;
              break;

            case "T":   // If character is T
              // Ts -> s, TZ -> Z
              if ( $fonaux[$i + 1] == "s" || $fonaux[$i + 1] == "Z")
              {
                break;
              }
              $copmud = 1;
              break;

            case "V":   // If character is V
            case "W":   // or if character is W
              // V,W initial + vowel -> o + vowel (U + vowel)
              if ( $fonaux[$i + 1] == "a" || $fonaux[$i + 1] == "i"|| $fonaux[$i + 1] == "o")
              {
                if ( $i == 0)
                {
                  $output[$j] = "o";
                  $j++;
                } else {
                  // V,W not initial + vowel -> V + vowel
                  $output[$j] = "V";
                  $newmud = 1;
                }
              } else {
                $output[$j] = "V";
                $newmud = 1;
              }
              break;

            case "X":   // If character is X, it's not modified
              $copmud = 1;
              break;

            case "Y":   // If character is Y, was already processed above
              break;

            case "Z":   // If character is Z
              // Z final is eliminated
              if ( $fonaux[$i + 1] == " ")
              {
                break;
              } else {
                // Z + vowel it's not modified
                if ( $fonaux[$i + 1] == "a" || $fonaux[$i + 1] == "i" || $fonaux[$i + 1] == "o")
                {
                  $copfon = 1;
                } else {
                  // Z + consonant -> S + consonant
                  $output[$j] = "s";
                  $j++;
                }
              }
              break;

            default:    // If character it's not any of above, it's not modified
              $output[$j] = $fonaux[$i];
              $j++;
              break;
          }

          // Copy current character
          if ( $copfon == 1)
          {
            $output[$j] = $fonaux[$i];
            $j++;
          }

          // Insert i after mute consonant
          if ( $copmud == 1)
          {
            $output[$j] = $fonaux[$i];
          }

          if ( $copmud == 1 || $newmud == 1)
          {
            $j++;
            $k = 0;
            while ( $k == 0)
            {
              if ( $fonaux[$i + 1] == " ")
              {
                // It's mute end
                $output[$j] = "i";
                $k = 1;
              } else {
                if ( $fonaux[$i + 1] == "a" || $fonaux[$i + 1] == "i" || $fonaux[$i + 1] == "o")
                {
                  $k = 1;
                } else {
                  if ( $output[$j - 1] == "X")
                  {
                    $output[$j] = "i";
                    $j++;
                    $k = 1;
                  } else {
                    if ( $fonaux[$i + 1] == "R" || $fonaux[$i + 1] == "L")
                    {
                      $k = 1;
                    } else {
                      if ( $fonaux[$i + 1] != "H")
                      {
                        $output[$j] = "i";
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

    // Loop into the word, letter by letter
    for ( $i = 0; $i < sizeof ( $output); $i++)
    {
      switch ( $output[$i])
      {
        case "i":       // i -> I
          $output[$i] = "I";
          break;
        case "a":       // a -> A
          $output[$i] = "A";
          break;
        case "o":       // o -> U
          $output[$i] = "U";
          break;
        case "s":       // s -> S
          $output[$i] = "S";
          break;
        case "E":       // E -> space
          $output[$i] = " ";
          break;
        case "Y":       // Y -> _
          $output[$i] = "_";
          break;
      }
    }

    // Return the word, modified, to the text vector:
    $words[$index] = implode ( "", $output) . " ";

    // Zero the counter:
    $j = 0;
  }

  // Join words into a phrase again:
  $text = implode ( "", $words);

  // Remove duplicated characters, except for SS (more than 2 S's turn into SS) and numbers:
  $last = " ";
  $output = "";
  for ( $i = 0; $i < strlen ( $text); $i++)
  {
    if ( $text[$i] != $last || $text[$i] == " " || ( $text[$i] >= "0" && $text[$i] <= "9") || ( $text[$i] == "S" && $text[$i - 1] == "S" && $i > 1 && $text[$i - 2] != "S"))
    {
      $output .= $text[$i];
    }
    $last = $text[$i];
  }

  // Remove extra spaces, convert to upcase and return:
  return trim ( strtoupper ( $output));
}

/**
 * Return an random alphanumeric password. Default to 12 characters length
 * otherwise set another size as parameter.
 *
 * @param int[optional] $size Size of password to be generated
 * @return string Alphanumeric password
 */
function random_password ( $size = 12)
{
  return substr ( preg_replace ( "/[^a-zA-Z0-9]/", "", base64_encode ( openssl_random_pseudo_bytes ( $size + 1))), 0, $size);
}

/**
 * Generate an AES key (16 bytes).
 *
 * @return string 16 bytes key
 */
function random_AES_key ()
{
  return substr ( preg_replace ( "/[^a-f0-9]/", "", base64_encode ( openssl_random_pseudo_bytes ( 128))), 0, 16);
}

/**
 * Function to translate form dates (full-date notation as defined by RFC 3339,\
 * section 5.6) to MySQL standard.
 *
 * @param string $date Date in current format
 * @return string String Result date at MySQL format
 */
function format_form_date ( $date)
{
  // If date is a valid YYYY-MM-DD, return MySQL format, otherwise, return empty:
  if ( preg_match ( "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $date))
  {
    return substr ( $date, 0, 4) . "-" . substr ( $date, 5, 2) . "-" . substr ( $date, 8, 2);
  } else {
    return "";
  }
}

/**
 * Function to translate date-time (date-time notation as defined by RFC 3339,
 * section 5.6) to MySQL standard.
 *
 * @param string $date Date and time in current format
 * @return string String Result date and time at MySQL format
 */
function format_form_datetime ( $date)
{
  // If date is a valid ISO8601 at UTC (Zulu timezone), return MySQL format, otherwise, return empty:
  if ( preg_match ( "/^[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}Z$/", $date))
  {
    return substr ( $date, 0, 4) . "-" . substr ( $date, 5, 2) . "-" . substr ( $date, 8, 2) . " " . substr ( $date, 11, 8);
  } else {
    return "";
  }
}

/**
 * Function to translate date-time (date-time notation as defined by RFC 3339,
 * section 5.6) to timestamp.
 *
 * @param string $date Date and time in current format
 * @return int Result date and time at UNIX timestamp
 */
function format_form_timestamp ( $date)
{
  // If date is a valid ISO8601 at UTC (Zulu timezone), return timestamp, otherwise, return empty:
  if ( preg_match ( "/^([0-9]{4})-([0-9]{2})-([0-9]{2})T([0-9]{2}):([0-9]{2}):([0-9]{2})Z$/", $date, $parts))
  {
    return gmmktime ( $parts[4], $parts[5], $parts[6], $parts[2], $parts[3], $parts[1]);
  } else {
    return "";
  }
}

/**
 * Function to translate a MySQL date to current system language date format.
 *
 * @param string $date MySQL format date.
 * @return string Date in current system language format.
 */
function format_db_date ( $date)
{
  // Return empty if invalid date:
  if ( $date == "0" || empty ( $date) || $date == "0000-00-00")
  {
    return "";
  }

  // Split fields:
  $year = substr ( $date, 0, 4);
  $month = substr ( $date, 5, 2);
  $day = substr ( $date, 8, 2);

  // Format output:
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

  // Return formated date:
  return $output;
}

/**
 * Function to translate a MySQL date and time to current system language date format.
 *
 * @param string $datetime MySQL format date and time.
 * @return string Date and time in current system language format.
 */
function format_db_datetime ( $datetime)
{
  // Return empty if invalid date:
  if ( $datetime == "0" || empty ( $datetime) || $datetime == "0000-00-00 00:00:00")
  {
    return "";
  }

  // Split fields:
  $year = substr ( $datetime, 0, 4);
  $month = substr ( $datetime, 5, 2);
  $day = substr ( $datetime, 8, 2);
  $hour = substr ( $datetime, 11, 2);
  $min = substr ( $datetime, 14, 2);
  $sec = substr ( $datetime, 17, 2);

  // Format output:
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

  // Return output:
  return $output . " " . $hour . ":" . $min . ":" . $sec;
}

/**
 * Function to translate a MySQL date and time to ISO8601 date format.
 *
 * @param string $datetime MySQL format date and time.
 * @return string Date and time in ISO8601 format.
 */
function format_db_iso8601 ( $datetime)
{
  // Return empty if invalid date:
  if ( $datetime == "0" || empty ( $datetime) || $datetime == "0000-00-00 00:00:00")
  {
    return "";
  }

  // Split fields:
  $year = substr ( $datetime, 0, 4);
  $month = substr ( $datetime, 5, 2);
  $day = substr ( $datetime, 8, 2);
  $hour = substr ( $datetime, 11, 2);
  $min = substr ( $datetime, 14, 2);
  $sec = substr ( $datetime, 17, 2);

  // Return output:
  return sprintf ( "%04d-%02d-%02dT%02d:%02d:%02dZ", $year, $month, $day, $hour, $min, $sec);
}

/**
 * Function to translate a MySQL date to UNIX timestamp.
 *
 * @param string $datetime MySQL format date and time.
 * @return int Date in UNIX timestamp.
 */
function format_db_timestamp ( $datetime)
{
  // Return empty if invalid date:
  if ( $datetime == "0" || empty ( $datetime) || $datetime == "0000-00-00 00:00:00")
  {
    return "";
  }

  // Split fields:
  $year = substr ( $datetime, 0, 4);
  $month = substr ( $datetime, 5, 2);
  $day = substr ( $datetime, 8, 2);
  $hour = substr ( $datetime, 11, 2);
  $min = substr ( $datetime, 14, 2);
  $sec = substr ( $datetime, 17, 2);

  // Return timestamp:
  return mktime ( $hour, $min, $sec, $month, $day, $year);
}

/**
 * Function to return human readable time format ([[DD:]HH:]MM:SS) from an integer.
 *
 * @param int $time Time in seconds.
 * @return string Human readable formatted time.
 */
function format_secs_to_string ( $time)
{
  // If bigger than a day, add day:
  $result = "";
  if ( $time >= 86400)
  {
    $result = sprintf ( "%02d:", floor ( $time / 86400));
    $time = $time % 86400;
    // Add hour:
    $result .= sprintf ( "%02d:", floor ( $time / 3600));
    $time = $time % 3600;
  } else {
    // If bigger than a hour, add hour:
    if ( $time >= 3600)
    {
      $result .= sprintf ( "%02d:", floor ( $time / 3600));
      $time = $time % 3600;
    }
  }

  // Add minute:
  $result .= sprintf ( "%02d:", floor ( $time / 60));
  $time = $time % 60;

  // Add seconds:
  $result .= sprintf ( "%02d", $time);

  // Return result:
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
 * Function to start capturing events. Any event to the server up to call
 * notify_capture_send() or notify_capture_discard() will be captured and sent
 * later grouped in one event handler or discarded.
 *
 * @global array $_in Framework global configuration variable
 * @param $serverid int ID of server to notify. If 0, notify all servers.
 * @param $event string[optional] Name of start event to fire at server.
 * @param $data array[optional] Start event parameters to send.
 * @return boolean
 */
function notify_capture ( $serverid, $event = "", $data = array ())
{
  global $_in;

  /**
   * First, check if there's any capture event to this server
   */
  if ( ! array_key_exists ( $serverid, $_in["notifycapture"]))
  {
    $_in["notifycapture"][$serverid] = array ();
  }

  /**
   * Add the start event
   */
  if ( $event != "")
  {
    $_in["notifycapture"][$serverid][] = array ( "event" => $event, "data" => $data);
  }

  /**
   * Return true
   */
  return true;
}

/**
 * Function to discard capturing events.
 *
 * @global array $_in Framework global configuration variable
 * @param $serverid int ID of server to notify. If 0, notify all servers.
 * @return boolean
 */
function notify_capture_discard ( $serverid)
{
  global $_in;

  /**
   * First, check if there's any capture event to this server
   */
  if ( ! array_key_exists ( $serverid, $_in["notifycapture"]))
  {
    return false;
  }

  /**
   * Discard all events
   */
  unset ( $_in["notifycapture"][$serverid]);

  /**
   * Return true
   */
  return true;
}

/**
 * Function to finish capturing events and send to the server.
 *
 * @global array $_in Framework global configuration variable
 * @param $serverid int ID of server to notify. If 0, notify all servers.
 * @param $event string[optional] Name of finish event to fire at server.
 * @param $data array[optional] Finish event parameters to send.
 * @return boolean
 */
function notify_capture_send ( $serverid, $event = "", $data = array ())
{
  global $_in;

  /**
   * Add the finish event
   */
  if ( $event != "")
  {
    $_in["notifycapture"][$serverid][] = array ( "event" => $event, "data" => $data);
  }

  /**
   * Check if there's any capture event to this server
   */
  if ( ! array_key_exists ( $serverid, $_in["notifycapture"]))
  {
    return false;
  }

  /**
   * Add group event handler to database
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `GroupedCommands` (`Server`, `Total`, `Left`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $serverid) . ", 0, 0)"))
  {
    return false;
  }
  $handlerid = $_in["mysql"]["id"]->insert_id;

  /**
   * Send each event
   */
  $events = $_in["notifycapture"][$serverid];
  unset ( $_in["notifycapture"][$serverid]);
  foreach ( $events as $event)
  {
    notify_server ( $serverid, $event["event"], $event["data"], $handlerid);
  }

  return true;
}

/**
 * Gearman function name for the client daemon task worker on a server.
 *
 * @param int $serverid Server ID
 * @return string
 */
function gearman_client_task_name ( $serverid)
{
  return "vd_client_" . (int) $serverid . "_task";
}

/**
 * Gearman function name for the backup client daemon task worker.
 *
 * @param int $serverid Primary server ID
 * @return string
 */
function gearman_backup_task_name ( $serverid)
{
  return "vd_backup_server_" . (int) $serverid . "_task";
}

/**
 * Ask the message router daemon to reload its in-memory caches.
 *
 * @global array $_in Framework global configuration variable
 * @param array $parameters Optional parameters (e.g. ServerID)
 * @return boolean
 */
function router_request_cache_reload ( $parameters = array ())
{
  global $_in;

  if ( ! array_key_exists ( "socket", $_in["gearman"]) || ! $_in["gearman"]["socket"])
  {
    $_in["gearman"]["socket"] = new GearmanClient ();
    $_in["gearman"]["socket"]->addServer ( $_in["gearman"]["servers"]);
  }

  return $_in["gearman"]["socket"]->doBackground ( "router_reload_cache", json_encode ( $parameters));
}

/**
 * Function to send notifications to servers (using gearman).
 *
 * @global array $_in Framework global configuration variable
 * @param $serverid int ID of server to notify. If 0, notify all servers.
 * @param $event string Name of event to fire at server.
 * @param $data array[optional] Event parameters to send.
 * @param $handler int[optional] ID of the grouped event handler.
 * @return boolean
 */
function notify_server ( $serverid, $event, $data = array (), $handler = null)
{
  global $_in;

  /**
   * Check if there's active capture to this server
   */
  if ( array_key_exists ( $serverid, $_in["notifycapture"]))
  {
    $_in["notifycapture"][$serverid][] = array ( "event" => $event, "data" => $data);
    return true;
  }

  /**
   * Get server configuration and tenant private key for signing
   */
  $where = "";
  if ( $serverid != 0)
  {
    $where .= " WHERE `Servers`.`ID` ";
    if ( $serverid < 0)
    {
      $where .= "!= " . $_in["mysql"]["id"]->real_escape_string ( (int) $serverid * -1);
    } else {
      $where .= "= " . $_in["mysql"]["id"]->real_escape_string ( (int) $serverid);
    }
  }
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Servers`.*, `Tenants`.`PrivateKey` AS `TenantPrivateKey` FROM `Servers` LEFT JOIN `Tenants` ON `Servers`.`Tenant` = `Tenants`.`ID`" . $where))
  {
    return false;
  }
  if ( $result->num_rows == 0)
  {
    return false;
  }

  /**
   * Connect to Gearman if not connected
   */
  $gearman_available = true;
  if ( ! array_key_exists ( "socket", $_in["gearman"]) || ! $_in["gearman"]["socket"])
  {
    try
    {
      $_in["gearman"]["socket"] = new GearmanClient ();
      $_in["gearman"]["socket"]->addServer ( $_in["gearman"]["servers"]);
    }
    catch ( Exception $e)
    {
      error_log ( "VoIP Domain: Failed to connect to Gearman server (" . $_in["gearman"]["servers"] . "): " . $e->getMessage () . " - Commands will be queued in database for later processing.");
      $gearman_available = false;
      $_in["gearman"]["socket"] = null;
    }
  }

  /**
   * Notify each server
   */
  while ( $server = $result->fetch_assoc ())
  {
    /**
     * Add event to database
     */
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Commands` (`Server`,`Event`,`Data`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( (int) $server["ID"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $event) . "', '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $data)) . "')"))
    {
      return false;
    }
    $command = $_in["mysql"]["id"]->insert_id;

    /**
     * Add grouped event entry to database (if grouped)
     */
    if ( $handler && ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `GroupCommand` (`Group`, `Command`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $handler) . ", " . $_in["mysql"]["id"]->real_escape_string ( $command) . ")") || ! @$_in["mysql"]["id"]->query ( "UPDATE `GroupedCommands` SET `Total` = `Total` + 1, `Left` = `Left` + 1 WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $handler))))
    {
      return false;
    }

    /**
     * Skip if server still doesn't have a configured public key or tenant private key
     */
    if ( empty ( $server["PublicKey"]) || empty ( $server["TenantPrivateKey"]))
    {
      continue;
    }

    /**
     * Skip Gearman send if not available (command is already queued in database)
     */
    if ( ! $gearman_available || ! $_in["gearman"]["socket"])
    {
      continue;
    }

    /**
     * Prepare payload
     */
    $payload = json_encode ( array ( "event" => $event, "id" => $command, "data" => $data, "group" => $handler));

    /**
     * Send event data to main server (skip if filtered out)
     */
    if ( ! array_key_exists ( "notify_backup_filter", $_in) || $_in["notify_backup_filter"]["server"] != $server["ID"] || $_in["notify_backup_filter"]["main"])
    {
      $iv = secure_rand ( 16, true);
      $key = secure_rand ( 256, true);
      $encrypted = openssl_encrypt ( $payload, "aes-256-cbc", $key, OPENSSL_RAW_DATA, $iv);
      $hmac = hash_hmac ( "sha256", $encrypted, $key, true);
      openssl_public_encrypt ( $key, $encryptedkey, $server["PublicKey"]);
      openssl_sign ( $key . $iv . $hmac . $encrypted, $sign, $server["TenantPrivateKey"], OPENSSL_ALGO_SHA256);

      $_in["gearman"]["socket"]->doBackground ( gearman_client_task_name ( $server["ID"]), json_encode ( array ( "payload" => base64_encode ( $encrypted), "iv" => base64_encode ( $iv), "key" => base64_encode ( $encryptedkey), "hmac" => base64_encode ( $hmac), "sign" => base64_encode ( $sign))));
    }

    /**
     * Check for backup servers
     */
    if ( ! $result2 = @$_in["mysql"]["id"]->query ( "SELECT * FROM `ServerBackup` WHERE `Server` = " . $_in["mysql"]["id"]->real_escape_string ( $server["ID"])))
    {
      return false;
    }
    while ( $backup = $result2->fetch_assoc ())
    {
      /**
       * Skip backup if filtered out by notify_backup_filter
       */
      if ( array_key_exists ( "notify_backup_filter", $_in) && $_in["notify_backup_filter"]["server"] == $server["ID"] && $_in["notify_backup_filter"]["backups"] !== null && ! in_array ( (int) $backup["ID"], $_in["notify_backup_filter"]["backups"]))
      {
        continue;
      }

      /**
       * Prepare payload
       */
      $iv = secure_rand ( 16, true);
      $key = secure_rand ( 256, true);
      $encrypted = openssl_encrypt ( $payload, "aes-256-cbc", $key, OPENSSL_RAW_DATA, $iv);
      $hmac = hash_hmac ( "sha256", $encrypted, $key, true);
      openssl_public_encrypt ( $key, $encryptedkey, $backup["PublicKey"]);
      openssl_sign ( $key . $iv . $hmac . $encrypted, $sign, $server["TenantPrivateKey"], OPENSSL_ALGO_SHA256);

      /**
       * Send event data
       */
      $_in["gearman"]["socket"]->doBackground ( gearman_backup_task_name ( $server["ID"]), json_encode ( array ( "payload" => base64_encode ( $encrypted), "iv" => base64_encode ( $iv), "key" => base64_encode ( $encryptedkey), "hmac" => base64_encode ( $hmac), "sign" => base64_encode ( $sign))));
    }
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

  // Return into requested unity:
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
 * Function to compare two arrays. If equals, return true,
 * otherwise return false.
 *
 * @param $array1 array First array.
 * @param $array2 array Second array.
 * @param $strict[optional] boolean Use strict compare (compare variable type
 *                                  too).
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
 * Function to compare two arrays, including keys. If equals, return true,
 * otherwise return false.
 *
 * @param $array1 array First array.
 * @param $array2 array Second array.
 * @param $strict[optional] boolean Use strict compare (compare variable type
 *                                  too).
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
 * Function to explode a string into an array, respecting key and value of each
 * field. Useful with Asterisk QOS fields.
 *
 * @param $string string String to be processed.
 * @return array
 */
function explode_QOS ( $string)
{
  $array = explode ( ";", $string);
  $result = array ();
  foreach ( $array as $value)
  {
    $index = substr ( $value, 0, strpos ( $value, "="));
    $value = substr ( $value, strpos ( $value, "=") + 1);
    if ( preg_match ( "/^[0-9]+\.[0-9]{6}\$/", $value))
    {
      $value = (float) $value;
    }
    if ( preg_match ( "/^[0-9]+\$/", $value))
    {
      $value = (int) $value;
    }

    /**
     * There's a bug into Asterisk RTCP stats where RTT could return an absurd
     * value like 65535.999000, but it's really 0. Fix this here.
     */
    if ( $index == "rtt" && $value >= 65535)
    {
      $value = 0.0;
    }

    $result[$index] = $value;
  }

  return $result;
}

/**
 * Function to filter QOS information to resumed structure.
 * For a full description of Asterisk channel RTP variables, please refer to
 * https://wiki.asterisk.org/wiki/display/AST/Asterisk+13+Function_CHANNEL
 *
 * @param $qos array Input raw QOS information.
 * @return array Filtered QOS.
 */
function filter_QOS ( $qos)
{
  return array (
    "SSRC" => array (
      "Our" => $qos["ssrc"],
      "Their" => $qos["themssrc"]
    ),
    "SentPackets" => array (
      "Packets" => $qos["txcount"],
      "Lost" => $qos["rlp"],
      "LostPercentage" => ( $qos["rlp"] * 100) / $qos["txcount"],
      "Jitter" => $qos["txjitter"]
    ),
    "ReceivedPackets" => array (
      "Packets" => $qos["rxcount"],
      "Lost" => $qos["lp"],
      "LostPercentage" => ( $qos["lp"] * 100) / $qos["rxcount"],
      "Jitter" => $qos["rxjitter"]
    ),
    "RTT" => $qos["rtt"]
  );
}

/**
 * Function to calculate the MOS of a call.
 *
 * @param $qosa string Asterisk RTCP QOS call information (side a).
 * @param $qosb[optional] string Asterisk RTCP QOS call information (side b, optional).
 * @return float Call MOS
 */
function calculate_MOS ( $qosa, $qosb = "")
{
  // Extract values from QOS:
  $qosa = explode_QOS ( $qosa);
  if ( ! empty ( $qosb))
  {
    $qosb = explode_QOS ( $qosb);
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
 * Function to check if a given string is a JSON encoded string.
 *
 * @param string $json String to test for
 * @return boolean True if is a JSON encoded string, otherwise, false
 */
function is_JSON ( $json)
{
  return is_string ( $json) && is_array ( json_decode ( $json, true)) && ( json_last_error () == JSON_ERROR_NONE) ? true : false;
}

/**
 * Function to check if a given string is a XML encoded string.
 *
 * @param string $xml String to test for
 * @return boolean True if is a XML encoded string, otherwise, false
 */
function is_XML ( $xml)
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
function cidr_match ( $ip, $cidr)
{
  list ( $subnet, $bits) = explode ( "/", $cidr);
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
 * For PHP versions <= 7.3.0, we don't have array_key_last function, so, implement it if needed.
 */
if ( ! function_exists ( "array_key_last"))
{
  function array_key_last ( $array)
  {
    if ( ! is_array ( $array) || empty ( $array))
    {
      return NULL;
    }

    return array_keys ( $array)[count ( $array) - 1];
  }
}

/**
 * For PHP versions <= 5.5.0, we don't have hash_pbkdf2 function, so, implement it if needed.
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
 * Build an absolute URL for HTTP Location headers from an application path.
 *
 * Paths starting with http:// or https:// are returned unchanged. Relative paths
 * are prefixed with the request scheme and configured host (general.baseurl).
 *
 * @param string $path Application path (e.g. "/api/users/1") or absolute URL
 * @return string
 */
function voipdomain_location_url ( $path)
{
  global $_in;

  if ( $path === "" || $path === null)
  {
    return $path;
  }
  if ( preg_match ( '#^https?://#i', $path))
  {
    return $path;
  }
  if ( $path[0] !== "/")
  {
    $path = "/" . $path;
  }
  if ( ! empty ( $_SERVER["HTTP_X_FORWARDED_PROTO"]))
  {
    $scheme = $_SERVER["HTTP_X_FORWARDED_PROTO"];
  } elseif ( ! empty ( $_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off")
  {
    $scheme = "https";
  } else {
    $scheme = "http";
  }
  if ( ! empty ( $_in["general"]["baseurl"]))
  {
    $host = $_in["general"]["baseurl"];
  } elseif ( ! empty ( $_SERVER["HTTP_HOST"]))
  {
    $host = $_SERVER["HTTP_HOST"];
  } else {
    $host = $_SERVER["SERVER_NAME"];
  }

  return $scheme . "://" . $host . $path;
}

/**
 * Return whether VoIP Domain web configuration still requires the web installer.
 *
 * @param array|null $conf Parsed webinterface.conf (sections as arrays)
 * @return boolean
 */
function voipdomain_config_requires_install ( $conf)
{
  if ( ! is_array ( $conf))
  {
    return true;
  }
  if ( array_key_exists ( "general", $conf) && is_array ( $conf["general"]) && array_key_exists ( "installed", $conf["general"]))
  {
    if ( ! filter_var ( $conf["general"]["installed"], FILTER_VALIDATE_BOOLEAN))
    {
      return true;
    }
  }
  if ( ! array_key_exists ( "mysql", $conf) || ! is_array ( $conf["mysql"]) || empty ( $conf["mysql"]["database"]))
  {
    return true;
  }
  return false;
}

/**
 * Human-readable owner, group, and mode for a path (for install diagnostics).
 *
 * @param string $path Filesystem path
 * @return string
 */
function voipdomain_path_permissions_text ( $path)
{
  if ( ! file_exists ( $path))
  {
    return __ ( "path does not exist");
  }
  $mode = substr ( sprintf ( "%o", fileperms ( $path)), -4);
  $owner = (string) fileowner ( $path);
  $group = (string) filegroup ( $path);
  if ( function_exists ( "posix_getpwuid"))
  {
    $pw = @posix_getpwuid ( fileowner ( $path));
    if ( is_array ( $pw) && ! empty ( $pw["name"]))
    {
      $owner = $pw["name"];
    }
    $gr = @posix_getgrgid ( filegroup ( $path));
    if ( is_array ( $gr) && ! empty ( $gr["name"]))
    {
      $group = $gr["name"];
    }
  }
  return $owner . ":" . $group . " " . __ ( "mode") . " " . $mode;
}

/**
 * PHP-FPM / CLI process username when POSIX is available.
 *
 * @return string
 */
function voipdomain_php_process_user ()
{
  if ( function_exists ( "posix_geteuid"))
  {
    $pw = @posix_getpwuid ( posix_geteuid ());
    if ( is_array ( $pw) && ! empty ( $pw["name"]))
    {
      return $pw["name"];
    }
  }
  return "php-fpm";
}

/**
 * Verify PHP can create and remove a file in a directory (POSIX + SELinux).
 *
 * @param string $dir Directory path
 * @return bool
 */
function voipdomain_install_dir_write_probe ( $dir)
{
  $probe = $dir . "/.voipdomain-install-probe-" . getmypid ();
  $ok = @file_put_contents ( $probe, "ok\n", LOCK_EX) !== false;
  if ( $ok)
  {
    @unlink ( $probe);
  }
  return $ok;
}

/**
 * Verify PHP can open an existing path for read/write (installer overwrite).
 *
 * @param string $path File path
 * @return bool
 */
function voipdomain_install_file_write_probe ( $path)
{
  if ( ! file_exists ( $path))
  {
    return true;
  }
  $handle = @fopen ( $path, "c+");
  if ( $handle === false)
  {
    return false;
  }
  fclose ( $handle);
  return true;
}

/**
 * Detailed filesystem / config checks for the web installer (GET /api/install/check).
 *
 * @return array Result, Description, Checks[]
 */
function voipdomain_install_check_status ()
{
  $conf_dir = "/etc/voipdomain";
  $conf_path = $conf_dir . "/webinterface.conf";
  $php_user = voipdomain_php_process_user ();
  $checks = array ();
  $description = "";

  $dir_exists = is_dir ( $conf_dir);
  $dir_mode = $dir_exists ? substr ( sprintf ( "%o", fileperms ( $conf_dir)), -4) : "";
  $dir_writable = $dir_exists && is_writable ( $conf_dir);
  $dir_write_probe = $dir_exists && voipdomain_install_dir_write_probe ( $conf_dir);
  $checks[] = array (
    "Name" => "ConfigDirectory",
    "Result" => $dir_exists,
    "Detail" => $dir_exists ? voipdomain_path_permissions_text ( $conf_dir) : __ ( "Directory missing")
  );

  $checks[] = array (
    "Name" => "ConfigDirectoryWritable",
    "Result" => $dir_write_probe,
    "Detail" => $dir_write_probe ? __ ( "Writable by PHP") : ( $dir_writable ? __ ( "Access denied (check SELinux)") : ( $dir_mode !== "" ? __ ( "mode") . " " . $dir_mode : __ ( "Not writable")))
  );
  if ( ! $dir_write_probe)
  {
    if ( ! $dir_exists)
    {
      $description = __ ( "Configuration directory /etc/voipdomain is missing.");
    } elseif ( $dir_mode === "0770")
    {
      $description = sprintf ( __ ( "PHP (%s) cannot write to /etc/voipdomain. Check that the pool user is in group nginx and SELinux allows writes."), $php_user);
    } else {
      $description = sprintf ( __ ( "PHP (%s) cannot write to /etc/voipdomain (expected root:nginx mode 0770)."), $php_user);
    }
  }

  $conf_exists = file_exists ( $conf_path);
  $conf_mode = $conf_exists ? substr ( sprintf ( "%o", fileperms ( $conf_path)), -4) : "";
  $conf_readable = ! $conf_exists || is_readable ( $conf_path);
  $conf_writable = voipdomain_install_file_write_probe ( $conf_path);
  $checks[] = array (
    "Name" => "ConfigFile",
    "Result" => $conf_readable,
    "Detail" => ! $conf_exists ? __ ( "Will be created by installer") : ( $conf_readable ? __ ( "Readable") : voipdomain_path_permissions_text ( $conf_path))
  );
  $checks[] = array (
    "Name" => "ConfigFileWritable",
    "Result" => $conf_writable,
    "Detail" => ! $conf_exists ? __ ( "Will be created by installer") : ( $conf_writable ? __ ( "Writable by PHP") : ( is_writable ( $conf_path) ? __ ( "Access denied (check SELinux)") : voipdomain_path_permissions_text ( $conf_path)))
  );
  if ( $description === "" && $conf_exists && ! is_readable ( $conf_path))
  {
    $description = __ ( "PHP cannot read /etc/voipdomain/webinterface.conf.");
  }
  if ( $description === "" && $conf_exists && ! $conf_writable)
  {
    if ( $conf_mode === "0640")
    {
      $description = sprintf ( __ ( "PHP (%s) cannot overwrite /etc/voipdomain/webinterface.conf: mode 0640 gives group nginx read-only. Use chmod 0660 root:nginx."), $php_user);
    } elseif ( $conf_mode === "0660")
    {
      $description = sprintf ( __ ( "PHP (%s) cannot overwrite /etc/voipdomain/webinterface.conf. Check group membership and SELinux context httpd_sys_rw_content_t on the file."), $php_user);
    } else {
      $description = sprintf ( __ ( "PHP (%s) cannot overwrite /etc/voipdomain/webinterface.conf (expected root:nginx mode 0660)."), $php_user);
    }
  }

  $conf = $conf_exists && is_readable ( $conf_path) ? @parse_ini_file ( $conf_path, true) : false;
  $parse_ok = ! $conf_exists || is_array ( $conf);
  $checks[] = array (
    "Name" => "ConfigParse",
    "Result" => $parse_ok,
    "Detail" => $parse_ok ? __ ( "OK") : __ ( "Syntax error")
  );
  if ( $description === "" && ! $parse_ok)
  {
    $description = __ ( "Cannot parse /etc/voipdomain/webinterface.conf.");
  }

  $install_pending = voipdomain_config_requires_install ( $conf);
  $checks[] = array (
    "Name" => "InstallPending",
    "Result" => $install_pending,
    "Detail" => $install_pending ? __ ( "Installer required") : __ ( "Already configured")
  );
  if ( $description === "" && ! $install_pending)
  {
    $description = __ ( "System is already installed. Open the login page or reset webinterface.conf to run the installer again.");
  }

  $ok = $dir_write_probe && $conf_writable && $parse_ok && $install_pending && $conf_readable;

  return array (
    "Result" => $ok,
    "Description" => $ok ? __ ( "All permissions are okay.") : ( $description !== "" ? $description : __ ( "Installation check failed.")),
    "Checks" => $checks
  );
}

/**
 * Open a MySQL connection without throwing on PHP 8+ (mysqli_sql_exception).
 *
 * @param string $hostname Database host (optionally host:port)
 * @param string $username Database username
 * @param string $password Database password
 * @param string $database Database name
 * @return array Keys: id (mysqli|null), errno (int), error (string|null)
 */
function voipdomain_mysqli_connect ( $hostname, $username, $password, $database)
{
  mysqli_report ( MYSQLI_REPORT_OFF);
  $link = mysqli_init ();
  if ( ! $link)
  {
    return array ( "id" => NULL, "errno" => -1, "error" => "mysqli_init failed");
  }

  // Parse hostname:port if present
  $port = null;
  $socket = null;
  if ( strpos ( $hostname, ":") !== false)
  {
    list ( $hostname, $port) = explode ( ":", $hostname, 2);
    $port = (int) $port;
  }

  // Use socket for localhost connections (same behavior as mysql CLI)
  if ( $hostname === "localhost")
  {
    $socket = ini_get ( "mysqli.default_socket");
    if ( empty ( $socket))
    {
      // Common socket paths
      foreach ( array ( "/var/lib/mysql/mysql.sock", "/var/run/mysqld/mysqld.sock", "/tmp/mysql.sock") as $path)
      {
        if ( file_exists ( $path))
        {
          $socket = $path;
          break;
        }
      }
    }
  }

  $result = @mysqli_real_connect ( $link, $hostname, $username, ( $password === "" ? null : $password), ( $database === "" ? null : $database), $port, $socket);
  if ( ! $result)
  {
    return array ( "id" => NULL, "errno" => mysqli_connect_errno (), "error" => mysqli_connect_error ());
  }
  return array ( "id" => $link, "errno" => 0, "error" => NULL);
}

/**
 * Function to return a system translation to current user language.
 *
 * @global array $_in Framework global configuration variable
 * @param string $text Text to be translated
 * @param boolean $strip[optional] Strip HTML tags (default true)
 * @param boolean $slashes[optional] Add slashes before " or ' (default true)
 * @param boolean $context[optional] Set alternative system module.
 * @return string Translated text (if not found, the text itself)
 */
function __ ( $text, $strip = true, $slashes = true, $context = "")
{
  global $_in;

  if ( ! is_array ( $_in))
  {
    $return = $text;
    if ( $strip)
    {
      $return = strip_tags ( $return ? $return : "");
    }
    if ( $slashes)
    {
      $return = addslashes ( $return);
    }
    return $return;
  }

  if ( empty ( $context) && array_key_exists ( "module", $_in))
  {
    $context = $_in["module"];
  }
  if ( array_key_exists ( "i18n", $_in) && array_key_exists ( $text, $_in["i18n"]))
  {
    if ( array_key_exists ( $_in["general"]["language"] . ( $context != "" ? "-" . $context : ""), $_in["i18n"][$text]))
    {
      $return = $_in["i18n"][$text][$_in["general"]["language"] . ( $context != "" ? "-" . $context : "")];
    } else {
      if ( array_key_exists ( $_in["general"]["language"], $_in["i18n"][$text]))
      {
        $return = $_in["i18n"][$text][$_in["general"]["language"]];
      } else {
        $return = $text;
      }
    }
  } else {
    $return = $text;
  }
  if ( $strip)
  {
    $return = strip_tags ( $return ? $return : "");
  }
  if ( $slashes)
  {
    $return = addslashes ( $return ? $return : "");
  }
  return $return;
}

/**
 * Function to echo a system translation to current user language.
 *
 * @param string $text Text to be translated
 * @param boolean $strip[optional] Strip HTML tags (default true)
 * @param boolean $slashes[optional] Add slashes before " or ' (default true)
 * @param boolean $context[optional] Set alternative system module.
 * @return void
 */
function _e ( $text, $strip = true, $slashes = true, $context = "")
{
  echo __ ( $text, $strip, $slashes, $context);
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
 * @param boolean $context[optional] Set alternative system module.
 * @return string Translated text (if not found, the text itself)
 */
function _n ( $singular, $plural, $count, $strip = true, $slashes = true, $context = "")
{
  global $_in;

  if ( empty ( $context) && array_key_exists ( "module", $_in))
  {
    $context = $_in["module"];
  }
  if ( $count == 1)
  {
    if ( array_key_exists ( $_in["general"]["language"] . ( $context != "" ? "-" . $context : ""), $_in["i18n"][$singular]))
    {
      $return = $_in["i18n"][$singular][$_in["general"]["language"] . ( $context != "" ? "-" . $context : "")];
    } else {
      if ( array_key_exists ( $_in["general"]["language"], $_in["i18n"][$singular]))
      {
        $return = $_in["i18n"][$singular][$_in["general"]["language"]];
      } else {
        $return = $singular;
      }
    }
  } else {
    if ( array_key_exists ( $_in["general"]["language"] . ( $context != "" ? "-" . $context : ""), $_in["i18n"][$plural]))
    {
      $return = $_in["i18n"][$plural][$_in["general"]["language"] . ( $context != "" ? "-" . $context : "")];
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
 * @param boolean $context[optional] Set alternative system module.
 * @return void
 */
function _ne ( $singular, $plural, $count, $strip = true, $slashes = true, $context = "")
{
  echo _n ( $singular, $plural, $count, $strip, $slashes, $context);
}

/**
 * Function to add a new translation string to the system. This function will
 * create the necessary internationalization system array to the desired string.
 *
 * @global array $_in Framework global configuration variable
 * @param string $text Text to be added (in en_US)
 * @param string[optional] $translation The translation text for $language
 * @param string[optional] $language The language of translated text
 * @param string[optional] $context The context of translated text
 * @param boolean $context[optional] Set alternative system module.
 * @return void
 */
function i18n_add ( $text, $translation = "", $language = "", $context = "")
{
  global $_in;

  if ( ! is_array ( $_in["i18n"] ?? null))
  {
    $_in["i18n"] = array ();
  }
  if ( empty ( $context))
  {
    $context = $_in["module"];
  }
  if ( ! array_key_exists ( $text, $_in["i18n"]))
  {
    $_in["i18n"][$text] = array ();
  }
  if ( $translation != "" && $language != "")
  {
    $_in["i18n"][$text][$language . ( $context != "" ? "-" . $context : "")] = $translation;
  }
}

/**
 * Function to dump all translations.
 *
 * @global array $_in Framework global configuration variable
 * @param string[optional] $context The context of translated text
 * @param string[optional] $language The language of translated text
 * @return array The list of translation strings
 */
function i18n_dump ( $language = "", $context = "")
{
  global $_in;

  if ( empty ( $context))
  {
    $context = $_in["module"];
  }
  if ( empty ( $language))
  {
    $language = $_in["general"]["language"];
  }
  $i18n = array ();
  $data = array_keys ( $_in["i18n"]);
  foreach ( $data as $entry)
  {
    if ( $language == "en_US")
    {
      $i18n[$entry] = $entry;
    } else {
      $i18n[$entry] = $_in["i18n"][$entry][$language . ( $context != "" ? "-" . $context : "")];
    }
  }

  return $i18n;
}

/**
 * Function to sort NAPTR DNS query result.
 *
 * @array $a First element.
 * @array $b Second element.
 * @return int -1 ($a > $b), 0 ($a == $b) or 1 ($a < $b)
 */
function sort_naptr ( $a, $b)
{
  if ( $a["order"] == $b["order"])
  {
    if ( $a["pref"] == $b["pref"])
    {
      return 0;
    }
    return ( $a["pref"] > $b["pref"] ? 1 : -1);
  }
  return ( $a["order"] > $b["order"] ? 1 : -1);
}

/**
 * Function to sort SRV DNS query result.
 *
 * @array $a First element.
 * @array $b Second element.
 * @return int -1 ($a > $b), 0 ($a == $b) or 1 ($a < $b)
 */
function sort_srv ( $a, $b)
{
  if ( $a["pri"] == $b["pri"])
  {
    if ( $a["weight"] == $b["weight"])
    {
      return 0;
    }
    return ( $a["weight"] > $b["weight"] ? 1 : -1);
  }
  return ( $a["pri"] > $b["pri"] ? 1 : -1);
}

/**
 * Function to return the difference between two arrays, in a recursive way.
 *
 * @param array $array1 First array.
 * @param array $array2 Second array.
 * @return array Array with the elements changed or new on $array2.
 */
function array_diff_recursive ( $array1, $array2)
{
  $difference = array ();
  foreach ( $array1 as $key => $value)
  {
    if ( ! array_key_exists ( $key, $array2))
    {
      continue;
    }
    if ( ! is_array ( $value))
    {
      if ( $array2[$key] !== $value)
      {
        $difference[$key] = $array2[$key];
      }
    } else {
      $result = array_diff_recursive ( $value, $array2[$key]);
      if ( sizeof ( $result) != 0)
      {
        $difference[$key] = $result;
      }
    }
    unset ( $array2[$key]);
  }
  return array_merge_recursive ( $difference, $array2);
}

/**
 * Function to remove file or directory recursively if needed.
 *
 * @param string $filename Full path to file or directory to be excluded
 * @return null
 */
function unlink_recursive ( $filename)
{
  if ( is_dir ( $filename))
  {
    $files = scandir ( $filename);
    foreach ( $files as $file)
    {
      if ( $file != "." && $file != "..")
      {
        unlink_recursive ( $filename . "/" . $file);
      }
    }
    rmdir ( $filename);
  } else {
    unlink ( $filename);
  }
}

/**
 * Function to process rules from web page.
 *
 * @param array $rules Rules to be processed.
 * @return array
 */
function fix_rules ( $rules)
{
  foreach ( $rules as $key => $value)
  {
    if ( $key == "rules")
    {
      foreach ( $rules[$key] as $id => $rule)
      {
        if ( array_key_exists ( "rules", $rule))
        {
          $rules[$key][$id] = fix_rules ( $rule);
          continue;
        }
        switch ( $rule["type"])
        {
          case "string":
            if ( is_array ( $rule["value"]))
            {
              foreach ( $rule["value"] as $valid => $valcontent)
              {
                $rules[$key][$id]["value"][$valid] = (string) $rule["value"][$valid];
              }
            } else {
              $rules[$key][$id]["value"] = (string) $rule["value"];
            }
            break;
          case "integer":
            if ( is_array ( $rule["value"]))
            {
              foreach ( $rule["value"] as $valid => $valcontent)
              {
                $rules[$key][$id]["value"][$valid] = (int) $rule["value"][$valid];
              }
            } else {
              $rules[$key][$id]["value"] = (int) $rule["value"];
            }
            break;
          case "double":
            if ( is_array ( $rule["value"]))
            {
              foreach ( $rule["value"] as $valid => $valcontent)
              {
                $rules[$key][$id]["value"][$valid] = (float) $rule["value"][$valid];
              }
            } else {
              $rules[$key][$id]["value"] = (float) $rule["value"];
            }
            break;
          case "date":
            if ( is_array ( $rule["value"]))
            {
              foreach ( $rule["value"] as $valid => $valcontent)
              {
                $rules[$key][$id]["value"][$valid] = format_form_timestamp ( $rule["value"][$valid] . " 00:00:00");
              }
            } else {
              $rules[$key][$id]["value"] = format_form_timestamp ( $rule["value"] . " 00:00:00");
            }
            break;
          case "time":
            if ( is_array ( $rule["value"]))
            {
              foreach ( $rule["value"] as $valid => $valcontent)
              {
                $rules[$key][$id]["value"][$valid] = (string) $rule["value"][$valid];
              }
            } else {
              $rules[$key][$id]["value"] = (string) $rule["value"];
            }
            break;
          case "datetime":
            if ( is_array ( $rule["value"]))
            {
              foreach ( $rule["value"] as $valid => $valcontent)
              {
                $rules[$key][$id]["value"][$valid] = format_form_timestamp ( $rule["value"][$valid]);
              }
            } else {
              $rules[$key][$id]["value"] = format_form_timestamp ( $rule["value"]);
            }
            break;
          case "boolean":
            if ( is_array ( $rule["value"]))
            {
              foreach ( $rule["value"] as $valid => $valcontent)
              {
                $rules[$key][$id]["value"][$valid] = (boolean) $rule["value"][$valid];
              }
            } else {
              $rules[$key][$id]["value"] = (boolean) $rule["value"];
            }
            break;
        }
      }
    }
  }

  return $rules;
}

/**
 * Generate an UUID v4 unique identifier.
 *
 * @param $data[optional] A random 16 bytes string.
 * @return string A valid UUID v4 unique identifier.
 */
function guidv4 ( $data = null)
{
  if ( $data == null || strlen ( $data) != 16)
  {
    $data = openssl_random_pseudo_bytes ( 16);
  }

  $data[6] = chr ( ord ( $data[6]) & 0x0f | 0x40); // set version to 0100
  $data[8] = chr ( ord ( $data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

  return vsprintf ( "%s%s-%s-%s-%s-%s%s%s", str_split ( bin2hex ( $data), 4));
}

/**
 * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
 * keys to arrays rather than overwriting the value in the first array with the duplicate
 * value in the second array, as array_merge does. I.e., with array_merge_recursive,
 * this happens (documented behavior):
 *
 * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
 *     => array('key' => array('org value', 'new value'));
 *
 * array_merge_recursive_distinct does not change the datatypes of the values in the arrays.
 * Matching keys' values in the second array overwrite those in the first array, as is the
 * case with array_merge, i.e.:
 *
 * array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
 *     => array('key' => array('new value'));
 *
 * Parameters are passed by reference, though only for performance reasons. They're not
 * altered by this function.
 *
 * @param array $array1
 * @param array $array2
 * @return array
 * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
 * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
 */
function array_merge_recursive_distinct ( array &$array1, array &$array2)
{
  $merged = $array1;

  foreach ( $array2 as $key => &$value)
  {
    if ( is_array ( $value) && isset ( $merged[$key]) && is_array ( $merged[$key]))
    {
      $merged[$key] = array_merge_recursive_distinct ( $merged[$key], $value);
    } else {
      $merged[$key] = $value;
    }
  }

  return $merged;
}

/**
 * Function to convert a size number to a human readable space string (KB, MB,
 * GB, etc.).
 *
 * @param $size integer Size to be converted
 * @param $base[optional] integer Use base 2 or base 10 (1024 or 1000 per Kilo)
 * @param $limit[optional] integer Limit to some size (0 = K, 1 = M, 2 = G,
 *                                 3 = T, 4 = P, 5 = E, 6 = Z and 7 = Y)
 * @return string
 */
function human_size ( $size, $base = 2, $limit = 255)
{
  $byteSize = $base == 10 ? 1000 : 1024;
  $byteUnit = array ( "K", "M", "G", "T", "P", "E", "Z", "Y");

  $size /= $byteSize;
  $x = 0;

  if ( $limit == 255 || $limit > sizeof ( $byteUnit))
  {
    while ( $size > $byteSize)
    {
      $size /= $byteSize;
      $x++;
    }
  } else {
    while ( $x < $limit)
    {
      $size /= $byteSize;
      $x++;
    }
  }

  return sprintf ( "%1.2f", $size) . " " . $byteUnit[$x] . ( $base == 10 ? "" : "i") . "B";
}

/**
 * Function to detect if an array is an associative indexed array.
 *
 * @param $array array The array to be checked
 * @return boolean
 */
function is_assoc ( array $array)
{
  if ( $array === array ())
  {
    return false;
  }
  return array_keys ( $array) !== range ( 0, count ( $array) - 1);
}

/**
 * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
 * keys to arrays rather than overwriting the value in the first array with the duplicate
 * value in the second array, as array_merge does. I.e., with array_merge_recursive,
 * this happens (documented behavior):
 *
 * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
 *     => array('key' => array('org value', 'new value'));
 *
 * array_merge_recursive_distinct does not change the datatypes of the values in the arrays.
 * Matching keys' values in the second array overwrite those in the first array, as is the
 * case with array_merge, i.e.:
 *
 * array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
 *     => array('key' => array('new value'));
 *
 * Parameters are passed by reference, though only for performance reasons. They're not
 * altered by this function.
 *
 * This function differs from array_merge_recursive_distinct because it check if
 * the value is an array, and if it's a sequential array, merge it. The default
 * action is to overwrite the sequential array values.
 *
 * @param array $array1 First array to be merged.
 * @param array $array2 Second array to be merged.
 * @return array Merged array.
 */
function array_merge_recursive_distinct_with_sequencial ( array &$array1, array &$array2)
{
  $merged = $array1;

  foreach ( $array2 as $key => &$value)
  {
    if ( is_array ( $value) && isset ( $merged[$key]) && is_array ( $merged[$key]))
    {
      if ( is_assoc ( $value) || is_assoc ( $merged[$key]))
      {
        $merged[$key] = array_merge_recursive_distinct_with_sequencial ( $merged[$key], $value);
      } else {
        $newarray = array ();
        foreach ( $merged[$key] as $newkey => $newvalue)
        {
          $newarray[] = $newvalue;
        }
        foreach ( $value as $newkey => $newvalue)
        {
          $newarray[] = $newvalue;
        }
        $merged[$key] = $newarray;
      }
    } else {
      $merged[$key] = $value;
    }
  }

  return $merged;
}

/**
 * Function to format a string from a content based on a pattern. The pattern
 * could be any string, and will "cut" pieces of the content using {X} (specific
 * character at position X), {X,Y} (characters between position X with Y length)
 * and {X,} (characters from X position to finish of the content).
 * Example:
 *   str_format ( "+5551992425885", "({3,2}) {5,5}-{10,}")
 *   will output "(51) 99242-5885"
 *
 * @param $content string Content to be cutted.
 * @param $pattern string Pattern to be used.
 * @result string Result of the pattern and content.
 */
function str_format ( $content, $pattern)
{
  $output = "";
  while ( strpos ( $pattern, "{") !== false)
  {
    $output .= substr ( $pattern, 0, strpos ( $pattern, "{"));
    $pattern = substr ( $pattern, strpos ( $pattern, "{"));
    $block = substr ( $pattern, 0, strpos ( $pattern, "}") + 1);
    $pattern = substr ( $pattern, strlen ( $block));
    if ( strpos ( $block, ",") !== false)
    {
      $start = (int) substr ( $block, 1, strpos ( $block, ","));
      if ( substr ( $block, strlen ( $start) + 2, 1) == "}")
      {
        $output .= substr ( $content, $start);
      } else {
        $output .= substr ( $content, $start, (int) substr ( str_replace ( "}", "", $block), strpos ( $block, ",") + 1));
      }
    } else {
      $output .= substr ( $content, (int) str_replace ( "{", "", str_replace ( "}", "", $block)), 1);
    }
  }
  $output .= $pattern;

  return $output;
}

/**
 * Function to get the first key of an array.
 *
 * @param $array array The array to be analyzed.
 * @return int|string|null The first array key. If not exist, return null.
 */
if ( ! function_exists ( "array_key_first"))
{
  function array_key_first ( array $array)
  {
    foreach ( $array as $key => $unused)
    {
      return $key;
    }
    return NULL;
  }
}

/**
 * Function that implements base32 encoding (RFC4648).
 *
 * @param $string string The data to be encoded.
 * @param $pad[optional] boolean Pad output.
 * @return string The RFC4648 encoded data.
 */
function base32_encode ( $string, $pad = false)
{
  $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ234567";
  $stringSize = strlen ( $string);
  $res = "";
  $remainder = 0;
  $remainderSize = 0;

  for ( $x = 0; $x < $stringSize; $x++)
  {
    $b = ord ( $string[$x]);
    $remainder = ( $remainder << 8) | $b;
    $remainderSize += 8;
    while ( $remainderSize > 4)
    {
      $remainderSize -= 5;
      $c = $remainder & ( 31 << $remainderSize);
      $c >>= $remainderSize;
      $res .= $chars[$c];
    }
  }
  if ( $remainderSize > 0)
  {
    $remainder <<= ( 5 - $remainderSize);
    $c = $remainder & 31;
    $res .= $chars[$c];
  }
  if ( $pad)
  {
    $padSize = ( 8 - ceil ( ( $stringSize % 5) * 8 / 5)) % 8;
    $res .= str_repeat ( "=", $padSize);
  }

  return $res;
}

/**
 * Function that implements base32 decoding (RFC4648).
 *
 * @param $string string The RFC4648 encoded data.
 * @return string The decoded data.
 */
function base32_decode ( $string)
{
  $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ234567";
  $string = rtrim ( $string, "=\x20\t\n\r\0\x0B");
  $stringSize = strlen ( $string);
  $buf = 0;
  $bufSize = 0;
  $res = "";
  $charMap = array_flip ( str_split ( $chars));
  $charMap += array_flip ( str_split ( strtolower ( $chars)));

  for ( $x = 0; $x < $stringSize; $x++)
  {
    $c = $string[$x];
    if ( ! isset ( $charMap[$c]))
    {
      if ( $c == " " || $c == "\r" || $c == "\n" || $c == "\t")
      {
        continue;
      }
      throw new Exception ( "Encoded string contains unexpected char #" . ord ( $c) . " at offset " . $x . " (using improper alphabet?)");
    }
    $b = $charMap[$c];
    $buf = ( $buf << 5) | $b;
    $bufSize += 5;
    if ( $bufSize > 7)
    {
      $bufSize -= 8;
      $b = ( $buf & ( 0xff << $bufSize)) >> $bufSize;
      $res .= chr ( $b);
    }
  }

  return $res;
}

/**
 * Function to generate a RFC6238 (TOTP) codes.
 *
 * @param $secret string The base32-encoded authentication secret.
 * @param $ranges[optional] integer The range of codes to accept.
 * @param $size[optional] integer The size of code, 6 or 8 digits.
 * @return array An array with result codes.
 */
function rfc6238_generate ( $secret, $ranges = 3, $size = 6)
{
  $secret = base32_decode ( $secret);
  $unixtimestamp = time () / 30;
  $result = array ();

  // Generate $ranges keys before and after
  for ( $x = - $ranges; $x <= $ranges; $x++)
  {
    $checktime = (int) $unixtimestamp + $x;

    // Calculate key
    $currentcounter = array ( 0, 0, 0, 0, 0, 0, 0, 0);
    for ( $y = 7; $y >= 0; $y--)
    {
      $currentcounter[$y] = pack ( "C*", $checktime);
      $checktime = $checktime >> 8;
    }
    $binary = implode ( $currentcounter);
    str_pad ( $binary, 8, chr ( 0), STR_PAD_LEFT);
    $thiskey = hash_hmac ( "sha1", $binary, $secret);

    // Truncate key
    $hashcharacters = str_split ( $thiskey, 2);
    $hmac_result = array ();
    for ( $y = 0; $y < count ( $hashcharacters); $y++)
    {
      $hmac_result[] = hexdec ( $hashcharacters[$y]);
    }
    $offset = $hmac_result[19] & 0x0f;

    // Add key to the result array
    $result[] = sprintf ( "%0" . $size . "d", (
                  ( ( $hmac_result[$offset + 0] & 0x7f) << 24 ) |
                  ( ( $hmac_result[$offset + 1] & 0xff) << 16 ) |
                  ( ( $hmac_result[$offset + 2] & 0xff) << 8 ) |
                  ( $hmac_result[$offset + 3] & 0xff)
                ) % pow ( 10, $size));
  }

  return $result;
}

/**
 * Function to validate a RFC6238 (TOTP) user code.
 *
 * @param $secret string The base32-encoded authentication secret.
 * @param $code string The user current code.
 * @param $ranges[optional] integer The range of codes to accept.
 * @param $size[optional] integer The size of code, 6 or 8 digits.
 * @return boolean True if code is valid.
 */
function rfc6238_validate ( $secret, $code, $ranges = 3, $size = 6)
{
  $secret = base32_decode ( $secret);
  $unixtimestamp = time () / 30;

  // Try $ranges keys before and after
  for ( $x = - $ranges; $x <= $ranges; $x++)
  {
    $checktime = (int) $unixtimestamp + $x;

    // Calculate key
    $currentcounter = array ( 0, 0, 0, 0, 0, 0, 0, 0);
    for ( $y = 7; $y >= 0; $y--)
    {
      $currentcounter[$y] = pack ( "C*", $checktime);
      $checktime = $checktime >> 8;
    }
    $binary = implode ( $currentcounter);
    str_pad ( $binary, 8, chr ( 0), STR_PAD_LEFT);
    $thiskey = hash_hmac ( "sha1", $binary, $secret);

    // Truncate key
    $hashcharacters = str_split ( $thiskey, 2);
    $hmac_result = array ();
    for ( $y = 0; $y < count ( $hashcharacters); $y++)
    {
      $hmac_result[] = hexdec ( $hashcharacters[$y]);
    }
    $offset = $hmac_result[19] & 0x0f;

    // Test key
    if ( (int) $code == (
      ( ( $hmac_result[$offset + 0] & 0x7f) << 24 ) |
      ( ( $hmac_result[$offset + 1] & 0xff) << 16 ) |
      ( ( $hmac_result[$offset + 2] & 0xff) << 8 ) |
      ( $hmac_result[$offset + 3] & 0xff)
    ) % pow ( 10, $size))
    {
      return true;
    }
  }

  return false;
}

/**
 * Function to generate a RFC6238 (TOTP) authentication URI.
 *
 * @param $username string The user name.
 * @param $domain string The user domain.
 * @param $secret string The user secret.
 * @param $issuer string The authentication issuer.
 * @return string An TOTP authentication URI.
 */
function rfc6238_uri ( $username, $domain, $secret, $issuer)
{
  return "otpauth://totp/" . rawurlencode ( $username) . "@" . rawurlencode ( $domain) . "?secret=" . rawurlencode ( $secret) . "&issuer=" . rawurlencode ( $issuer);
}

/**
 * Function to generate a RFC4226 (HOTP) code.
 *
 * @param string $secret The base32-encoded authentication secret.
 * @param int $counter The moving factor (counter).
 * @param int $size[optional] The size of code, typically 6 or 8 digits.
 * @return string The generated HOTP code.
 */
function rfc4226_generate ( $secret, $counter, $size = 6)
{
  $secret = base32_decode ( $secret);

  // Convert counter to 8-byte binary string (big endian):
  $bin_counter = "";
  for ( $i = 7; $i >= 0; $i--)
  {
    $bin_counter .= chr ( ( $counter >> ( $i * 8)) & 0xff);
  }

  // Calculate HMAC-SHA1:
  $hmac = hash_hmac ( "sha1", $bin_counter, $secret, true);

  // Dynamic truncation:
  $offset = ord ( $hmac[19]) & 0x0f;

  // Return HOTP value:
  return sprintf ( "%0" . $size . "d", (
           ( ( ord ( $hmac[$offset]) & 0x7f) << 24) |
           ( ( ord ( $hmac[$offset + 1]) & 0xff) << 16) |
           ( ( ord ( $hmac[$offset + 2]) & 0xff) << 8) |
           ( ord ( $hmac[$offset + 3]) & 0xff)
         ) % pow ( 10, $size));
}

/**
 * Function to validate a RFC4226 (HOTP) user code.
 *
 * @param string $secret The base32-encoded authentication secret.
 * @param string|int $code The user-provided HOTP code.
 * @param int $counter The current counter value.
 * @param int $window[optional] Number of future counters to try (to allow for drift).
 * @param int $size[optional] The size of code, 6 or 8 digits.
 * @return int -1 if code is not valid, otherwise the counter matched.
 */
function rfc4226_validate ( $secret, $code, $counter, $window = 5, $size = 6)
{
  $secret = base32_decode ( $secret);

  for ( $i = 0; $i < $window; $i++)
  {
    $trial_counter = $counter + $i;

    // Convert counter to 8-byte binary string (big endian):
    $bin_counter = "";
    for ( $j = 7; $j >= 0; $j--)
    {
      $bin_counter .= chr ( ( $trial_counter >> ( $j * 8)) & 0xff);
    }

    // HMAC-SHA1:
    $hmac = hash_hmac ( "sha1", $bin_counter, $secret, true);

    // Dynamic truncation
    $offset = ord ( $hmac[19]) & 0x0f;
    $calculated_code = (
      ( ( ord ( $hmac[$offset]) & 0x7f) << 24) |
      ( ( ord ( $hmac[$offset + 1]) & 0xff) << 16) |
      ( ( ord ( $hmac[$offset + 2]) & 0xff) << 8) |
      ( ord ( $hmac[$offset + 3]) & 0xff)
    ) % pow ( 10, $size);

    if ( (int) $code === $calculated_code)
    {
      return $trial_counter;
    }
  }

  return -1;
}

/**
 * Function to generate a RFC4226 (HOTP) authentication URI.
 *
 * @param $username string The user name.
 * @param $domain string The user domain.
 * @param $secret string The user secret.
 * @param $issuer string The authentication issuer.
 * @return string An HOTP authentication URI.
 */
function rfc4226_uri ( $username, $domain, $secret, $issuer)
{
  return "otpauth://hotp/" . rawurlencode ( $username) . "@" . rawurlencode ( $domain) . "?secret=" . rawurlencode ( $secret) . "&issuer=" . rawurlencode ( $issuer);
}

/**
 * Function to search for a substring inside an array.
 *
 * @param $needle string The string to search for.
 * @param $haystack array The array to search in.
 * @return boolean True if substring was found in any array value, otherwise false.
 */
function array_search_substring ( $needle, $haystack)
{
  foreach ( $haystack as $value)
  {
    if ( strpos ( $value, $needle) !== false)
    {
      return true;
    }
  }
  return false;
}
?>
