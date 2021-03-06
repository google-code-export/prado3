<?php

class ActiveDropDownListTestCase extends SeleniumTestCase
{
	function test()
	{
		$this->open("active-controls/index.php?page=ActiveDropDownList");
		$this->assertTextPresent('Active Drop Down List Test Case');

		$this->assertText("label1", "Label 1");

		$this->click("button1");
		$this->pause(800);
		$this->assertSelected("list1", "item 4");

		$this->click("button2");
		$this->pause(800);
		$this->assertEmptySelection("list1");

		$this->click("button3");
		$this->pause(800);
		$this->assertSelected("list1", "item 2");

		$this->assertText("label1", "Selection 1: value 1");

		$this->select("list1", "item 1");
		$this->pause(800);
		$this->select("list2", "value 1 - item 4");
		$this->pause(800);
		$this->assertText("label2", "Selection 2: value 1 - item 4");

		$this->select("list1", "item 3");
		$this->pause(800);
		$this->select("list2", "value 3 - item 5");
		$this->pause(800);
		$this->assertText("label2", "Selection 2: value 3 - item 5");

		$this->click('button4');
		$this->pause(800);
		$this->assertSelected('list1', 'item 3');
		$this->pause(300);
		$this->assertSelected('list2', 'value 3 - item 3');

	}
}

?>