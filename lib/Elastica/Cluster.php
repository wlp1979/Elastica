<?php
/**
 * Cluster informations for elasticsearch
 *
 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/admin/cluster
 * @category Xodoa
 * @package Elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 */
class Elastica_Cluster
{
	protected $_client = null;

	public function __construct(Elastica_Client $client) {
		$this->_client = $client;
		$this->refresh();
	}

	public function refresh() {
		$path = '_cluster/state';
		$this->_response = $this->_client->request($path, Elastica_Request::GET);
		$this->_data = $this->getResponse()->getData();
	}

	/**
	 * @return Elastica_Response Response object
	 */
	public function getResponse() {
		return $this->_response;
	}

	/**
	 * Returns the full state of the cluster
	 *
	 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/admin/cluster/state
	 * @return array State array
	 */
	public function getState() {
		return $this->_data;
	}

	/**
	 * Returns a list of existing node names
	 *
	 * @return array List of node names
	 */
	public function getNodeNames() {
		$data = $this->getState();
		return array_keys($data['routing_nodes']['nodes']);
	}

	/**
	 * Returns all nodes of the cluster
	 *
	 * @return array List of Elastica_Node objects
	 */
	public function getNodes() {
		$nodes = array();
		foreach ($this->getNodeNames() as $name) {
			$nodes[] = new Elastica_Node($name, $this->getClient());
		}
		return $nodes;
	}

	/**
	 * @return Elastica_Client Client object
	 */
	public function getClient() {
		return $this->_client;
	}

	/**
	 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/admin/cluster/nodes_info/
	 */
	public function getInfo(array $args) {
		throw new Exception('not implemented yet');
	}

	/**
	 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/admin/cluster/health/
	 */
	public function getHealth($args = array()) {
		throw new Exception('not implemented yet');
	}

	/**
	 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/admin/cluster/nodes_restart/
	 */
	public function restart(array $args) {
		throw new Exception('not implemented yet');

	}

	/**
	 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/admin/cluster/nodes_shutdown/
	 */
	public function shutdown(array $args) {
		throw new Exception('not implemented yet');

	}
}
