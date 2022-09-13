<?php declare(strict_types=1);
//=============================================================================
//  Copyright 2018-2022 Opplaud LLC and other contributors. MIT licensed.
//=============================================================================

namespace Aponica\ImageUtils;

use function Aponica\AssertProperties\fAssertProperties;

//-----------------------------------------------------------------------------
/// Draws the specified text on the image, centered on the given coordinates.
///
/// This is admittedly not done to an exact science.
///
/// @param $hParams
///   A hash (associative array) with these members:
///
///     * ['zFontFileName'] =>
///       The name of the font file, as per <a
///       href="https://www.php.net/manual/en/function.imagettftext.php"
///       >`imagettftext()`</a>.
///
///     * ['iImage'] =>
///       The image resource on which to draw the text.
///
///     * ['nHeight'] =>
///       The desired approximate line height (in pixels).
///
///     * ['zText'] =>
///       The text to be drawn on the image.
///
///     * ['nX'] =>
///       The X position over which the text should be centered.
///
///     * ['nY'] =>
///       The Y position over which the text should be centered.
///
/// @param $hOptions
///   A hash (associative array) that may contain:
///
///     * ['iColor'] =>
///       The color resource (created by <a
///       href="https://www.php.net/manual/en/function.imagecolorallocate.php"
///       >`imagecolorallocate()`</a>) to color the text. If not specified,
///       black (#000000) is used.
///
///     * ['hOutline'] =>
///       A hash (associative array) specifying a rudimentary outline for
///       the text, which slightly increases the overall size. By default,
///       no outline is applied. Members include:
///
///         * ['iColor'] =>
///           A color identifier (created by <a
///           href="https://www.php.net/manual/en/function.imagecolorallocate.php"
///           >`imagecolorallocate()`</a>) for the outline. This is required
///           and should not be the same as the text color.
///
///         * ['nThickness'] =>
///           The thickness of the outline. If this is too large a number
///           (depending on the text size) the outline will appear
///           broken, because it is created by offsetting the same text at
///           multiple locations behind the main text. The default is 1 (one).
//-----------------------------------------------------------------------------

function fCenterText( array $hParams, array $hOptions = [] ) : void {

  //  Verify the parameters and set some local variables for brevity.

  fAssertProperties( $hParams,
    [ 'zFontFileName', 'iImage',  'nHeight', 'zText', 'nX', 'nY' ],
    [ 'zLabel' => 'Aponica\ImageUtils\fCenterText: hParams' ]
    );

  $zFont = $hParams[ 'zFontFileName' ];
  $iImage = $hParams[ 'iImage' ];
  $nPointSize = $hParams[ 'nHeight' ] * 0.75; // slightly small b/c fonts lie!
  $zText = $hParams[ 'zText' ];


  //  Compute the text dimensions and position. Typefaces typically have
  //  more space below the text than above, so the baseline is lowered to
  //  make the vertical alignment appear more centered.

  $anBox = imagettfbbox( $nPointSize, 0, $zFont, $zText );
  $nLeft = intval(
    $hParams[ 'nX' ] - ( ( $anBox[2] - $anBox[6] ) / 2 ) );

  $anBox = imagettfbbox( $nPointSize, 0, $zFont, "{[y]}" ); // high/low
  $nBase = intval(
    $hParams[ 'nY' ] + ( ( $anBox[3] - $anBox[7] ) / 3.66 ) );


  //  Copy the options to the settings and initialize defaults.

  $hSettings = array_merge( [], $hOptions ); // duplicate

  if ( ! array_key_exists( 'iColor', $hSettings ) )
    $hSettings[ 'iColor' ] = imagecolorallocate( $iImage, 0, 0, 0 );


  //  If an outline is desired, create it by drawing the same text to the
  //  top-left, top-right, bottom-left and bottom-right of the main text,
  //  offset by the desired thickness and using the outline color.

  if ( array_key_exists( 'hOutline', $hOptions ) ) { // outline desired

    fAssertProperties( $hOptions[ 'hOutline' ],
      [ 'iColor' ],
      [ 'zLabel' => 'Aponica\ImageUtils\fCenterText: hOptions[hOutline]' ]
      );

    $nColor = $hSettings[ 'hOutline' ][ 'iColor' ];

    $nOff = ( array_key_exists( 'nThickness', $hSettings[ 'hOutline' ] ) ?
      $hSettings[ 'hOutline' ][ 'nThickness' ] : 1 );

    imagettftext( $iImage, $nPointSize, 0,
      $nLeft - $nOff, $nBase - $nOff, $nColor, $zFont, $zText );

    imagettftext( $iImage, $nPointSize, 0,
      $nLeft + $nOff, $nBase - $nOff, $nColor, $zFont, $zText );

    imagettftext( $iImage, $nPointSize, 0,
      $nLeft - $nOff, $nBase + $nOff, $nColor, $zFont, $zText );

    imagettftext( $iImage, $nPointSize, 0,
      $nLeft + $nOff, $nBase + $nOff, $nColor, $zFont, $zText );

    } // outline desired


  //  Finally, draw the desired text!

  imagettftext( $iImage, $nPointSize, 0, $nLeft, $nBase,
    $hSettings[ 'iColor' ], $zFont, $zText );

  } // fCenterText

// EOF
