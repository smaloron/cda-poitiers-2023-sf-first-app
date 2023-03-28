<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\FlashBagAwareSessionInterface;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;

class ImageUploader
{
    private string $targetFolder;

    private FlashBagInterface $flashBag;


    /**
     * @param string $targetFolder
     */
    public function __construct(string $targetFolder, RequestStack $requestStack)
    {
        $this->targetFolder = $targetFolder;
        /** @var FlashBagAwareSessionInterface $session */
        $session = $requestStack->getSession();
        $this->flashBag = $session->getFlashBag();

    }

    public function process(
        UploadedFile $uploadedFile,
        mixed $entity,
        string $method
    ) {

        try {
            $fileName = uniqid('photo_', true) . "." . $uploadedFile->guessExtension();

            // déplacement du fichier temporaire vers sa destination
            $uploadedFile->move(
                $this->targetFolder,
                $fileName
            );

            $entity->$method($fileName);

            $this->flashBag->add('success', 'Téléchargement OK');

        } catch (\Exception $error){
            $this->flashBag->add('error', 'Impossible de traiter le fichier téléchargé');
        }

    }


}