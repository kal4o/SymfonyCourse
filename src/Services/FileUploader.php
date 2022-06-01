<?php

namespace App\Services;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader {

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(\Symfony\Component\DependencyInjection\ContainerInterface $container) {

        $this->container = $container;
    }

    public function uploadFile(UploadedFile $file) {

        $fileName = md5(uniqid()) . '.' . $file->guessClientExtension();

        $file->move(
        //TODO get the target for files directory
            $this->container->getParameter('uploads_dir'),
            $fileName
        );

        return $fileName;
    }

}