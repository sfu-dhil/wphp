<?php

namespace Nines\FeedbackBundle\Services;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Exception;
use Monolog\Logger;
use Nines\FeedbackBundle\Entity\Comment;
use Nines\FeedbackBundle\Form\CommentType;
use ReflectionClass;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

// service id: feedback.comment
class CommentService {
    
    /**
     * @var EntityManager
     */
    private $em;
    
    /**
     * @var Logger
     */
    private $logger;
    
    /**
     * @var Router
     */
    private $router;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authChecker;
    
    /**
     * Mapping of class name to route name.
     *
     * @var array
     */
    private $routing;
    
    private $defaultStatusName;
    
    private $publicStatusName;
    
    private $formFactory;
    
    public function __construct($routing, $defaultStatusName, $publicStatusName) {
        $this->routing = $routing;
        $this->defaultStatusName = $defaultStatusName;
        $this->publicStatusName = $publicStatusName;
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
    
    public function setAuthorizationChecker(AuthorizationCheckerInterface $authChecker) {
        $this->authChecker = $authChecker;        
    }
    
    public function setFormFactory(FormFactory $formFactory) {
        $this->formFactory = $formFactory;
    }
    
    public function acceptsComments($name) {
        return array_key_exists($name, $this->routing);
    }
    
    public function findEntity(Comment $comment) {
        list($class, $id) = explode(':', $comment->getEntity());
        $entity = $this->em->getRepository($class)->find($id);
        return $entity;
    }
    
    public function entityType(Comment $comment) {
        $entity = $this->findEntity($comment);
        $reflection = new ReflectionClass($entity);
        return $reflection->getShortName();
    }
    
    public function entityUrl(Comment $comment) {
        list($class, $id) = explode(':', $comment->getEntity());
        if(! array_key_exists($class, $this->routing)) {
            throw new Exception("Cannot map {$class} to a route.");
        }
        return $this->router->generate($this->routing[$class], ['id' => $id]);
    }
    
    public function findComments($entity) {
        $class = get_class($entity);
        $comments = array();
        if($this->authChecker->isGranted('ROLE_ADMIN')) {
            $comments = $this->em->getRepository('FeedbackBundle:Comment')->findBy(array(
                'entity' => $class . ':' . $entity->getId()
            ));
        } else {
            $status = $this->em->getRepository('FeedbackBundle:CommentStatus')->findOneBy(array(
                'name' => $this->publicStatusName
            ));
            if($status) {
                $comments = $this->em->getRepository('FeedbackBundle:Comment')->findBy(array(
                    'entity' => $class . ':' . $entity->getId(),
                    'status' => $status,
                ));
            }
        }
        return $comments;
    }
    
    public function addComment($entity, Comment $comment) {
        $comment->setEntity(get_class($entity) . ':' . $entity->getId());    
        if(! $comment->getStatus()) {
            $comment->setStatus($this->em->getRepository('FeedbackBundle:CommentStatus')->findOneBy(array(
                'name' => $this->defaultStatusName,
            )));
        }
        $this->em->persist($comment);
        $this->em->flush($comment);
        return $comment;
    }
    
    public function getForm() {
        return $this->formFactory->create(CommentType::class)->createView();
    }
}
