<?php
use PHPUnit\Framework\TestCase;
use Wex\App;

class ExampleTest extends TestCase
{
    protected static $app = null;

    public function setUp()
    {
        if (self::$app === null) {
            self::$app = App::bootstrap();
        }
    }

    public function testThisIsExample()
    {
        $this->assertTrue(true);
    }
}
