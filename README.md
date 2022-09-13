# aponica/image-utils-php

Common image manipulations in PHP.

Current manipulations include:

* Centering lines of text over an image.
* Correcting the orientation of an image.
* Fitting an image by cropping and/or scaling.

<a name="installation"></a>
## Installation

```sh
composer install aponica/image-utils-php
```

<a name="example"></a>
## Example

```php
use function Aponica\ImageUtils\fCenterTextLines;
use function Aponica\ImageUtils\fiFitImage;
use function Aponica\ImageUtils\fiLoadOrientedImage;

//  Load the image, rotating it if necessary.

$oriented = fiLoadOrientedImage( 'example.jpg' );

//  Scale and/or crop the image to size 800x600.

$resized = fiFitImage( 800, 600, $oriented );

//  Overlay two lines of red text in Helvetica type. 

fCenterTextLines(
  [ 'iImage' => $resized,
    'avLines' => [ 'First Text Line', 'Second Text Line' ] ],
  [ 'iColor' => imagecolorallocate( $resized, 255, 0, 0 ),
    'zFontFileName' => 'helvetica.ttf' ]
  );
```

## Unit Testing

The [PHPUnit](https://phpunit.de/) unit tests compare images created during
the testing process to images that were created beforehand. 

Before you change any code, be sure to run this command from the package root 
directory (the one *containing* `tests-config`):

```sh
php tests-config/CreateControlImages.php
```

`CreateControlImages.php` creates the control images (hence its name!),
placing them in a `tests-images` directory.

If you don't run the script beforehand, there will be no control images and
the tests will always fail.

If you run the script *after* making changes, the unit tests might pass
even if you introduced a breaking change, because you will be comparing
current images against themselves.

Consult `tests-config/CreateControlImages.php` for detailed instructions on
creating new unit tests that leverage this functionality.
 
## Please Donate!

Help keep a roof over our heads and food on our plates! 
If you find aponica® open source software useful, please 
**[click here](https://www.paypal.com/biz/fund?id=BEHTAS8WARM68)** 
to make a one-time or recurring donation via *PayPal*, credit 
or debit card. Thank you kindly!


## Contributing

Please [contact us](https://aponica.com/contact/) if you believe this package
is missing important functionality that you'd like to provide.

Under the covers, the code is **heavily commented** and uses a form of
[Hungarian notation](https://en.wikipedia.org/wiki/Hungarian_notation) 
for data type guidance. If you submit a pull request, please try to maintain
the (admittedly unusual) coding style, which is the product of many decades
of programming experience.

## Copyright

Copyright 2019-2022 Opplaud LLC and other contributors.

## License

MIT License.

## Trademarks

OPPLAUD and aponica are registered trademarks of Opplaud LLC.

## Related Links

Official links for this project:

* [Home Page & Online Documentation](https://aponica.com/docs/image-utils-php/)
* [GitHub Repository](https://github.com/aponica/image-utils-php)
* [Packagist](https://packagist.org/packages/aponica/image-utils-php)
  
Related projects:

* None at this time.
