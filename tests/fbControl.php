<?php declare(strict_types=1);
//=============================================================================
// Copyright 2019-2022 Opplaud LLC and other contributors. MIT licensed.
//=============================================================================

//-----------------------------------------------------------------------------
/// Saves an image as a control, or compares it to one.
///
/// @param $iImage
///   The GDImage to save or compare.
///
/// @param $bTest
///   If `true`, the image is saved to a temporary file and compared to the
///   control image, returning true if they are the same. If `false`, then
///   the image is saved as the control image.
///
/// @returns bool:
///   If the image is being saved as the control, this always returns `true`.
///   Otherwise, it returns whether the image matches the control.
///
/// @throws Exception
//-----------------------------------------------------------------------------

function fbControl( GDImage $iImage, $bTest = true ) : bool {

  $ahTrace = debug_backtrace();
  $zTest = substr( $ahTrace[ 1 ][ 'function' ], 2 );

  $zControlFileName =
    ( __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .
      'tests-images' . DIRECTORY_SEPARATOR . $zTest . '.png' );

  $zFileName = ( $bTest ?
    tempnam( sys_get_temp_dir(), $zTest ) :
    $zControlFileName );

  if ( ! imagepng( $iImage, $zFileName, 0 ) )
    throw new Exception( "could not save image to $zFileName" );

  if ( ! $bTest )
    return true;

  $bSame = ( ( filesize( $zControlFileName ) === filesize( $zFileName ) ) &&
    ( md5_file( $zControlFileName ) === md5_file( $zFileName ) ) );

  unlink( $zFileName );

  return $bSame;

  }; // fbControl

// EOF
