<?php
namespace tests;

use Lauzis\Cco\Cco;
use PHPUnit\Framework\TestCase;

require_once 'src/bootstrap.php';

class CcoClassTest extends TestCase
{

    public function testInvalidActionFirstPlayer(){
        $cco = new Cco();
        $expectedResult = [3,3];

        $green_actions = ["watch-movie"];
        try {
            $result = $cco->getCoinBalances($green_actions, ["walk"]);
            $this->fail('FormValidationException was not thrown');
        } catch (\Exception $e) {

            $this->assertSame(
                    "Invalid operation '".$green_actions[0]."'",
                $e->getMessage()
            );
        }
    }

    public function testInvalidActionSecondPlayer(){
        $cco = new Cco();

        $green_actions = ["steal","share","share","watch-movie"];
        $red_actions = ['share','share','walk', 'share'];
        try {
            $result = $cco->getCoinBalances($green_actions, $red_actions);
            $this->fail('FormValidationException was not thrown');
        } catch (\Exception $e) {

            $this->assertSame(
                "Invalid operation '".$red_actions[2]."'",
                $e->getMessage()
            );
        }
    }

    public function testOutOfBalance(){
        $cco = new Cco();

        $green_actions = ["share","share","share","share"];
        $red_actions = ["steal","steal","steal","steal"];
        try {
            $result = $cco->getCoinBalances($green_actions, $red_actions);
            $this->fail('FormValidationException was not thrown');
        } catch (\Exception $e) {

            $this->assertSame(
                "Invalid operation 'share' for the current balance '0'",
                $e->getMessage()
            );
        }
    }

    /**
     * @throws \Exception
     */
    public function testEmptyActions(){
        $cco = new Cco();
        $expectedResult = [3,3];
        $result = $cco->getCoinBalances([], []);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @throws \Exception
     */
    public function testGreenEmptyActions(){
        $cco = new Cco();
        $expectedResult = [6,2];
        $result = $cco->getCoinBalances([], ['share']);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @throws \Exception
     */
    public function testRedEmptyActions(){
        $cco = new Cco();
        $expectedResult = [2,6];
        $result = $cco->getCoinBalances(['steal','share', 'steal'], []);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @throws \Exception
     */
    public function testGreenEmptyActionsEmptyList(){
        $cco = new Cco();
        $expectedResult = [12,0];
        $result = $cco->getCoinBalances([],['share','share', 'share']);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @throws \Exception
     */
    public function testShareShare(){
        $cco = new Cco();
        $expectedResult = [5,5];
        $result = $cco->getCoinBalances(["share"], ["share"]);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @throws \Exception
     */
    public function testStealShare(){
        $cco = new Cco();
        $expectedResult = [6,2];
        $result = $cco->getCoinBalances(["steal"], ["share"]);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @throws \Exception
     */
    public function testStealSteal(){
        $cco = new Cco();
        $expectedResult = [3,3];
        $result = $cco->getCoinBalances(["steal"], ["steal"]);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @throws \Exception
     */
    public function testOther(){
        $cco = new Cco();
        $this->assertEquals([3,11], $cco->getCoinBalances(["share", "share", "share"], ["steal", "share", "steal"]));

        $this->assertEquals([5, 5], $cco->getCoinBalances(['share'], ['share']));
        $this->assertEquals([6, 2], $cco->getCoinBalances(['steal'], ['share']));
        $this->assertEquals([2, 6], $cco->getCoinBalances(['share'], ['steal']));
        $this->assertEquals([3, 3], $cco->getCoinBalances(['steal'], ['steal']));

        $this->assertEquals([3, 11], $cco->getCoinBalances(['share', 'share', 'share'], ['steal', 'share', 'steal']));
        $this->assertEquals([0, 12], $cco->getCoinBalances(['share', 'share', 'steal', 'share'], ['steal', 'steal', 'steal', 'steal']));
        $this->assertEquals([12, 0], $cco->getCoinBalances(['steal', 'steal', 'steal'], ['share', 'share', 'share']));
        $this->assertEquals([7, 7], $cco->getCoinBalances(['share', 'share'], ['share', 'share']));
        $this->assertEquals([11, 3], $cco->getCoinBalances(['share', 'steal', 'steal', 'steal'], ['share', 'share', 'steal', 'share']));
        $this->assertEquals([3, 11], $cco->getCoinBalances(['share', 'share', 'steal', 'share'], ['steal', 'share', 'steal', 'steal']));
        $this->assertEquals([3, 3], $cco->getCoinBalances(['steal', 'steal', 'steal', 'steal'], ['steal', 'steal', 'steal', 'steal']));
        $this->assertEquals([8, 4], $cco->getCoinBalances(['steal', 'share', 'steal', 'steal'], ['share', 'share', 'steal', 'steal']));
        $this->assertEquals([9, 1], $cco->getCoinBalances(['steal', 'steal'], ['share', 'share']));
        $this->assertEquals([5, 5], $cco->getCoinBalances(['steal', 'share'], ['share', 'steal']));
    }

}
