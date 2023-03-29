<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Author;
use App\Entity\Comment;
use App\Entity\Tag;
use App\Entity\Theme;
use App\Entity\User;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Security\Voter\ArticleVoter;
use App\Service\ImageUploader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/blog', name: 'blog_')]
class BlogController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ArticleRepository $repository): Response
    {
        return $this->render('blog/index.html.twig', [
            'articleList' => $repository->getBestRatedArticles(4)
        ]);
    }



    #[Route('/by-theme/{id}', name: 'by_theme')]
    public function byTheme(
        Theme $theme,
        ArticleRepository $repository
    ): Response {
        $articleList = $repository->findBy(['theme' => $theme]);

        return $this->render('blog/list.html.twig', [
            'articleList' => $articleList,
            'title' => 'Liste des articles par thèmes',
            'crit' => $theme->getThemeName()
        ]);
    }

    #[Route('/by-author/{id}', name: 'by_author')]
    public function byAuthor(
        User $author,
        ArticleRepository $repository
    ): Response {
        $articleList = $repository->findBy(['author' => $author]);

        return $this->render('blog/list.html.twig', [
            'articleList' => $articleList,
            'title' => 'Liste des articles par auteur',
            'crit' => $author->getNickName()
        ]);
    }

    #[Route('/by-tag/{id}', name: 'by_tag')]
    public function byTag(
        Tag $tag,
        ArticleRepository $repository,
        PaginatorInterface $paginator,
        Request $request): Response
    {

        $pagination = $paginator->paginate(
            $repository->getArticlesByTag($tag),
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('blog/list.html.twig', [
            'articleList' => $pagination,
            'title' => 'Liste des articles par tag',
            'crit' => $tag->getTagName()
        ]);
    }

    #[Route('/by-year/{year}', name: 'by_year')]
    public function byYear(int $year, ArticleRepository $repository): Response
    {
        return $this->render('blog/list.html.twig', [
            'articleList' => $repository->getArticlesByYear($year),
            'title' => 'Liste des articles par années',
            'crit' => $year
        ]);
    }

    #[Route('/aside', name: 'aside')]
    public function aside(ArticleRepository $repository)
    {
        $countByAuthor = $repository->getArticlecountByAuthor()->getArrayResult();

        dump($repository->getArticleCountByYear());

        return $this->render('blog/aside.html.twig', [
            'countByAuthor' => $countByAuthor,
            'countByTag' => $repository->getArticleCountByTag(),
            'countByYear' => $repository->getArticleCountByYear()
        ]);
    }

    #[isGranted('ROLE_USER')]
    #[Route('/new', name: 'new_article')]
    #[Route('/update/{id}', name: 'update_article', requirements: ['id' => '\d+'])]
    public function addEdit(
        Request $request,
        EntityManagerInterface $em,
        ImageUploader $uploader,
        Article $article = null
    ) {

        if ($article === null) {
            $article = new Article();
            $article->setAuthor($this->getUser());
            $title = "Nouvel article";
            $this->denyAccessUnlessGranted(ArticleVoter::ADD, $article);
        } else {
            // Vote pour l'accès à la modification
            $this->denyAccessUnlessGranted(ArticleVoter::EDIT, $article);
            $title = "Modification de l'article";
        }

        $form = $this->createForm(
            ArticleType::class,
            $article
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // traitement de l'upload
            /** @var UploadedFile $photoUpload */
            $photoUpload = $form->get('photo')->getData();

            if($photoUpload instanceof UploadedFile){
                $uploader->process(
                    $photoUpload,
                    $article,
                    "setPhoto"
                );
            }

            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('blog_details', ['id' => $article->getId()]);
        }

        return $this->render('blog/form.html.twig', [
            'title' => $title,
            'articleForm' => $form->createView()
        ]);
    }

    #[Route('/search', name: 'search', methods: ['GET'])]
    public function search(Request $request, ArticleRepository $repository){
        // Récupération de la saisie du formulaire
        $searchInput = $request->query->get('search');

        return $this->render('blog/list.html.twig', [
            'articleList' => $repository->getSearchedArticles($searchInput),
            'title' => 'Recherche libre des articles',
            'crit' => $searchInput
        ]);
    }

    #[Route('/{slug}', name: 'details')]
    public function details(
        Article $article,
        Request $request,
        ArticleRepository $repository,
        EntityManagerInterface $em
    ): Response {

        $comment = new Comment();
        $comment->setCreatedAt(new \DateTime())
            ->setArticle($article);

        $form = $this->createForm(
            CommentType::class,
            $comment
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('blog_details', ['id' => $article->getId()]);
        }

        return $this->render('blog/details.html.twig', [
            'article' => $article,
            'commentForm' => $form->createView(),
            'rating' => ceil($repository->getArticleAverageRating($article->getId()))
        ]);
    }
}
