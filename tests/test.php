<?php
ini_set( 'display_errors', '1' );

require_once( '../src/fnFittedTextHeight.php' );
require_once( '../src/fCenterText.php' );
require_once( '../src/fCenterTextLines.php' );
require_once( '../src/fiFitImage.php' );
require_once( '../src/fiLoadOrientedImage.php' );

//  REQUIRED AND OPTIONAL QUERY STRING PARAMETERS:

Aponica\AssertProperties\fAssertProperties(
  $_GET, [ 'nLines', 'nH', 'nW' ], [ 'zLabel' => 'URL' ] );

$bMargins = array_key_exists( 'bMargins', $_GET );

$nSpace = array_key_exists( 'nSpace', $_GET ) ?
  floatval( $_GET[ 'nSpace' ] ) : 0;


//  Go to town.

$iImage = Opplaud\ImageUtils\fiLoadOrientedImage( 'vonnegut.jpg' );
$iImage = Opplaud\ImageUtils\fiFitImage(
  intval( $_GET[ 'nW' ] ), intval( $_GET[ 'nH' ] ), $iImage );

$nW = imagesx( $iImage );
$nH = imagesy( $iImage );

$zAmadeus = __DIR__ . '/Amadeus.ttf';

$avAllLines = [
  '[y}', // 0
  [ 'nRelativeHeight' => 0.5,
    'zText' => '[g}' ],
  [ 'nRelativeHeight' => 2,
    'zText' => '[3}' ],
  [ 'zFontFileName' => $zAmadeus,
    'zText' => '[Line 4}' ],
  '[FIFTH LINE MANy CAPPED WORDS}', // 4
  [ 'nRelativeHeight' => 0.5,
    'zText' => '[Sixth Line Half Syzed}' ],
  [ 'zFontFileName' => $zAmadeus,
    'nRelativeHeight' => 2,
    'zText' => '[7=Double-Syze}' ],
  '[Lyne Eight}' // 7
  ];

$aiColors = [
  imagecolorallocate( $iImage, 0, 255, 0 ),
  imagecolorallocate( $iImage, 0, 0, 255 ),
  imagecolorallocate( $iImage, 255, 255, 0 ),
  imagecolorallocate( $iImage, 255, 0, 255 ),
  imagecolorallocate( $iImage, 0, 255, 255 )
  ];

$nLines = intval( $_GET[ 'nLines' ] );

$avFiltered = array_filter(
  $avAllLines,
  function( $k ) use ( $nLines ) { return $k < $nLines; },
  ARRAY_FILTER_USE_KEY
  );

$n = 0;
$avUseLines = [];
foreach ( $avFiltered as $v ) { // $v
  if ( is_array( $v ) )
    $avUseLines[] = array_merge( [ 'iColor' => $aiColors[ $n++ ] ], $v );
  else
    $avUseLines[] = $v;
  } // $v

if ( 3.5 < count( $avUseLines ) )
  $avUseLines[ 2 ][ 'hOutline' ] =
    [ 'iColor' => imagecolorallocate( $iImage, 255, 255, 255 ) ];

if ( 4.5 < count( $avUseLines ) )
  $avUseLines[ 3 ][ 'hOutline' ] = [
    'iColor' => imagecolorallocate( $iImage, 0, 0, 0 ),
    'nThickness' => 5 ];

$nSpace = round(
  array_key_exists( 'nSpace', $_GET ) ? floatval( $_GET[ 'nSpace' ] ) : 1, 2 );
$avUseLines[ 0 ] .= $nSpace;

Opplaud\ImageUtils\fCenterTextLines(
[ // hParams
  'iImage' => $iImage,
  'avLines' => $avUseLines
  ], // hParams
[ // hOptions
  //'nBottom' => 0.8 * $nH,
  //'bDebug' => true,
  'zFontFileName' => __DIR__ . '/MontserratRegular.ttf',
  //'iColor' => imagecolorallocate( $iImage, 255, 0, 0 ),
  //'nLeft' => 0.2 * $nW,
  'bMargins' => $bMargins,
  //'hOutline' => [ 'iColor' => imagecolorallocate( $iImage, 255, 127, 0 ) ],
  //'nRight' => 0.8 * $nW,
  'nSpace' => $nSpace,
  //'nTop' => 0.2 * $nH
  ] // hOptions
); // fCenterTextLines()

header("Content-type: image/jpeg");
imagejpeg( $iImage );
imagedestroy( $iImage );

// EOF
