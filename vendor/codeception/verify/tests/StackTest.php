<?php
use app\Components\Stack;

class StackTest extends \Codeception\TestCase\Test
{
	public function testValidation()
	{
		$stack = new Stack();

		$stack->push('A Dream of Spring');
		$stack->push('The Winds of Winter');
		$stack->push('A Dance with Dragons');
		$stack->push('A Feast for Crows');
		$stack->push('A Storm of Swords');
		$stack->push('A Clash of Kings');
		$stack->push('A Game of Thrones');
		
		echo $stack->pop(); // outputs 'A Game of Thrones'
		echo $stack->pop(); // outputs 'A Clash of Kings'
		echo $stack->pop(); // outputs 'A Storm of Swords'

		$user->username = null;
		$this->assertFalse($user->validate(['username']));

		$user->username = 'toolooooongnaaaaaaameeee';
		$this->assertFalse($user->validate(['username']));

		$user->username = 'davert';
		$this->assertTrue($user->validate(['username']));
	}
}
?>