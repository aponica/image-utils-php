<?php declare(strict_types=1);
//=============================================================================
//  Copyright 2022 Opplaud LLC and other contributors. MIT licensed.
//=============================================================================

require_once 'fbControl.php';

use PHPUnit\Framework\TestCase;
use function Aponica\ImageUtils\fCenterTextLines;

//-----------------------------------------------------------------------------

function fbCenterTextLinesDebugAndOutline( bool $bTest ) : bool {

  $iImage = imagecreatetruecolor( 400, 200 );

  imagefill( $iImage, 0, 0,
    imagecolorallocate( $iImage, 255, 255, 255 ) );

  fCenterTextLines(
    [ // hParams
      'iImage' => $iImage,
      'avLines' => [ 'First Line', 'Last Line' ]
      ], // hParams
    [ // hOptions
      'bDebug' => true,
      'iColor' => imagecolorallocate( $iImage, 255, 0, 0 ),
      'zFontFileName' => 'tests-config/Ultra.ttf',
      'hOutline' => [
        'iColor' => imagecolorallocate( $iImage, 0, 0, 0 ) ]
      ] // hOptions
    ); // fCenterTextLines

  return fbControl( $iImage, $bTest );

  } // fbCenterTextLinesDebugAndOutline

//-----------------------------------------------------------------------------

function fbCenterTextLinesHashLines( bool $bTest ) : bool {

  $iImage = imagecreatetruecolor( 400, 200 );

  imagefill( $iImage, 0, 0,
    imagecolorallocate( $iImage, 255, 255, 255 ) );

  $iRed = imagecolorallocate( $iImage, 255, 0, 0 );
  $iGreen = imagecolorallocate( $iImage, 0, 255, 0 );
  $iBlue = imagecolorallocate( $iImage, 0, 0, 255 );

  fCenterTextLines( [
    'iImage' => $iImage,
    'avLines' => [
      [ // line 1
        'iColor' => $iRed,
        'zFontFileName' => 'tests-config/Ultra.ttf',
        'hOutline' => [ 'iColor' => $iBlue, 'nThickness' => 2 ],
        'nRelativeHeight' => 2,
        'zText' => 'First Line'
        ], // line 1
      [ // line 2
        'iColor' => $iBlue,
        'zFontFileName' => 'tests-config/NotoMonoRegular.ttf',
        'hOutline' => [ 'iColor' => $iRed ],
        'zText' => 'This is Line Number Two'
        ], // line 2
      [ // line 3
        'iColor' => $iGreen,
        'zFontFileName' => 'tests-config/NotoMonoRegular.ttf',
        'zText' => 'Last Line'
        ] // line 3
      ] // avLines
    ] ); // fCenterTextLines

  return fbControl( $iImage, $bTest );

  } // fbCenterTextLinesHashLines

//-----------------------------------------------------------------------------

function fbCenterTextLinesMinParams( bool $bTest ) : bool {

  $iImage = imagecreatetruecolor( 400, 200 );

  imagefill( $iImage, 0, 0,
    imagecolorallocate( $iImage, 255, 255, 255 ) );

  fCenterTextLines(
    [ // hParams
      'iImage' => $iImage,
      'avLines' => [ 'First Line', 'This is Line Number Two', 'Last Line' ]
      ], // hParams
    [ // hOptions
      'iColor' => imagecolorallocate( $iImage, 0, 0, 0 ),
      'zFontFileName' => 'tests-config/Ultra.ttf'
      ] // hOptions
    ); // fCenterTextLines

  return fbControl( $iImage, $bTest );

  } // fbCenterTextLinesMinParams

//-----------------------------------------------------------------------------

function fbCenterTextLinesNoLines( bool $bTest ) : bool {

  $iImage = imagecreatetruecolor( 50, 50 );

  imagefill( $iImage, 0, 0,
    imagecolorallocate( $iImage, 255, 0, 0 ) );

  fCenterTextLines(
    [ // hParams
      'iImage' => $iImage,
      'avLines' => []
      ], // hParams
    [ // hOptions
      'iColor' => imagecolorallocate( $iImage, 255, 0, 0 ),
      'zFontFileName' => 'tests-config/Ultra.ttf'
      ] // hOptions
    ); // fCenterTextLines

  return fbControl( $iImage, $bTest );

  } // fbCenterTextLinesNoLines

//-----------------------------------------------------------------------------

final class fCenterTextLinesTest extends TestCase {

  //---------------------------------------------------------------------------

  public function testDebugAndOutline() : void {
    $this->assertEquals( true, fbCenterTextLinesDebugAndOutline( true ) );
    }

  //---------------------------------------------------------------------------

  public function testHashLines() : void {
    $this->assertEquals( true, fbCenterTextLinesHashLines( true ) );
    }

  //---------------------------------------------------------------------------

  public function testMinParams() : void {
    $this->assertEquals( true, fbCenterTextLinesMinParams( true ) );
    }

  //---------------------------------------------------------------------------

  public function testNoFont() : void {

    $this->expectException( Exception::class );
    $this->expectExceptionMessageMatches(
      '/\[zFontFileName\] must be specified/' );

    $iImage = imagecreatetruecolor( 10, 10 );

    fCenterTextLines(
      [ // hParams
        'iImage' => $iImage,
        'avLines' => [ 'Line One' ]
        ], // hParams
      [ // hOptions
        'iColor' => imagecolorallocate( $iImage, 255, 0, 0 )
        ] // hOptions
      ); // fCenterTextLines

    } // testNoFont

  //---------------------------------------------------------------------------

  public function testNoLines() : void {
    $this->assertEquals( true, fbCenterTextLinesNoLines( true ) );
    }

} // fCenterTextLinesTest

// EOF
