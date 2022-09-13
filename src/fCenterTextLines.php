<?php declare(strict_types=1);
//=============================================================================
//  Copyright 2018-2022 Opplaud LLC and other contributors. MIT licensed.
//=============================================================================

namespace Aponica\ImageUtils;

use Exception;
use function Aponica\AssertProperties\fAssertProperties;

//-----------------------------------------------------------------------------
/// Draws specified lines of text on an image within given dimensions.
///
/// This is admittedly not done to an exact science.
///
/// @param $hParams
///   A hash (associative array) containing:
///
///     * ['iImage'] =>
///       The image resource on which to draw the text.
///
///     * ['avLines'] =>
///       An array of strings and/or nested hashes containing each
///       line to be drawn, from topmost to bottommost. If a member
///       is a hash, it contains:
///
///         * ['iColor'] =>
///           The (optional) color resource for this line as per
///           fCenterText() (overrides `$hOptions`).
///
///         * ['zFontFileName'] =>
///           The (optional) font for this line. If this is not specified
///           for a line and no font is specified in `$hOptions` (below), an
///           Exception will be thrown.
///
///         * ['hOutline'] =>
///           The (optional) outline information for this line as per
///           fCenterText() (overrides `$hOptions`).
///
///         * ['nRelativeHeight'] =>
///           The (optional) height of this line relative to the normal line
///           height (which is determined based on the number of lines and
///           the height of the shortest line). This should be a number from
///           0.1 to 2.0, with 1.0 being the normal line height and the
///           default value.
///
///         * ['zText'] =>
///           The (required) text to draw on this line.
///
///       Otherwise, the member is treated as the text to draw on the line.
///
/// @param $hOptions
///   A hash (associative array) of options, which may include:
///
///     * ['nBottom'] =>
///       The lowest position above which text should appear (defaults to
///       the image height).
///
///     * ['bDebug'] =>
///       If true, then a rectangle is drawn for each text line, dissected
///       horizontally and vertical by center lines (default false).
///
///     * ['iColor'] =>
///       The color resource as per fCenterText() (default black).
///
///     * ['zFontFileName'] =>
///       The font to use for lines that do not specify one. Although
///       passed with the options, this member is **required** unless
///       **every** member of $hParams['avLines'] includes its own
///       ['zFontFileName'] member.
///
///     * ['hOutline'] =>
///       The outline information as per fCenterText() (default none).
///
///     * ['nLeft'] =>
///       The leftmost position after which text should appear (default 0).
///
///     * ['bMargins'] =>
///       If true, then margins at least equal to the space between lines
///       are added above and below the lines (default false).
///
///     * ['nRight'] =>
///       The rightmost position before which text should appear
///       (defaults to the image width).
///
///     * ['nSpace'] =>
///       The desired percentage of space between lines (for example,
///       0.5 for 1/2 line) (defaults to 0 for single-spacing).
///
///     * ['nTop'] =>
///       The highest position below which text should appear (default 0).
//-----------------------------------------------------------------------------

function fCenterTextLines( array $hParams, array $hOptions = [] ) :void {

  //  Verify the parameters and initialize the settings from the options.

  fAssertProperties(
    $hParams,
    [ 'iImage',  'avLines' ],
    [ 'zLabel' => 'Aponica\ImageUtils\fCenterTextLines: hParams' ]
    ); // fAssertProperties()

  $avLines = $hParams[ 'avLines' ];
  $nLineCount = count( $avLines );

  if ( 0.5 > $nLineCount )
    return; // nothing to draw

  $hSettings = array_merge(
    [ // defaults
      'nBottom' => imagesy( $hParams[ 'iImage' ] ),
      'bDebug' => false,
      'nLeft' => 0,
      'iColor' => imagecolorallocate( $hParams[ 'iImage' ], 0, 0, 0 ),
      'bMargins' => false,
      'nRight' => imagesx( $hParams[ 'iImage' ] ),
      'nSpace' => 0,
      'nTop' => 0
      ], // defaults
    $hOptions
    ); // array_merge()


  //  How many relative lines are needed for the spacing between lines and,
  //  if applicable, the margins?

  $nLinesNeeded = $hSettings[ 'nSpace' ] *
    ( $nLineCount + ( $hSettings[ 'bMargins' ] ? 2 : 0 ) - 1 );


  //  For each line, validate the params and compute how many relative lines
  //  it will need based on its relative height, adding it to the total number
  //  of relative lines needed.

  for ( $n = 0 ; $n < $nLineCount ; $n++ ) { // each line

    if ( is_array( $avLines[ $n ] ) )
      fAssertProperties( $avLines[ $n ], [ 'zText' ],
        [ 'zLabel' =>
          "Aponica\\ImageUtils\\fCenterTextLines: hParams[avLines][$n]" ] );

    else // convert to array
      $avLines[ $n ] = [ 'zText' => "${avLines[$n]}" ];

    if ( ! array_key_exists( 'zFontFileName', $avLines[ $n ] ) )
      { // use default font

      if ( ! array_key_exists( 'zFontFileName', $hSettings ) )
        throw new Exception( 'Aponica\\ImageUtils\\fCenterTextLines: ' .
          '[zFontFileName] must be specified for either hOptions or ' .
          "hParams[avLines][$n]" );

      $avLines[ $n ][ 'zFontFileName' ] = $hSettings[ 'zFontFileName' ];

      } // use default font

    if ( ! array_key_exists( 'nRelativeHeight', $avLines[ $n ] ) )
      $avLines[ $n ][ 'nRelativeHeight' ] = 1;

    $nLinesNeeded += $avLines[ $n ][ 'nRelativeHeight' ];

    if ( ! array_key_exists( 'iColor', $avLines[ $n ] ) )
      $avLines[ $n ][ 'iColor' ] = $hSettings[ 'iColor' ];

    } // each line


  // Compute how much space is available, and the height of one line.

  $nMaxH = intval( $hSettings[ 'nBottom' ] - $hSettings[ 'nTop' ] );
  $nMaxW = intval( $hSettings[ 'nRight' ] - $hSettings[ 'nLeft' ] );

  $nLineHeight = ( $nMaxHeight = ( $nMaxH / $nLinesNeeded ) );


  //  For each line, compute the actual height that will fit within the
  //  allocated space. This might require that height of other lines be
  //  reduced to maintain proportion.

  for ( $n = 0 ; $n < $nLineCount ; $n++ ) { // calculate max size

    $hLine = $avLines[ $n ];
    $nRelativeH = $hLine[ 'nRelativeHeight' ];

    $nFittedH = fnFittedTextHeight( $nMaxW, ( $nMaxHeight * $nRelativeH ),
      $hLine[ 'zFontFileName' ], $hLine[ 'zText' ] );

    $nNormalH = $nFittedH / $nRelativeH;
    if ( $nLineHeight > $nNormalH )
      $nLineHeight = $nNormalH;

  } // calculate max size


  //  Compute how much space should appear between each line, and where
  //  the first line should appear.

  $nSpace = intval( $nLineHeight * $hSettings[ 'nSpace' ] );

  $nLineTop = intval( $hSettings[ 'nTop' ] +
    ( $hSettings[ 'bMargins' ] ? $nSpace : 0 ) +
    ( $nMaxH / 2 ) - ( $nLineHeight * $nLinesNeeded / 2 ) );


  //  Generate each line.

  for ( $n = 0 ; $n < $nLineCount ; $n++ ) {

    $hLine = $avLines[ $n ];
    $nHeight = intval( $nLineHeight * $hLine[ 'nRelativeHeight' ] );

    $nX = intval( $hSettings[ 'nLeft' ] + ( $nMaxW / 2 ) );
    $nH = intval( $hLine[ 'nRelativeHeight' ] * $nLineHeight );
    $nY = intval( $nLineTop + ( $nH / 2 ) );

    if ( $hSettings[ 'bDebug' ] ) { // draw lines

      imagerectangle( $hParams[ 'iImage' ], $hSettings[ 'nLeft' ], $nLineTop,
        $hSettings[ 'nRight' ], $nLineTop + $nHeight, $hLine[ 'iColor' ] );

      imageline( $hParams[ 'iImage' ], $hSettings[ 'nLeft' ], $nY,
        $hSettings[ 'nRight' ], $nY, $hLine[ 'iColor' ] );

      imageline( $hParams[ 'iImage' ], $nX, $nLineTop,
        $nX, ( $nLineTop + $nH ), $hLine[ 'iColor' ] );

      } // draw lines

    $hPassOptions = [ 'iColor' => $hLine[ 'iColor' ] ];
    if ( array_key_exists( 'hOutline', $hLine ) )
      $hPassOptions[ 'hOutline' ] = $hLine[ 'hOutline' ];
    else if ( array_key_exists( 'hOutline', $hSettings ) )
      $hPassOptions[ 'hOutline' ] = $hSettings[ 'hOutline' ];

    fCenterText(
      [ // hParams
        'zFontFileName' => $hLine[ 'zFontFileName' ],
        'iImage' => $hParams[ 'iImage' ],
        'nHeight' => $nHeight,
        'zText' => $hLine[ 'zText' ],
        'nX' => $nX,
        'nY' => $nY
        ], // hParams
      $hPassOptions
      ); // fCenterText()

    $nLineTop += $nHeight + $nSpace;

    } // for $n

  } // fCenterTextLines

// EOF
