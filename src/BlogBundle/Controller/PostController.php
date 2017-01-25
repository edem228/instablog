<?php

namespace BlogBundle\Controller;
use BlogBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;


class PostController extends Controller
{
    /**
     * @Route("/blog/new", name="post_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm('BlogBundle\Form\PostType', $post);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush($post);
            return $this->redirectToRoute('post_show', array('id' => $post->getId()));
        }

        return $this->render('BlogBundle:Post:new.html.twig', array(
            'post' => $post,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/blogs/{id}", name="post_show")
     * @Method({"GET"})
     */
    public function showAction(Post $post)
    {
        //$deleteForm = $this->createDeleteForm($post);
        return $this->render('BlogBundle:Post:show.html.twig', array(
            'post' => $post,
            //'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @Route("/blogs/", name="post_list")
     * @Method({"GET"})
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('BlogBundle:Post')->findAll();

        return $this->render('BlogBundle:Post:index.html.twig', array(
            'posts' => $posts,
        ));
    }


    /**
     * @Route("/blogs/{id}/edit",  name="post_edit"))
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Post $post)
    {
        $editForm = $this->createForm('BlogBundle\Form\PostType', $post);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('post_edit', array('id' => $post->getId()));
        }
        return $this->render('BlogBundle:Post:edit.html.twig', array(
            'post' => $post,
            'edit_form' => $editForm->createView(),
        ));
    }

}
