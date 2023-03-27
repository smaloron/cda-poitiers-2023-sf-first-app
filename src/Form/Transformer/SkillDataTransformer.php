<?php

namespace App\Form\Transformer;

use App\Entity\Skill;
use App\Repository\SkillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;

class SkillDataTransformer implements DataTransformerInterface
{
    public function __construct(private SkillRepository $skillRepository){}


    /**
     * @inheritDoc
     * @param ArrayCollection $value
     */
    public function transform(mixed $value)
    {
        $skillArray = $value->toArray();
        $skillArray = array_map(
            function (Skill $item){
                return $item->getSkillName();
            },
            $skillArray
        );

        return implode(', ', $skillArray);

    }

    /**
     * @inheritDoc
     */
    public function reverseTransform(mixed $value)
    {
        $skillArray = explode(',', $value);
        $skillArray = array_map('trim', $skillArray);
        $skillCollection = new ArrayCollection();

        foreach ($skillCollection as $skillName){
            $skill = $this->skillRepository->findOneBySkillName($skillName);
            if($skill === null){
                $skill = new Skill();
                $skill->setSkillName($skillName);
            }
            $skillCollection->add($skill);
        }

        return $skillCollection;
    }
}