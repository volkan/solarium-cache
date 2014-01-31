<?php

namespace Hautelook\Solarium\Cache\Tests;

use Hautelook\Solarium\Cache\CachePlugin;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTestCase;
use Solarium\Client;
use Solarium\Core\Client\Adapter\AdapterInterface;
use Solarium\Core\Client\Response;

/**
 * @author Adrien Brault <adrien.brault@gmail.com>
 */
class CachePluginTest extends ProphecyTestCase
{
    public function testNoCacheRequested()
    {
        $adapterProphecy = $this->prophesizeClientAdapter();
        $adapterProphecy
            ->execute(Argument::cetera())
            ->willReturn($this->createSuccessfulResponse())
        ;
        $cacheProphecy = $this->prophesize('Doctrine\Common\Cache\Cache');

        $client = $this->createClient($adapterProphecy->reveal());

        $plugin = new CachePlugin();
        $plugin->setCache($cacheProphecy->reveal());
        $client->registerPlugin('cache', $plugin);

        $cacheProphecy->fetch(Argument::cetera())->shouldNotBeCalled();
        $cacheProphecy->save(Argument::cetera())->shouldNotBeCalled();

        $client->execute($client->createSelect());
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage The CachePlugin cache was not set.
     */
    public function testCacheRequestedButNoCacheSet()
    {
        $adapterProphecy = $this->prophesizeClientAdapter();
        $adapterProphecy
            ->execute(Argument::cetera())
            ->willReturn($this->createSuccessfulResponse())
        ;

        $client = $this->createClient($adapterProphecy->reveal());

        $client->registerPlugin('cache', new CachePlugin());

        $client->execute($client->createSelect(array('cache_lifetime' => 60)));
    }

    public function testCaching()
    {
        $adapterProphecy = $this->prophesizeClientAdapter();
        $adapterProphecy
            ->execute(Argument::cetera())
            ->willReturn($response = $this->createSuccessfulResponse())
            ->shouldBeCalledTimes(1)
        ;
        $cacheProphecy = $this->prophesize('Doctrine\Common\Cache\Cache');

        $client = $this->createClient($adapterProphecy->reveal());

        $plugin = new CachePlugin();
        $plugin->setCache($cacheProphecy->reveal());
        $client->registerPlugin('cache', $plugin);

        $isResponseSaved = false;
        $cacheProphecy->fetch(Argument::any())->will(function () use (&$isResponseSaved, $response) {
            if ($isResponseSaved) {
                return serialize($response);
            }

            return null;
        });
        $cacheProphecy
            ->save(
                Argument::any(),
                serialize($response),
                60
            )
            ->shouldBeCalled()
            ->will(function () use (&$isResponseSaved) {
                $isResponseSaved = true;
            })
        ;

        $client->execute($client->createSelect(array('cache_lifetime' => 60)));
        $client->execute($client->createSelect(array('cache_lifetime' => 60)));
    }

    private function createClient(AdapterInterface $adapter)
    {
        $client = new Client();
        $client->setAdapter($adapter);

        return $client;
    }

    private function prophesizeClientAdapter()
    {
        $adapterProphecy = $this->prophesize('Solarium\Core\Client\Adapter\AdapterInterface');
        $adapterProphecy->setOptions(Argument::cetera())->shouldBeCalled();

        return $adapterProphecy;
    }

    private function createSuccessfulResponse()
    {
        return new Response('', array('HTTP/1.1 200 OK'));
    }
}
