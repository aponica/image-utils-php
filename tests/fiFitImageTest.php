<?php declare(strict_types=1);
//=============================================================================
//  Copyright 2022 Opplaud LLC and other contributors. MIT licensed.
//=============================================================================

require_once 'fbControl.php';

use PHPUnit\Framework\TestCase;
use function Aponica\ImageUtils\fiFitImage;

//-----------------------------------------------------------------------------

function fbFitImageCropOffBottom( bool $bTest ) : bool {

  $iImage = fiFitImage( 400, 200,
    imagecreatefromjpeg( 'tests-config/RightSideUp.jpg' ),
    [ 'nCropOffset' => -150 ] ); // too far

  return fbControl( $iImage, $bTest );

  } // fbFitImageCropOffBottom


//-----------------------------------------------------------------------------

function fbFitImageCropOffLeft( bool $bTest ) : bool {

  $iImage = fiFitImage( 300, 300,
    imagecreatefromjpeg( 'tests-config/RightSideUp.jpg' ),
    [ 'nCropOffset' => 150 ] ); // too far

  return fbControl( $iImage, $bTest );

  } // fbFitImageCropOffLeft


//-----------------------------------------------------------------------------

function fbFitImageCropOffRight( bool $bTest ) : bool {

  $iImage = fiFitImage( 300, 300,
    imagecreatefromjpeg( 'tests-config/RightSideUp.jpg' ),
    [ 'nCropOffset' => -150 ] ); // too far

  return fbControl( $iImage, $bTest );

  } // fbFitImageCropOffRight


//-----------------------------------------------------------------------------

function fbFitImageCropOffTop( bool $bTest ) : bool {

  $iImage = fiFitImage( 400, 200,
    imagecreatefromjpeg( 'tests-config/RightSideUp.jpg' ),
    [ 'nCropOffset' => 150 ] ); // too far

  return fbControl( $iImage, $bTest );

  } // fbFitImageCropOffTop


//-----------------------------------------------------------------------------

function fbFitImageDoubleHeight( bool $bTest ) : bool {

  $iImage = fiFitImage( 400, 600,
    imagecreatefromjpeg( 'tests-config/RightSideUp.jpg' ) );

  return fbControl( $iImage, $bTest );

  } // fbFitImageDoubleHeight


//-----------------------------------------------------------------------------

function fbFitImageDoubleSquare( bool $bTest ) : bool {

  $iImage = fiFitImage( 600, 600,
    imagecreatefromjpeg( 'tests-config/RightSideUp.jpg' ) );

  return fbControl( $iImage, $bTest );

  } // fbFitImageDoubleSquare


//-----------------------------------------------------------------------------

function fbFitImageDoubleWidth( bool $bTest ) : bool {

  $iImage = fiFitImage( 800, 300,
    imagecreatefromjpeg( 'tests-config/RightSideUp.jpg' ) );

  return fbControl( $iImage, $bTest );

  } // fbFitImageDoubleWidth


//-----------------------------------------------------------------------------

function fbFitImageHalfHeight( bool $bTest ) : bool {

  $iImage = fiFitImage( 400, 150,
    imagecreatefromjpeg( 'tests-config/RightSideUp.jpg' ) );

  return fbControl( $iImage, $bTest );

  } // fbFitImageHalfHeight


//-----------------------------------------------------------------------------

function fbFitImageHalfSquare( bool $bTest ) : bool {

  $iImage = fiFitImage( 150, 150,
    imagecreatefromjpeg( 'tests-config/RightSideUp.jpg' ) );

  return fbControl( $iImage, $bTest );

  } // fbFitImageHalfSquare


//-----------------------------------------------------------------------------

function fbFitImageHalfWidth( bool $bTest ) : bool {

  $iImage = fiFitImage( 200, 300,
    imagecreatefromjpeg( 'tests-config/RightSideUp.jpg' ) );

  return fbControl( $iImage, $bTest );

  } // fbFitImageHalfWidth


final class fiFitImageTest extends TestCase {

  //---------------------------------------------------------------------------

  public function testCropOffBottom() : void {
    $this->assertEquals( true, fbFitImageCropOffBottom( true ) );
    }

  //---------------------------------------------------------------------------

  public function testCropOffLeft() : void {
    $this->assertEquals( true, fbFitImageCropOffLeft( true ) );
    }

  //---------------------------------------------------------------------------

  public function testCropOffRight() : void {
    $this->assertEquals( true, fbFitImageCropOffRight( true ) );
    }

  //---------------------------------------------------------------------------

  public function testCropOffTop() : void {
    $this->assertEquals( true, fbFitImageCropOffTop( true ) );
    }

  //---------------------------------------------------------------------------

  public function testDoubleHeight() : void {
    $this->assertEquals( true, fbFitImageDoubleHeight( true ) );
    }

  //---------------------------------------------------------------------------

  public function testDoubleSquare() : void {
    $this->assertEquals( true, fbFitImageDoubleSquare( true ) );
    }

  //---------------------------------------------------------------------------

  public function testDoubleWidth() : void {
    $this->assertEquals( true, fbFitImageDoubleWidth( true ) );
    }

  //---------------------------------------------------------------------------

  public function testHalfHeight() : void {
    $this->assertEquals( true, fbFitImageHalfHeight( true ) );
    }

  //---------------------------------------------------------------------------

  public function testHalfSquare() : void {
    $this->assertEquals( true, fbFitImageHalfSquare( true ) );
    }

  //---------------------------------------------------------------------------

  public function testHalfWidth() : void {
    $this->assertEquals( true, fbFitImageHalfWidth( true ) );
    }

} // fiFitImageTest

// EOF
