<?php

namespace AppBundle\Topic;

use AppBundle\Entity\Game;
use AppBundle\Entity\User;
use AppBundle\Helper\GameToArrayConverter;
use AppBundle\Security\ApiKeyUserProvider;
use Doctrine\ORM\EntityManager;
use Gos\Bundle\WebSocketBundle\Topic\TopicInterface;
use Gos\Bundle\WebSocketBundle\Topic\TopicPeriodicTimerInterface;
use Gos\Bundle\WebSocketBundle\Topic\TopicPeriodicTimerTrait;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Gos\Bundle\WebSocketBundle\Router\WampRequest;

class GameTopic implements TopicInterface, TopicPeriodicTimerInterface
{
	use TopicPeriodicTimerTrait;

	/**
	 * @var EntityManager
	 */
	protected $em;

	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}

	/**
	 * @param Topic $topic
	 *
	 * @return array
	 */
	public function registerPeriodicTimer(Topic $topic)
	{
		//add
		$this->periodicTimer->addPeriodicTimer(
			$this, 'game', 2, function() use ($topic) {
			$games = $this->em
				->getRepository('AppBundle:Game')
				->createQueryBuilder('game')
				->select('game, users')
				->join('game.users', 'users')
				->getQuery()
				->getArrayResult();

			$topic->broadcast(array('games' => $games ? $games : []));
		}
		);

		//exist
		$this->periodicTimer->isPeriodicTimerActive($this, 'game'); // true or false

		//remove
		$this->periodicTimer->cancelPeriodicTimer($this, 'game');
	}

	/**
	 * This will receive any Subscription requests for this topic.
	 *
	 * @param ConnectionInterface $connection
	 * @param Topic $topic
	 * @param WampRequest $request
	 *
	 * @return void
	 */
	public function onSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
	{
		//this will broadcast the message to ALL subscribers of this topic.
		$topic->broadcast(['msg' => $connection->resourceId." has joined ".$topic->getId()]);
	}

	/**
	 * This will receive any UnSubscription requests for this topic.
	 *
	 * @param ConnectionInterface $connection
	 * @param Topic $topic
	 * @param WampRequest $request
	 *
	 * @return void
	 */
	public function onUnSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
	{
		//this will broadcast the message to ALL subscribers of this topic.
		$topic->broadcast(['msg' => $connection->resourceId." has left ".$topic->getId()]);
	}

	/**
	 * This will receive any Publish requests for this topic.
	 *
	 * @param ConnectionInterface $connection
	 * @param Topic $topic
	 * @param WampRequest $request
	 * @param $event
	 * @param array $exclude
	 * @param array $eligible
	 *
	 * @return mixed|void
	 */
	public function onPublish(
		ConnectionInterface $connection, Topic $topic, WampRequest $request, $event, array $exclude, array $eligible
	) {
		/*
			$topic->getId() will contain the FULL requested uri, so you can proceed based on that

			if ($topic->getId() === 'game/channel/shout')
			   //shout something to all subs.
		*/

		$topic->broadcast(
			[
				'msg' => $event,
			]
		);
	}

	/**
	 * Like RPC is will use to prefix the channel
	 * @return string
	 */
	public function getName()
	{
		return 'game.topic';
	}
}