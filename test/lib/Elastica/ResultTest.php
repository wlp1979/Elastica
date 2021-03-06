<?php
require_once dirname(__FILE__) . '/../../bootstrap.php';


class Elastica_ResultTest extends PHPUnit_Framework_TestCase
{
	public function setUp() {
	}

	public function tearDown() {
	}

	public function testGetters() {
		// Creates a new index 'xodoa' and a type 'user' inside this index
		$indexName = 'xodoa';
		$typeName = 'user';

		$client = new Elastica_Client();
		$index = $client->getIndex($indexName);
		$index->create(array(), true);
		$type = $index->getType($typeName);


		// Adds 1 document to the index
		$docId = 3;
		$doc1 = new Elastica_Document($docId, array('username' => 'hans'));
		$type->addDocument($doc1);

		// Refreshes index
		$index->refresh();
		sleep(2);

		$resultSet = $type->search('hans');

		$this->assertEquals(1, $resultSet->count());

		$result = $resultSet->current();

		$this->assertInstanceOf('Elastica_Result', $result);
		$this->assertEquals($indexName, $result->getIndex());
		$this->assertEquals($typeName, $result->getType());
		$this->assertEquals($docId, $result->getId());
		$this->assertGreaterThan(0, $result->getScore());
		$this->assertInternalType('array', $result->getData());
	}
}
