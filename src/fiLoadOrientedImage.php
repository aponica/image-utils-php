<?php declare(strict_types=1);
//=============================================================================
//  Copyright 2018-2022 Opplaud LLC and other contributors. MIT licensed.
//=============================================================================

namespace Aponica\ImageUtils;

use Exception;
use GdImage;

//--------------------------------------------------------------------------
/// Returns a (potentially rotation-corrected) JPEG image.
///
/// @param $zImgPath
///   The image file name.
///
/// @returns GdImage:
///   The (potentially rotation-corrected) image.
///
/// @thows Exception
//--------------------------------------------------------------------------

function fiLoadOrientedImage( string $zImgPath ) : GdImage {

  $iImage = imagecreatefromjpeg( $zImgPath );

  $hExif = exif_read_data( $zImgPath );

  if ( ! empty( $hExif[ 'Orientation' ] ) )
    switch ($hExif['Orientation']) {
      case 3: $iImage = imagerotate( $iImage, 180, 0 ); break;
      case 6: $iImage = imagerotate( $iImage, -90, 0 ); break;
      case 8: $iImage = imagerotate( $iImage, 90, 0 ); break;
      }

  return $iImage;

  } // fiLoadOrientedImage

// EOF
