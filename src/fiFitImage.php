<?php declare(strict_types=1);
//=============================================================================
//  Copyright 2018-2022 Opplaud LLC and other contributors. MIT licensed.
//=============================================================================

namespace Aponica\ImageUtils;

use GdImage;

//--------------------------------------------------------------------------
/// Scales and/or crops a JPEG image to fit specified dimensions.
///
/// The image is scaled to best fit the desired dimensions, then cropped
/// as necessary to achieve the correct aspect ratio.
///
/// @param $nWidth
///   The desired width of the image.
///
/// @param $nHeight
///   The desired height of the image.
///
/// @param $iImage
///   The GdImage.
///
/// @param $hOptions
///   A hash (associative array) of options, possibly including:
///
///     * ['nCropOffset'] =>
///       A positive or negative percentage to shift the cropping of the
///       image. If the image is cropped on the left and right, then a
///       negative value shifts the cropping left (so more of the right
///       side is removed) and a positive value shifts it right. If the
///       image is cropped on the top and bottom, then a negative value
///       shifts the cropping up (so more of the bottom is removed) and
///       a positive value shifts it down. A value of `-100` or less shifts
///       the cropping to the starting edge (left or top), and a value of
///       `100` or more shifts it to the far edge (right or bottom).
///
/// @returns GdImage:
///   The new image.
//--------------------------------------------------------------------------

function fiFitImage( int $nWidth, int $nHeight,
  GdImage $iImage, array $hOptions = [] ) : GdImage {

    $hSettings = array_merge(
      [ // defaults
        'nCropOffset' => 0
        ], // defaults
      $hOptions
      ); // array_merge()

    $nShiftPct =
      ( -99.5 > $hSettings[ 'nCropOffset' ] ) ? -1 :
        ( ( 99.5 < $hSettings[ 'nCropOffset' ] ) ? 1 :
          ( $hSettings[ 'nCropOffset' ] / 100 ) );


    //  If the difference between the actual width and target width is
    //  greater than the difference in heights, then the width is scaled
    //  and the height is cropped; otherwise, vice-versa.

    $nW = imagesx( $iImage );
    $nH = imagesy( $iImage );

    $nShiftX = 0;
    $nShiftY = 0;

    if ( ( $nWidth - $nW ) > ( ( $nHeight - $nH ) * ( $nWidth / $nHeight ) ) )
      { // scale width

      $nTempHeight = intval( $nWidth * ( $nH / $nW ) );

      $iImage = imagescale( $iImage, $nWidth, $nTempHeight );

      $nShiftY = intval( ( ( $nTempHeight - $nHeight ) / 2 ) * $nShiftPct );

      $nH = imagesy( $iImage );

      $nW = $nWidth;

      } // scale width

    else { // scale height

      $nTempWidth = intval( $nHeight * ( $nW / $nH ) );

      $iImage = imagescale( $iImage, $nTempWidth , $nHeight );

      $nW = imagesx( $iImage );

      $nShiftX = intval( ( ( $nTempWidth - $nWidth ) / 2 ) * $nShiftPct );

      $nH = $nHeight;

      } // scale height


    //  The center of the image is always maintained when cropping, unless
    //  offset by request.

    if ( ( $nW !== $nWidth ) || ( $nH !== $nHeight ) )
      $iImage = imagecrop( $iImage, [
        'x' => ( ( $nW - $nWidth ) / 2 ) + $nShiftX,
        'y' => ( ( $nH - $nHeight ) / 2 ) + $nShiftY,
        'width' => $nWidth,
        'height' => $nHeight
        ] ); // imagecrop()

    return $iImage;

  } // fiFitImage

// EOF
