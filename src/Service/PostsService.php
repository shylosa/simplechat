<?php
namespace App\Service;

use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;

class PostsService
{
    const POST_LIMIT = 10;
    
    private $postRepository;
    private $entityManager;
    
    public function __construct(PostRepository $postRepository, EntityManagerInterface $entityManager)
    {
        $this->postRepository = $postRepository;
        $this->entityManager = $entityManager;
    }

    public function getPosts(): array
    {
        return $this->postRepository->findBy([], ['postedAt' => 'DESC']);
    }

    //Крайне неоптимальный способ удаления лишних записей
    //т.к. Обращение к базе происходит n-10 раз
    public function removeOldestRow(): void
    {
        while ($this->getPostCount() > self::POST_LIMIT){
            $this->entityManager->remove($this->postRepository->findOneBy([],['postedAt' => 'ASC']));
            $this->entityManager->flush();
        }
    }

    public function getPostCount(): int
    {
        return $this->postRepository->count([]);
    }
}
