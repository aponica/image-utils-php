<?php declare(strict_types=1);
//=============================================================================
// Copyright 2019-2022 Opplaud LLC and other contributors. MIT licensed.
//=============================================================================

/// @cond DOXYGEN_IGNORE

namespace PHPUnit\Framework;
class TestCase {};

/// @endcond DOXYGEN_IGNORE

require_once 'vendor/autoload.php';

//-----------------------------------------------------------------------------
//  This script creates "control" images for comparison during unit tests.
//  The script must be run with the package directory (the parent of the
//  "tests-config" subdirectory) as its working directory. Control images
//  will be placed in a sibling "tests-images" directory.
//
//  For each unit test file in the ../tests subdirectory: if a test needs to
//  compare a newly-generated image against a previously-generated "control"
//  image (because the corresponding library code has changed), then the
//  file containing the test case (extending TestCase) should:
//
//  (1) Require_once the "fbControl.php" file in the "tests" directory,
//  which defined function "fbControl" to perform the comparison:
//
//    require_once "fbControl.php";
//
//  (2) Define a top-level function named "fbFuncDesc", where "Func" is the
//  name of the library function to be tested (without prefixes), and "Desc"
//  is a description of the test being performed.
//
//  The signature receives one bool parameter named "$bTest" and must return
//  a bool result, which it gets by passing its created image and $bTest to
//  fbControl().
//
//  For example, if the library function is named "fFillImage", and you are
//  testing what happens when an option named "bSolid" is enabled, then the
//  "fFillImageTest.php" test file might contain:
//
//    function fbFillImageSolid( bool $bTest ) : bool {
//      $iImage = imagecreatetruecolor( 400, 50 );
//      fFillImage( $iImage, [ 'bSolid' => true ] );
//      return fbControl( $iImage, $bTest );
//      }
//
//  (3) Within the class that extends TestCase, define a test method that
//  asserts that the response from the top-level test function is true.
//  The method should be named "testDesc", where "Desc" is the description
//  provided for the top-level test function.
//
//  Continuing the previous example, fFillImageTest.php should contain:
//
//    class fFillImageTest extends TestCase {
//      public function testSolid( bool $bControl = false ) : void {
//        $this->assertEquals( true, fbFillImageSolid( true ) );
//        }
//      }
//
//  When this script is run, if the top-level function is defined correctly,
//  its results will be saved (by fbControl()) as an uncompressed image named
//  "FuncDesc.png" in the "tests-images" directory, where "Func" and "Desc"
//  are the values from the top-level function name.
//
//  Continuing the previous example, the control file will be named
//  "FillImageSolid.png".
//
//  When the unit tests are run, the results will be compared (by fbControl())
//  to the previously-saved control image.  The assertion will pass if the
//  images are the same or fail if they are different.
//-----------------------------------------------------------------------------

//  Create the target directory if needed.

$zControlPath = ( __DIR__ .
  DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'tests-images' );

if ( ! is_dir( $zControlPath ) )
  mkdir( $zControlPath );


//  Import all of the tests files to get their image-creation functions.

$zTestsDir = __DIR__ .
  DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'tests';

$azFiles = scandir( $zTestsDir );

foreach ( $azFiles as $zFileName )
  if ( preg_match( '/^f\w+Test.php$/u', $zFileName ) ) {
    echo "$zFileName:\n";
    require_once( $zTestsDir . DIRECTORY_SEPARATOR . $zFileName );
    }


//  Invoke each image-creation function to create the control images.
//  Here the functions are passed false (instead of true, as in the test
//  case), which causes fbControl() to save them to the "tests-images"
//  subdirectory!

$azFuncs = get_defined_functions()[ 'user' ];

foreach ( $azFuncs as $zFunc )
  if ( preg_match( '/^fb/u', $zFunc ) &&
    ( strtolower( $zFunc ) !== 'fbcontrol' ) ) {
      echo "  $zFunc\n";
      $zFunc( false );
      }

// EOF
