<?php

namespace App\Form\Transformer;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;

class TagDataTransformer implements DataTransformerInterface
{

    public function __construct(private TagRepository $tagRepository){}

    public function transform(mixed $value)
    {
        $tagArray = $value->toArray();
        $tagArray = array_map(
            function ($item){
                return $item->getTagName();
            },
            $tagArray
        );
        return implode(', ', $tagArray);
    }

    public function reverseTransform(mixed $value)
    {
        // Transformation de la liste des tags sous forme de chaîne de caractères
        // en un tableau ordinal de chaine de caractères
        $tagsArray = explode(',', $value);
        $tagsArray = array_map('trim', $tagsArray);

        $tagCollection = new ArrayCollection();

        foreach ($tagsArray as $tagName){
            // on cherche un tag existant
            $tag = $this->tagRepository->findOneBy(['tagName' => $tagName]);
            if($tag === null){
                $tag = new Tag();
                $tag->setTagName($tagName);
            }
            // Ajout à la collection
            $tagCollection->add($tag);
        }

        return $tagCollection;
    }
}