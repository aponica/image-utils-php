<?php declare(strict_types=1);
//=============================================================================
//  Copyright 2022 Opplaud LLC and other contributors. MIT licensed.
//=============================================================================

require_once 'fbControl.php';

use PHPUnit\Framework\TestCase;
use function Aponica\ImageUtils\fCenterText;

//-----------------------------------------------------------------------------

function fbCenterTextAllOptions( bool $bTest ) : bool {

  $iImage = imagecreatetruecolor( 400, 50 );

  imagefill( $iImage, 0, 0,
    imagecolorallocate( $iImage, 255, 0, 0 ) );

  fCenterText(
    [ // hParams
      'zFontFileName' => 'tests-config/NotoMonoRegular.ttf',
      'iImage' => $iImage,
      'nHeight' => 25,
      'zText' => 'CenterText:NoOptions',
      'nX' => 200,
      'nY' => 25
      ], // hParams
    [ // hOptions
      'iColor' =>
        imagecolorallocate( $iImage, 0, 0, 255 ),
      'hOutline' => [
        'iColor' =>
          imagecolorallocate( $iImage, 255, 255, 255 ),
        'nThickness' => 2
        ]
      ] // hOptions
    ); // fCenterText

  return fbControl( $iImage, $bTest );

  } // fbCenterTextAllOptions


//-----------------------------------------------------------------------------

function fbCenterTextNoOptions( bool $bTest ) : bool {

  $iImage = imagecreatetruecolor( 400, 50 );

  imagefill( $iImage, 0, 0,
    imagecolorallocate( $iImage, 255, 255, 255 ) );

  fCenterText( [
    'zFontFileName' => 'tests-config/NotoMonoRegular.ttf',
    'iImage' => $iImage,
    'nHeight' => 25,
    'zText' => 'CenterText:NoOptions',
    'nX' => 200,
    'nY' => 25
    ] ); // fCenterText

  return fbControl( $iImage, $bTest );

  } // fbCenterTextNoOptions

final class fCenterTextTest extends TestCase {

  //---------------------------------------------------------------------------

  public function testAllOptions() : void {
    $this->assertEquals( true, fbCenterTextAllOptions( true ) );
    }

  //---------------------------------------------------------------------------

  public function testNoOptions() : void {
    $this->assertEquals( true, fbCenterTextNoOptions( true ) );
    }

} // fCenterTextTest

// EOF
