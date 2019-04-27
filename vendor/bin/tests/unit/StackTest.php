<?php

use app\Components\Stack;

class StackTest extends \PHPUnit_Framework_TestCase
{
	/** @var app\Components\Stack */
	public $stack;


	protected function setUp()
	{
		$this->stack = new \app\Components\Stack();
	}

	protected function tearDown()
	{
	}

	// tests

	public function testPushPopContentCheck()
	{
		$expected = 1234;
		$this->stack->push($expected);
		$actual = $this->stack->pop();
		$this->assertEquals($expected, $actual);
	}

	public function testPushTop()
	{

		$this->stack->push('42');
		$this->stack->top();

		$this->assertFalse($this->stack->isEmpty());
	}

	public function testPushTopContentCheckMultiples()
	{
		$pushed3 = 3;
		$this->stack->push($pushed3);
		$pushed4 = 4;
		$this->stack->push($pushed4);
		$pushed5 = 5;
		$this->stack->push($pushed5);

		$topped = $this->stack->top();
		$this->assertEquals($pushed5, $topped);
	}

	public function testPushTopNoStackStateChange()
	{
		$pushed = 44;
		$this->stack->push($pushed);
		for ($index = 0; $index < 10; $index++) {
			$topped = $this->stack->top();
			$this->assertEquals($pushed, $topped);
		}
	}

	public function testTopEmptyStack()
	{
		$this->stack->top();
	}

	public function testPushNullCheckPop()
	{
		$this->stack->push(null);
		$this->assertNull($this->stack->pop());
		$this->assertTrue($this->stack->isEmpty());
	}

	public function testPushNullCheckTop()
	{
		$this->stack->push(null);
		$this->assertNull($this->stack->top());
		$this->assertFalse($this->stack->isEmpty());
	}

}