<?php declare(strict_types=1);
//=============================================================================
//  Copyright 2018-2022 Opplaud LLC and other contributors. MIT licensed.
//=============================================================================

namespace Aponica\ImageUtils;

//-----------------------------------------------------------------------------
/// Returns the approximate height of the text fitted to given boundaries.
///
/// This is primarily for use by fCenterTextLines() but available for use
/// elsewhere.
///
/// @param $nMaxW
///   The maximum width of the text.
///
/// @param $nMaxH
///   The maximum height of the text.
///
/// @param $zFontFileName
///   The name of the font file, as expected by <a
///   href="https://www.php.net/manual/en/function.imagettfbbox.php"
///   >`imagettfbbox()`</a>.
///
/// @param $zText
///   The line of text to be fitted.
///
/// @returns number:
///   The approximate height (in pixels) required to fit the text into the
///   specified boundaries. For some typefaces, certain characters might still
///   exceed the reported size.
///
/// @see <a href="https://www.php.net/manual/en/function.imagettfbbox.php"
///   >`imagettfbbox()`</a>.
//-----------------------------------------------------------------------------

function fnFittedTextHeight(
  float $nMaxW, float $nMaxH, string $zFontFileName, string $zText ) : float {

  for ( $nHeight = $nMaxH / 1.2 ; $nHeight > 0 ; $nHeight -= 0.05 )
    { // shrink text until it fits

    $anBox = imagettfbbox( $nHeight, 0, $zFontFileName, "{[(#!?|/Wgy$zText])}" );
    $nHighest = max( $anBox[1] - $anBox[7], $anBox[3] - $anBox[5] );

    $anBox = imagettfbbox( $nHeight, 0, $zFontFileName, $zText );
    $nWidth = max( $anBox[4] - $anBox[6], $anBox[2] - $anBox[0] );

    if ( ( $nHighest < $nMaxH ) && ( $nWidth < $nMaxW ) )
      break;

    } // shrink text until it fits

  return $nHeight * 1.2 ; // pt->px

  } // fnFittedTextHeight

// EOF
