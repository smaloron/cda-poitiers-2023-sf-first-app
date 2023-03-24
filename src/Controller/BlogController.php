<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Author;
use App\Entity\Comment;
use App\Entity\Tag;
use App\Entity\Theme;
use App\Entity\User;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/blog', name: 'blog_')]
class BlogController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ArticleRepository $repository): Response
    {
        return $this->render('blog/index.html.twig', [
            'articleList' => $repository->findBy([], ['createdAt'=> 'DESC'], 4)
        ]);
    }

    #[Route('/{id}', name: 'details', requirements: ['id'=> '\d+'])]
    public function details(
        Article $article,
        Request $request,
        EntityManagerInterface $em
    ): Response{

        $comment = new Comment();
        $comment->setCreatedAt(new \DateTime())
                ->setArticle($article);

        $form = $this->createForm(
            CommentType::class, $comment
        );

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('blog_details', ['id'=>$article->getId()]);
        }

        return  $this->render('blog/details.html.twig', [
            'article' => $article,
            'commentForm' => $form->createView()
        ]);
    }

    #[Route('/by-theme/{id}', name: 'by_theme')]
    public function byTheme(
        Theme $theme,
        ArticleRepository $repository): Response
    {
        $articleList = $repository->findBy(['theme' => $theme]);

        return $this->render('blog/list.html.twig', [
            'articleList' => $articleList,
            'title' => 'Liste des articles par thèmes',
            'crit' => $theme->getThemeName()
        ]);
    }

    #[Route('/by-author/{id}', name: 'by_author')]
    public  function byAuthor(
        User $author,
        ArticleRepository $repository): Response
    {
        $articleList = $repository->findBy(['author'=> $author]);

        return $this->render('blog/list.html.twig', [
            'articleList' => $articleList,
            'title' => 'Liste des articles par auteur',
            'crit' => $author->getNickName()
        ]);
    }

    #[Route('/by-tag/{id}', name: 'by_tag')]
    public function  byTag(Tag $tag, ArticleRepository $repository): Response {
        return $this->render('blog/list.html.twig', [
        'articleList' => $repository->getArticlesByTag($tag),
            'title' => 'Liste des articles par tag',
            'crit' => $tag->getTagName()
        ]);
    }

    #[Route('/aside', name: 'aside')]
    public function aside(ArticleRepository $repository){
        $countByAuthor = $repository->getArticlecountByAuthor()->getArrayResult();

        dump($repository->getArticleCountByYear());

        return $this->render('blog/aside.html.twig', [
            'countByAuthor' => $countByAuthor,
            'countByTag' => $repository->getArticleCountByTag(),
            'countByYear' => $repository->getArticleCountByYear()
        ]);
    }
}
