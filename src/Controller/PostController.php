<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/", name="post")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, PostRepository $postRepository)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setPostedAt(new \DateTime());
            $entityManager->persist($post);

            //Крайне неоптимальный способ удаления лишних записей
            //т.к. Обращение к базе происходит n-10 раз
            $this->removeOldestRow($entityManager, $postRepository);
            $entityManager->flush();

            return $this->redirectToRoute('post');
        }

        $posts = $postRepository->findBy([], ['postedAt' => 'DESC']);

        return $this->render('post/index.html.twig', [
            'form' => $form->createView(),
            'posts'=>$posts,
        ]);
    }

    public function removeOldestRow($entityManager, $postRepository): void
    {
        $maxPosts = 10;
        while ($postRepository->count([]) >= $maxPosts){
            $entityManager->remove($postRepository->findOneBy([],['postedAt' => 'ASC']));
            $entityManager->flush();
        }
    }
}

//Вариант запроса, удаляющего лишние записи (проверка на то, что записей больше 10 происходит в PHP)
//SET @oldest = (SELECT COUNT(*)-10 FROM post);
//PREPARE STMT FROM 'SELECT * FROM post ORDER BY posted_at LIMIT ?';
//EXECUTE STMT USING @oldest;

