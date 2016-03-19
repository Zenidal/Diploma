<?php

namespace AppBundle\Topic;

use AppBundle\Entity\Game;
use AppBundle\Helper\CardHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Gos\Bundle\WebSocketBundle\Topic\TopicInterface;
use Gos\Bundle\WebSocketBundle\Topic\TopicPeriodicTimerTrait;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Gos\Bundle\WebSocketBundle\Router\WampRequest;

class ActualGameTopic implements TopicInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param  ConnectionInterface $connection
     * @param  Topic $topic
     * @param WampRequest $request
     */
    public function onSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
        //this will broadcast the message to ALL subscribers of this topic.
        $topic->broadcast(['msg' => $connection->resourceId . " has joined " . $topic->getId()]);
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
        $topic->broadcast(['msg' => $connection->resourceId . " has left " . $topic->getId()]);
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
    )
    {
        if ($event['event'] && $event['event'] == 'move') {
            $cardHelper = new CardHelper($this->em);
            try {
                $moveResult = $cardHelper->moveCard($event['userId'], $event['gameId'], $event['cardId']);
                if ($moveResult['result'] == CardHelper::MOVED_SUCCESSFULLY) {
                    /** @var Game $game */
                    $game = $moveResult['data'];
                    $gameArray = array(
                        'id' => $game->getId(),
                        'name' => $game->getName(),
                        'visitorId' => $game->getVisitor()->getId(),
                        'creatorId' => $game->getCreator()->getId(),
                        'json' => $game->getJson()
                    );
                    $topic->broadcast(
                        [
                            'msg' => ['game' => $gameArray]
                        ]
                    );
                } else if ($moveResult['result'] == CardHelper::MOVED_FAIL_BY_TURN) {
                    $topic->broadcast(
                        [
                            'msg' => 'Not your turn.',
                        ]
                    );
                } else {
                    $topic->broadcast(
                        [
                            'msg' => 'Error.',
                        ]
                    );
                }
            } catch (EntityNotFoundException $ex) {
                $topic->broadcast(
                    [
                        'msg' => $ex->getMessage(),
                    ]
                );
            }
        }
        /*
            $topic->getId() will contain the FULL requested uri, so you can proceed based on that

            if ($topic->getId() === 'game/channel/shout')
               //shout something to all subs.
        */
    }

    /**
     * Like RPC is will use to prefix the channel
     * @return string
     */
    public function getName()
    {
        return 'actual_game.topic';
    }
}