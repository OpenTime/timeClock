<?php
/**
 * Time Clock
 * PHPUnit Test
 *
 * @author      MarQuis L. Knox <opensource@marquisknox.com>
 * @license     GPL v2
 * @link        http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://github.com/MarQuisKnox/timeClock
 *
 * @since       Thursday, April 26, 2012 / 04:54 PM GMT+1 mknox
 * @edited      $Date: 2011-03-10 12:38:09 +0100 (Thu, 10 Mar 2011) $ $Author: mknox $
 * @version     $Revision: 1 $
 *
 * @uses		PHPUnit
 * @package     Time Clock
 */

define( 'IN_PHPUNIT', true );
require_once( dirname( dirname(__FILE__) ).'/includes/config.php' );
class getWorkDaysTest extends PHPUnit_Framework_TestCase
{
	public function testNewArrayIsEmpty()
	{
		// Create the Array fixture.
		$fixture = array();

		// Assert that the size of the Array fixture is 0.
		$this->assertEquals(0, sizeof($fixture));
	}

	public function testArrayContainsAnElement()
	{
		// Create the Array fixture.
		$fixture = array();

		// Add an element to the Array fixture.
		$fixture[] = 'Element';

		// Assert that the size of the Array fixture is 1.
		$this->assertEquals(1, sizeof($fixture));
	}
}