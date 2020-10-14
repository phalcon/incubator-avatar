<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

use Phalcon\Di\FactoryDefault as DI;
use Phalcon\Incubator\Avatar\Gravatar;


final class GravatarCest
{
    private $di;

    public function __construct()
    {
        $this->di = new DI();

        $this->di->setShared('gravatar', function () {
            // Get Gravatar instance
            $gravatar = new Gravatar(
                []
            );

            // Setting default image, maximum size and maximum allowed Gravatar rating
            $gravatar->setDefaultImage('retro')
                ->setSize(220)
                ->setRating(Gravatar::RATING_PG);

            return $gravatar;
        });

        // Config must be either an array or \Phalcon\Config instance
        $config = [
            'default_image' => 'mm',
            'rating'        => 'x',
            'size'          => 110,
            'use_https'     => true,
        ];

        $this->di->setShared(
            'gravatar_config',
            function () use ($config) {
                // Get Gravatar instance
                $gravatar = new Gravatar($config);

                return $gravatar;
            }
        );
    }

    public function gravatarGetAvatar(FunctionalTester $I)
    {
        $gravatar = $this->di->getShared('gravatar');

        $expected = 'http://www.gravatar.com/avatar/6a6c19fea4a3676970167ce51f39e6ee?s=220&r=pg&d=retro';
        $I->assertEquals($expected, $gravatar->getAvatar('john@doe.com'));

        $gravatar_config = $this->di->getShared('gravatar_config');
        $expected = 'https://secure.gravatar.com/avatar/6a6c19fea4a3676970167ce51f39e6ee?s=110&r=x&d=mm';
        $I->assertEquals($expected, $gravatar_config->getAvatar('john@doe.com'));
    }

    public function gravatarGetAvatarConfigSecure(FunctionalTester $I)
    {
        $gravatar = $this->di->getShared('gravatar_config');
        $expected = 'https://secure.gravatar.com/avatar/6a6c19fea4a3676970167ce51f39e6ee?s=110&r=x&d=mm';
        $I->assertEquals($expected, $gravatar->getAvatar('john@doe.com'));
    }
}