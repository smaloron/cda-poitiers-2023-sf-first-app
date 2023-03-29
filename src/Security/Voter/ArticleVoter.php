<?php

namespace App\Security\Voter;

use App\Entity\Article;
use App\Entity\User;
use App\Repository\ArticleRepository;
use ContainerJIN9OYb\getMonolog_Logger_CacheService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ArticleVoter extends Voter
{
    public const EDIT = 'EDIT';
    public const VIEW = 'VIEW';
    public const ADD = 'NEW';

    public function __construct(
        private int $maxArticles,
        private ArticleRepository $articleRepository,
        private LoggerInterface $logger
    ){}


    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW, self::ADD])
            && $subject instanceof Article;
    }

    /**
     * @throws Exception
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        return match ($attribute){
            self::VIEW => $this->isAllowedToView($subject, $user),
            self::EDIT => $this->isAllowedToEdit($subject, $user),
            self::ADD => $this->isAllowedToAdd($subject, $user),
            default => throw new Exception("Pas de vote sans attribut")
        };
    }

    private function isAllowedToEdit(Article $article, UserInterface $user):bool {
        return ($article->getAuthor() === $user);
    }

    private function isAllowedToView(Article $article, UserInterface $user):bool{
        return true;
    }

    private function isAllowedToAdd(Article $article, User $user):bool{
        try{
            $this->logger->info('Voter Add OK');
            return $this->articleRepository
                    ->getCreatedArticleCountToday($user) <= $this->maxArticles;
        }catch (Exception $ex){
            $this->logger->error($ex->getMessage());
            return false;
        }
    }
}
