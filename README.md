Ynfinite communication bundle
=============================

Ynfinte is a marketing automation solution for everyone. Visite the [projekt website][1] for
more information.

The communication bundle for [Contao CMS][3] enables communication from your website to Ynfinite and vice versa.


Installation
------------

Run the following command in your project directory:

```bash
php composer.phar require ynfinite/com-bundle
```


Activation
-------------

Adjust to your `app/AppKernel.php` file:

```php
// app/AppKernel.php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new Ynfinite\ComBundle\YnfiniteComBundle(),
        ];
    }
}
```


License
-------

Contao is licensed under the terms of the LGPLv3.
Ynfinite ComBundle is licensed under the terms of the MIT licence


Getting support
---------------

Visit the [support page][2] to learn about the available support options.


[1]: https://ynfinite.de
[2]: https://ynfinite.de/support.html
[3]: https://contao.org