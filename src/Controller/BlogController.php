<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Theme;
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

    #[Route('/{id}', name: 'details')]
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
        ArticleRepository $repository){
        $articleList = $repository->findBy(['theme' => $theme]);

        return $this->render('blog/list.html.twig', [
            'articleList' => $articleList,
            'title' => 'Liste des articles par thèmes',
            'theme' => $theme->getThemeName()
        ]);
    }
}
