<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Services\FileUploader;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post", name="app_post")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param PostRepository $postRepository
     * @return Response
     */
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();
        //dump($posts);
        
        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/create", name="app_create")
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function create(ManagerRegistry $doctrine, Request $request, FileUploader $fileUploader) :Response {
        //create a post new title
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);
        //$post->setTitle('This is going to be a title');

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            //entity manager to persist to DB
            $em = $doctrine->getManager();

            /** @var UploadedFile $file */
            $file = $request->files->get('post')['attachment'];
            if ($file) {

                $fileName = $fileUploader->uploadFile($file);

                $post->setImage($fileName);
                //prepares queries to db columns
                $em->persist($post);
                // actually executes the queries (i.e. the INSERT query)
                $em->flush();
            }

            return $this->redirect($this->generateUrl('app_postindex'));
        }

        //get the post id after it is created
        //$id = $post->getId();

        //send a message if delete successful
        $this->addFlash('success', 'Your post was successfully created');

        //return a response
        return $this->render('post/create.html.twig', [
            'form' => $form->createView()
        ]);
        //return $this->redirect($this->generateUrl('app_postindex'));
        //return new Response('You saved new post into the database');
    }

    /**
     * @Route("/show/{id}", name="show")
     * @param $id
     * @param PostRepository $postRepository
     * @return Response
     */
    public function show(Post $post) {
        //create the show view
        return $this->render('post/show.html.twig', [
            'post' => $post
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @param Post $post
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function removePost(Post $post, ManagerRegistry $doctrine)
    {
        //get the post id before to be deleted
        $id = $post->getId();

        //entity manager to persist to DB
        $em = $doctrine->getManager();
        $em->remove($post);

        //exec sql query (delete)
        $em->flush();

        //send a message if delete successful
        $this->addFlash('success', 'Your post #' . $id . ' was successfully deleted');

        //return a response
        return $this->redirect($this->generateUrl('app_postindex'));
    }

}



