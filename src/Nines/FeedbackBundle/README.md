Feedback & Commenting Bundle
============================

The Feedback bundle provides very simple commenting for Symfony 
applications.

Installation
============

Download the bundle and put it somewhere Symfony will find it. Add it
to the application kernel and add routing for the bundle.

Configuration
=============

The bundle needs to know how to map entity classes to urls. Add something
like the following to app/config/config.yml:

```yaml
feedback:
    commenting:
        author:
            class: AppBundle\Entity\Author
            route: admin_author_show
        alias:
            class: AppBundle\Entity\Alias
            route: admin_alias_show
        publication:
            class: AppBundle\Entity\Publication
            route: admin_publication_show
        place:
            class: AppBundle\Entity\Place
            route: admin_place_show
```

Usage
=====

Add commenting to an entity controller:

Get comments in the entity's show controller:

```php
    /**
     * Finds and displays a Author entity.
     *
     * @Route("/{id}", name="author_show")
     * @Method({"GET","POST"})
     * @Template()
     * @param Author $author
     */
    public function showAction(Request $request, Author $author) {
        $comment = new Comment();
        $form = $this->createForm('Nines\FeedbackBundle\Form\CommentType', $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('feedback.comment')->addComment($author, $comment);
            $this->addFlash('success', 'Thank you for your suggestion.');
            return $this->redirect($this->generateUrl('author_show', array('id' => $author->getId())));
        }
        $comments = $this->get('feedback.comment')->findComments($author);
        return array(
            'form' => $form->createView(),
            'author' => $author,
			'comments' => $comments,
        );
    }
```

Show the comments and comment form in a twig template:

```twig
    {% include 'FeedbackBundle:Comment:comments-view.html.twig' %}                            
    {% include('FeedbackBundle:Comment:comment-form.html.twig') %}
```