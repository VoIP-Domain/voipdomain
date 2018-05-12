/**
 *            phonetizer v1.0
 * by Ernani Azevedo <ernaniaz@gmail.com>
 *
 * @name        phonetizer
 * @description A function to get the phonetic version of a text.
 * @version     1.0
 * @author      Ernani Azevedo <ernaniaz@gmail.com>
 * @license     GPLv3
 */

/**
 * History:
 *
 * v1.0 - Released Jul/21/2017:
 * - First release
 */

/**
 * Function to return phonetic form of a text. The text must be UTF-8 encoded.
 *
 * @param string text
 * @return string
 */
function phonetizer ( text)
{
  // Trim and get uppercase text variable:
  text = text.toUpperCase ().trim ();

  // Remove name prepositions and extra spaces:
  text = text.replace ( /( |^)(DEL|DA|DE|DI|DO|DU|DAS|DOS|DEU|DER|E|LA|LE|LES|LOS|VAN|VON|EL)( |$)/i, ' ');

  // Remove diacritics:
  var diacritics = [
    /[\300-\306]/g, /[\340-\346]/g,	// A, a
    /[\310-\313]/g, /[\350-\353]/g,	// E, e
    /[\314-\317]/g, /[\354-\357]/g,	// I, i
    /[\322-\330]/g, /[\362-\370]/g,	// O, o
    /[\331-\334]/g, /[\371-\374]/g,	// U, u
    /[\321]/g, /[\361]/g,		// N, n
    /[\307]/g, /[\347]/g		// C, c
  ];
  var chars = [ 'A', 'a', 'E', 'e', 'I', 'i', 'O', 'o', 'U', 'u', 'N', 'n', 'C', 'c'];
  for ( var i = 0; i < diacritics.length; i++)
  {
    text = text.replace ( diacritics[i], chars[i]);
  }

  // Remove all characters other than A-Z, 0-9, &, _ or space:
  text = text.replace ( /[^A-Za-z0-9&_ ]/, '');

  // Remove duplicated characters, except for SS (more than 2 S's become SS) and numbers:
  last = ' ';
  output = "";
  for ( var i = 0; i < text.length; i++)
  {
    if ( text[i] != last || text[i] == ' ' || ( text[i] >= 0 && text[i] <= 9) || ( text[i] == 'S' && text[i - 1] == 'S' && i > 1 && text[i - 2] != 'S'))
    {
      output += text[i];
    }
    last = text[i];
  }

  // Explode words to process:
  words = output.split ( / /);

  // Process each word:
  for ( var w = 0; w < words.length; w++)
  {
    // Get word and add space:
    word = words[w] + ' ';

    // Reset auxiliary variables:
    var output = [];
    var fonfon = [];
    var fonaux = [];
    var j = 0;

    // If word has only one character:
    if ( word.length == 1)
    {
      switch ( word[0])
      {
        case '_':	// _ -> space
          output[0] = ' ';
          break;
        case 'E':	// E -> i
        case '&':	// & -> i
        case 'I':	// I -> i
          output[0] = 'i';
          break;
        default:	// Character is not modified
          output[0] = word[0];
          break;
      }
    } else {
      // Word has more than one character. Process current word, character to character:
      for ( var i = 0; i < word.length; i++)
      {
        switch ( word[i])
        {
          case '_':	// _ -> Y
            fonfon[i] = 'Y';
            break;
          case '&':	// & -> i
          case 'E':	// E -> i
          case 'Y':	// Y -> i
          case 'I':	// I -> i
            fonfon[i] = 'i';
            break;
          case 'O':	// O -> o
          case 'U':	// U -> o
            fonfon[i] = 'o';
            break;
          case 'A':	// A -> a
            fonfon[i] = 'a';
            break;
          case 'S':	// S -> s
            fonfon[i] = 's';
            break;
          default:	// Unidentified character
            fonfon[i] = word[i];
            break;
        }
      }
      endfon = 0;
      fonaux = fonfon;

      // Words formed by only 3 consonants are dispensed from the phonetization process:
      if ( fonaux.length == 3)
      {
        if ( fonaux[0] == 'a' || fonaux[0] == 'i' || fonaux[0] == 'o')
        {
          endfon = 0;
        } else {
          if ( fonaux[1] == 'a' || fonaux[1] == 'i' || fonaux[1] == 'o')
          {
            endfon = 0;
          } else {
            if ( fonaux[2] == 'a' || fonaux[2] == 'i' || fonaux[2] == 'o')
            {
              endfon = 0;
            } else {
              endfon = 1;
              output[0] = fonaux[0];
              output[1] = fonaux[1];
              output[2] = fonaux[2];
            }
          }
        }
      }

      // If the word is not formed by only 3 consonants:
      if ( endfon != 1)
      {
        // Process the current word, character to character:
        for ( var i = 0; i < fonaux.length - 1; i++)
        {
          // Reset control variables:
          copfon = 0;
          copmud = 0;
          newmud = 0;

          switch ( fonaux[i])
          {
            case 'a':	// If the character is an "a"
              // If the word ends with As, AZ, AM, or AN, eliminate the consonant at the end of the word:
              if ( fonaux[i + 1] == 's' || fonaux[i + 1] == 'Z' || fonaux[i + 1] == 'M' || fonaux[i + 1] == 'N')
              {
                if ( fonaux[i + 2] != ' ')
                {
                  copfon = 1;
                } else {
                  output[j] = 'a';
                  output[j + 1] = ' ';
                  j++;
                  i++;
                }
              } else {
                copfon = 1;
              }
              break;

            case 'B':	// If the character is a "B", it's not modified
              copmud = 1;
              break;

            case 'C':	// If the character is a "C"
              // Ci -> si
              if ( fonaux[i + 1] == 'i')
              {
                output[j] = 's';
                j++;
                break;
              }
 
              // Cois (end) -> Kao
              if ( fonaux[i + 1] == 'o' && fonaux[i + 2] == 'i' && fonaux[i + 3] == 's' && fonaux[i + 4] == ' ')
              {
                output[j] = 'K';
                output[j + 1] = 'a';
                output[j + 2] = 'o';
                i += 4;
                break;
              }
 
              // CT -> T
              if ( fonaux[i + 1] == 'T')
              {
                break;
              }
 
              // C -> K
              if ( fonaux[i + 1] != 'H')
              {
                output[j] = 'K';
                newmud = 1;
 
                // CK -> K
                if ( fonaux[i + 1] == 'K')
                {
                  i++;
                }
                break;
              }
 
              // CH -> K to CHi (end), CHi (vowel), CHiNi (end) and CHiTi (end)
              // CHi (end) or CHi (vowel)
              x = 0;
              if ( fonaux[i + 1] == 'H')
              {
                if ( fonaux[i + 2] == 'i')
                {
                  if ( fonaux[i + 3] == 'a' || fonaux[i + 3] == 'i' || fonaux[i + 3] == 'o')
                  {
                    x = 1;
                  } else {
                    // CHiNi (end) or CHiTi (end)
                    if (( fonaux[i + 3] == 'N' || fonaux[i + 3] == 'T') && fonaux[i + 4] == 'i' && fonaux[i + 5] == ' ')
                    {
                      x = 1;
                    }
                  }
                }
              }

              if ( x == 1)
              {
                output[j] = 'K';
                j++;
                i++;
                break;
              }
 
              // CHi, not CHi (end), CHi (vowel), CHiNi (end) or CHiTi (end)
              // CH not followed by i
              // If previous is not s, CH -> X

              // sCH: phoneme retreats one position
              if ( j > 0 && output[j - 1] == 's')
              {
                j--;
              }

              output[j] = 'X';
              newmud = 1;
              i++;
              break;

            case 'D':	// If the character is a "D"
              // Search for DoR
              x = 0;
              if ( fonaux[i + 1] != 'o')
              {
                copmud = 1;
                break;
              } else {
                if ( fonaux[i + 2] == 'R')
                {
                  if ( i != 0)
                  {
                    x = 1;		// DoR (not at begin)
                  } else {
                    copfon = 1;		// DoR (begin)
                  }
                } else {
                  copfon = 1;		// It's not DoR
                }
              }

              if ( x == 1)
              {
                if ( fonaux[i + 3] == 'i')
                {
                  if ( fonaux[i + 4] == 's')	// dores
                  {
                    if ( fonaux[i + 5] != ' ')
                    {
                      x = 0;		// It's not dores
                    }
                  } else {
                    x = 0;
                  }
                } else {
                  if ( fonaux[i + 3] == 'a')
                  {
                    if ( fonaux[i + 4] != ' ')
                    {
                      if ( fonaux[i + 4] != 's')
                      {
                        x = 0;
                      } else {
                        if ( fonaux[i + 5] != ' ')
                        {
                          x = 0;
                        }
                      }
                    }
                  } else {
                    x = 0;
                  }
                }
              } else {
                x = 0;
              }

              if ( x == 1)
              {
                output[j] = 'D';
                output[j + 1] = 'o';
                output[j + 2] = 'R';
                i += 5;
              } else {
                copfon = 1;
              }
              break;

            case 'F':	// If the character is a "F", it's not modified
              copmud = 1;
              break;

            case 'G':	// If the character is a "G"
              // Gui -> Gi
              if ( fonaux[i + 1] == 'o')
              {
                if ( fonaux[i + 2] == 'i')
                {
                  output[j] = 'G';
                  output[j + 1] = 'i';
                  j += 2;
                  i +=2;
                } else {
                  // If not Gui, copy as a mute consonant
                  copmud = 1;
                }
              } else {
                // GL
                if ( fonaux[i + 1] == 'L')
                {
                  if ( fonaux[i + 2] == 'i')
                  {
                    //gli + vowel -> li + vowel
                    if ( fonaux[i + 3] == 'a' || fonaux[i + 3] == 'i' || fonaux[i + 3] == 'o')
                    {
                      output[j] = fonaux[i + 1];
                      output[j + 1] = fonaux[i + 2];
                      j += 2;
                      i += 2;
                    } else {
                      //glin -> lin
                      if ( fonaux[i + 3] == 'N')
                      {
                        output[j] = fonaux[i + 1];
                        output[j + 1] = fonaux[i + 2];
                        j += 2;
                        i += 2;
                      } else {
                        copmud = 1;
                      }
                    }
                  } else {
                    copmud = 1;
                  }
                } else {
                  // GN + vowel -> Ni + vowel
                  if ( fonaux[i + 1] == 'N')
                  {
                    if ( fonaux[i + 2] != 'a' && fonaux[i + 2] != 'i' && fonaux[i + 2] != 'o')
                    {
                      copmud = 1;
                    } else {
                      output[j] = 'N';
                      output[j + 1] = 'i';
                      j += 2;
                      i++;
                    }
                  } else {
                    // GHi -> Gi
                    if ( fonaux[i + 1] == 'H')
                    {
                      if ( fonaux[i + 2] == 'i')
                      {
                        output[j] = 'G';
                        output[j + 1] = 'i';
                        j += 2;
                        i +=2;
                      } else {
                        copmud = 1;
                      }
                    } else {
                      copmud = 1;
                    }
                  }
                }
              }
              break;

            case 'H':	// If the character is a "H", it's discarted
              break;

            case 'i':	// If the character is an "i"
              if ( fonaux[i + 2] == ' ')
              {
                // is or iZ end loses consonant
                if ( fonaux[i + 1] == 's' || fonaux[i + 1] == 'Z')
                {
                  output[j] = 'i';
                  break;
                }
              }

              // iX
              if ( fonaux[i + 1] != 'X')
              {
                copfon = 1;
              } else {
                if ( i != 0)
                {
                  copfon = 1;
                } else {
                  // vowel iX at begin switch to iZ
                  if ( fonaux[i + 2] == 'a' || fonaux[i + 2] == 'i' || fonaux[i + 2] == 'o')
                  {
                    output[j] = 'i';
                    output[j + 1] = 'Z';
                    j += 2;
                    i++;
                    break;
                  } else {
                    // consonant ix at begin switch to is
                    if ( fonaux[i + 2] == 'C' || fonaux[i + 2] == 's')
                    {
                      output[j] = 'i';
                      j++;
                      i++;
                    } else {
                      output[j] = 'i';
                      output[j + 1] = 's';
                      j += 2;
                      i++;
                    }
                    break;
                  }
                }
              }
              break;

            case 'J':	// If the character is a "J"
              // J -> Gi
              output[j] = 'G';
              output[j + 1] = 'i';
              j += 2;
              break;
 
            case 'K':	// If the character is a "K"
              // KT -> T
              if ( fonaux[i + 1] != 'T')
              {
                copmud = 1;
              }
              break;
 
            case 'L':	// If the character is a "L"
              // L + vowel it's not modified
              if ( fonaux[i + 1] == 'a' || fonaux[i + 1] == 'i' || fonaux[i + 1] == 'o')
              {
                copfon = 1;
              } else {
                // L + consonant -> U + consonant
                if ( fonaux[i + 1] != 'H')
                {
                  output[j] = 'o';
                  j++;
                  break;
                } else {
                  // LH + consonant it's not modified
                  if ( fonaux[i + 2] != 'a' && fonaux[i + 2] != 'i' && fonaux[i + 2] != 'o')
                  {
                    copfon = 1;
                  } else {
                    // LH + vowel -> LI + vowel
                    output[j] = 'L';
                    output[j + 1] = 'i';
                    j += 2;
                    i++;
                    break;
                  }
                }
              }
              break;

            case 'M':	// If the character is a "M"
              // M + consonant -> N + consonant
              // M end -> N
              if (( fonaux[i + 1] != 'a' && fonaux[i + 1] != 'i' && fonaux[i + 1] != 'o') || fonaux[i + 1] == ' ')
              {
                output[j] = 'N';
                j++;
              } else {
                // Everything else is not modified
                copfon = 1;
              }
              break;

            case 'N':	// If the character is a "N"
              // NGT -> NT
              if ( fonaux[i + 1] == 'G' && fonaux[i + 2] == 'T')
              {
                fonaux[i + 1] = 'N';
                copfon = 1;
              } else {
                // NH + consonant it's not modified
                if ( fonaux[i + 1] == 'H')
                {
                  if ( fonaux[i + 2] != 'a' && fonaux[i + 2] != 'i' && fonaux[i + 2] != 'o')
                  {
                    copfon = 1;
                  } else {
                    // NH + vowel -> Ni + vowel
                    output[j] = 'N';
                    output[j + 1] = 'i';
                    j += 2;
                    i++;
                  }
                } else {
                  copfon = 1;
                }
              }
              break;

            case 'o':	// If the character is an "o"
              // os (end) -> o and oZ (end) -> o
              if (( fonaux[i + 1] == 's' || fonaux[i + 1] == 'Z') && fonaux[i + 2] == ' ')
              {
                output[j] = 'o';
              } else {
                copfon = 1;
              }
              break;

            case 'P':	// If the character is a "P"
              // PH -> F
              if ( fonaux[i + 1] == 'H')
              {
                output[j] = 'F';
                i++;
                newmud = 1;
              } else {
                copmud = 1;
              }
              break;
 
            case 'Q':	// If the character is a "Q"
              // Koi -> Ki (QUE, QUI -> KE, KI)
              if ( fonaux[i + 1] == 'o' && fonaux[i + 2] == 'i')
              {
                output[j] = 'K';
                j++;
                i++;
                break;
              }

              // QoA -> KoA (QUA -> KUA)
              output[j] = 'K';
              j++;
              break;

            case 'R':   // If the character is a "R", it's not modified
              copfon = 1;
              break;

            case 's':	// If the character is a "s"
              // s at end is ignored
              if ( fonaux[i + 1] == ' ')
              {
                break;
              }
 
              // s at begin + vowel it's not modified
              if ( fonaux[i + 1] == 'a' || fonaux[i + 1] == 'i' || fonaux[i + 1] == 'o')
              {
                if ( i == 0)
                {
                  copfon = 1;
                  break;
                } else {
                  // s between two vowels -> z
                  if ( fonaux[i - 1] != 'a' && fonaux[i - 1] != 'i' && fonaux[i - 1] != 'o')
                  {
                    copfon = 1;
                    break;
                  } else {
                    // soL it's not modified
                    if ( fonaux[i + 1] == 'o' && fonaux[i + 2] == 'L' && fonaux[i + 3] == ' ')
                    {
                      copfon = 1;
                    } else {
                      output[j] = 'Z';
                      j++;
                    }
                    break;
                  }
                }
              }
 
              // ss -> s
              if ( fonaux[i + 1] == 's')
              {
                if ( fonaux[i + 2] != ' ')
                {
                  copfon = 1;
                  i++;
                } else {
                  fonaux[i + 1] = ' ';
                }
                break;
              }
 
              // s at begin followed by consonant is preceded by i
              // If it's not sCi, sH or sCH not followed by vowel
              if ( i == 0)
              {
                if ( ! ( fonaux[i + 1] == 'C' && fonaux[i + 2] == 'i'))
                {
                  if ( fonaux[i + 1] != 'H')
                  {
                    if ( ! ( fonaux[i + 1] == 'C' && fonaux[i + 2] == 'H' && ( fonaux[i + 3] != 'a' && fonaux[i + 3] != 'i' && fonaux[i + 3] != 'o')))
                    {
                      output[j] = 'i';
                      j++;
                      copfon = 1;
                      break;
                    }
                  }
                }
              }
 
              // sH -> X;
              if ( fonaux[i + 1] == 'H')
              {
                output[j] = 'X';
                i++;
                newmud = 1;
                break;
              }
              if ( fonaux[i + 1] != 'C')
              {
                copfon = 1;
                break;
              }
 
              // sCh not followed by i switch to X
              if ( fonaux[i + 2] == 'H')
              {
                output[j] = 'X';
                i += 2;
                newmud = 1;
                break;
              }
              if ( fonaux[i + 2] != 'i')
              {
                copfon = 1;
                break;
              }

              // sCi at end -> Xi
              if ( fonaux[i + 3] == ' ')
              {
                output[j] = 'X';
                output[j + 1] = 'i';
                i += 3;
                break;
              }
 
              // sCi at end -> Xi
              if ( fonaux[i + 3] == ' ')
              {
                output[j] = 'X';
                output[j + 1] = 'i';
                i += 3;
                break;
              }
 
              // sCi vowel -> X
              if ( fonaux[i + 3] == 'a' || fonaux[i + 3] == 'i' || fonaux[i + 3] == 'o')
              { 
                output[j] = 'X';
                j++;
                i += 2;
                break;
              }
 
              // sCi consonant -> si
              output[j] = 's';
              output[j + 1] = 'i';
              j += 2;
              i += 2;
              break;
 
            case 'T':	// If the character is a "T"
              // Ts -> s, TZ -> Z
              if ( fonaux[i + 1] == 's' || fonaux[i + 1] == 'Z')
              {
                break;
              }
              copmud = 1;
              break;
 
            case 'V':	// If the character is a "V"
            case 'W':	// Or if the character is a "W"
              // V or W at begin + vowel -> o + vowel (U + vowel)
              if ( fonaux[i + 1] == 'a' || fonaux[i + 1] == 'i' || fonaux[i + 1] == 'o')
              {
                if ( i == 0)
                {
                  output[j] = 'o';
                  j++;
                } else {
                  // V or W not at begin + vowel -> V + vowel
                  output[j] = 'V';
                  newmud = 1;
                }
              } else {
                output[j] = 'V';
                newmud = 1;
              }
              break;
 
            case 'X':	// If the character is a "X", it's not modified
              copmud = 1;
              break;
 
            case 'Y':	// If the character is a "Y", it's already processed above
              break;
 
            case 'Z':	// If the character is a "Z"
              // Z at end it's discarted
              if ( fonaux[i + 1] == ' ')
              {
                break;
              } else {
                // Z + vowel it's not modified
                if ( fonaux[i + 1] == 'a' || fonaux[i + 1] == 'i' || fonaux[i + 1] == 'o')
                {
                  copfon = 1;
                } else {
                  // Z + consonant -> S + consonant
                  output[j] = 's';
                  j++;
                }
              }
              break;
 
            default:	// If the character is not one of the processed above, it's not modified
              output[j] = fonaux[i];
              j++;
              break;
          }

          // Copy current character
          if ( copfon == 1)
          {
            output[j] = fonaux[i];
            j++;
          }
 
          // Insertion of i after mute consonant
          if ( copmud == 1)
          {
            output[j] = fonaux[i];
          }

          if ( copmud == 1 || newmud == 1)
          {
            j++;
            k = 0;
            while ( k == 0)
            {
              if ( fonaux[i + 1] == ' ')
              {
                // It's a mute end
                output[j] = 'i';
                k = 1;
              } else {
                if ( fonaux[i + 1] == 'a' || fonaux[i + 1] == 'i' || fonaux[i + 1] == 'o')
                {
                  k = 1;
                } else {
                  if ( output[j - 1] == 'X')
                  {
                    output[j] = 'i';
                    j++;
                    k = 1;
                  } else {
                    if ( fonaux[i + 1] == 'R' || fonaux[i + 1] == 'L')
                    {
                      k = 1;
                    } else {
                      if ( fonaux[i + 1] != 'H')
                      {
                        output[j] = 'i';
                        j++;
                        k = 1;
                      } else {
                        i++;
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

    // Process all the word, character to character:
    for ( var i = 0; i < output.length; i++)
    {
      switch ( output[i])
      {
        case 'i':	// i -> I
          output[i] = 'I';
          break;
        case 'a':	// a -> A
          output[i] = 'A';
          break;
        case 'o':	// o -> U
          output[i] = 'U';
          break;
        case 's':	// s -> S
          output[i] = 'S';
          break;
        case 'E':	// E -> space
          output[i] = ' ';
          break;
        case 'Y':	// Y -> _
          output[i] = '_';
          break;
      }
    }

    // Return the word, modified, to the variable that contains the text:
    words[w] = output.join ( '');

    // Reset the counter
    j = 0;
  }

  // Join the words into a phase again:
  text = words.join ( ' ');

  // Remove duplicate characters, except for SS (more then 2 S's become SS) and numbers:
  last = ' ';
  output = '';
  for ( var i = 0; i < text.length; i++)
  {
    if ( text[i] != last || text[i] == ' ' || ( text[i] >= '0' && text[i] <= '9') || ( text[i] == 'S' && text[i - 1] == 'S' && i > 1 && text[i - 2] != 'S'))
    {
      output += text[i];
    }
    last = text[i];
  }

  // Remove excess space, capitalizes and return result:
  return output.toUpperCase ().trim ();
}
