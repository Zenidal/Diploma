<?php

namespace AppBundle\RPC;

use AppBundle\Entity\Game;
use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Ratchet\ConnectionInterface;
use Gos\Bundle\WebSocketBundle\RPC\RpcInterface;
use Gos\Bundle\WebSocketBundle\Router\WampRequest;

class GameService implements RpcInterface
{
	protected $em;

	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}

	/**
	 * Name of RPC, use for pubsub router (see step3)
	 * @return string
	 */
	public function getName()
	{
		return 'game.rpc';
	}
}