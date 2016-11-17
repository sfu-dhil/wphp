<?php

namespace FeedbackBundle\Services;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use FeedbackBundle\Entity\Comment;
use Monolog\Logger;
use Symfony\Component\Routing\Router;

// service id: feedback.comment
class CommentService {
    
    /**
     * @var EntityManager
     */
    private $em;
    
    private $logger;
    
    /**
     * @var Router
     */
    private $router;
    
    private $routing;
    
    public function __construct($routing) {
        $this->routing = $routing;
    }
    
    public function setDoctrine(Registry $registry) {
        $this->em = $registry->getManager();
    }
    
    public function setLogger(Logger $logger) {
        $this->logger = $logger;
    }
    
    public function setRouter(Router $router) {
        $this->router = $router;
    }
    
    public function findEntity(Comment $comment) {
        list($class, $id) = explode(':', $comment->getEntity());
        $entity = $this->em->getRepository($class)->find($id);
        return $entity;
    }
    
    public function entityType(Comment $comment) {
        $entity = $this->findEntity($comment);
        $reflection = new \ReflectionClass($entity);
        return $reflection->getShortName();
    }
    
    public function entityUrl(Comment $comment) {
        list($class, $id) = explode(':', $comment->getEntity());
        if( ! array_key_exists($class, $this->routing)) {
            throw new \Exception("Cannot map {$class} to a route.");
        }
        return $this->router->generate($this->routing[$class], ['id' => $id]);
    }
    
    public function findComments($entity) {
        $class = get_class($entity);
        $comments = $this->em->getRepository('FeedbackBundle:Comment')->findBy(array(
            'entity' => $class . ':' . $entity->getId()
        ));
        return $comments;
    }
    
    public function addComment($entity, Comment $comment) {
        $comment->setEntity(get_class($entity) . ':' . $entity->getId());
        $this->em->persist($comment);
        $this->em->flush($comment);
        return $comment;
    }
    
}
