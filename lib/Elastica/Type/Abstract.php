<?php
/**
 * Abstract helper class to implement search indices based on models.
 *
 * This abstract model should help creating search index and a subtype
 * with some easy config entries that are overloaded.
 *
 * The following variables have to be set:
 *	- $_indexName
 *	- $_typeName
 *
 * The following variables can be set for additional configuration
 *	- $_mapping: Value type mapping for the given type
 *	- $_indexParams: Parameters for the index
 *
 * @todo Add some settings examples to code
 * @category Xodoa
 * @package Elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 */
abstract class Elastica_Type_Abstract
{
	const MAX_DOCS_PER_REQUEST = 1000;

	protected $_indexName = '';
	protected $_typeName = '';
	protected $_client = null;
	protected $_index = null;
	protected $_type = null;
	protected $_mapping = array();
	protected $_indexParams = array();
	protected $_source = true;

	/**
	 * Creates index object with client connection
	 *
	 * Reads index and type name from protected vars _indexName and _typeName.
	 * Has to be set in child class
	 */
	public function __construct(Elastica_Client $client = null) {
		if (!$client) {
			$client = new Elastica_Client();
		}

		if (empty($this->_indexName)) {
			throw new Elastica_Exception_Invalid('Index name has to be set');
		}

		if (empty($this->_typeName)) {
			throw new Elastica_Exception_Invalid('Type name has to be set');
		}

		$this->_client = $client;
		$this->_index = new Elastica_Index($this->_client, $this->_indexName);
		$this->_type = new Elastica_Type($this->_index, $this->_typeName);
	}

	/**
	 * Creates the index and sets the mapping for this type
	 *
	 * @param bool $recreate OPTIONAL Recreates the index if true (default = false)
	 */
	public function create($recreate = false) {
		$this->getIndex()->create($this->_indexParams, $recreate);
		$this->getType()->setMapping($this->_mapping, $this->_source);
	}

	/**
	 * Searchs in the current index
	 *
	 * @param $query array|Elastica_Query Query params
	 * @return Elastica_ResultSet Result set
	 */
	public function search($query) {
		return $this->getType()->search($query);
	}

	/**
	 * Returns the search index
	 *
	 * @return Elastica_Index Index object
	 */
	public function getIndex() {
		return $this->_index;
	}

	/**
	 * Returns type object
	 *
	 * @return Elastica_Type Type object
	 */
	public function getType() {
		return $this->_type;
	}

	/**
	 * Converts given time to format: 1995-12-31T23:59:59Z
	 *
	 * This is the lucene date format
	 *
	 * @param int $date Date input (could be string etc.) -> must be supported by strtotime
	 * @return string Converted date string
	 */
	public function convertDate($date) {
		if (is_int($date)) {
			$timestamp = $date;
		} else {
			$timestamp = strtotime($date);
		}
		$string =  date('Y-m-d\TH:i:s\Z', $timestamp);
		return $string;
	}
}