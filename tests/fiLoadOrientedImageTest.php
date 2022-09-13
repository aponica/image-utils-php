<?php declare(strict_types=1);
//=============================================================================
//  Copyright 2022 Opplaud LLC and other contributors. MIT licensed.
//=============================================================================

require_once 'fbControl.php';

use PHPUnit\Framework\TestCase;
use function Aponica\ImageUtils\fiLoadOrientedImage;

//-----------------------------------------------------------------------------

function fbLoadOrientedImageRightSideUp( bool $bTest ) : bool {

  $iImage = fiLoadOrientedImage('tests-config/RightSideUp.jpg' );

  return fbControl( $iImage, $bTest );

  } // fbLoadOrientedImageRightSideUp

//-----------------------------------------------------------------------------

function fbLoadOrientedImageTurnedClockwise( bool $bTest ) : bool {

  $iImage = fiLoadOrientedImage('tests-config/TurnedClockwise.jpg' );

  return fbControl( $iImage, $bTest );

  } // fbLoadOrientedImageTurnedClockwise

//-----------------------------------------------------------------------------

function fbLoadOrientedImageUpsideDown( bool $bTest ) : bool {

  $iImage = fiLoadOrientedImage('tests-config/UpsideDown.jpg' );

  return fbControl( $iImage, $bTest );

  } // fbLoadOrientedImageUpsideDown

//-----------------------------------------------------------------------------

final class fiLoadOrientedImageTest extends TestCase {

  //---------------------------------------------------------------------------

  public function testRightSideUp() : void {
    $this->assertEquals( true, fbLoadOrientedImageRightSideUp( true ) );
    }

  //---------------------------------------------------------------------------

  public function testTurnedClockwise() : void {
    $this->assertEquals( true, fbLoadOrientedImageTurnedClockwise( true ) );
    }

  //---------------------------------------------------------------------------

  public function testUpsideDown() : void {
    $this->assertEquals( true, fbLoadOrientedImageUpsideDown( true ) );
    }

} // fiLoadOrientedImageTest

// EOF
