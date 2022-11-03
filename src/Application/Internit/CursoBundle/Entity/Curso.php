<?php

namespace App\Application\Internit\CursoBundle\Entity;

use App\Application\Internit\CursoBundle\Repository\CursoRepository;
use App\Application\Internit\DocumentoBundle\Entity\Documento;
use App\Entity\SonataMediaMedia;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CursoRepository::class)]
class Curso
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[ORM\Column(length: 255)]
    private string $nome;

    #[ORM\ManyToOne(targetEntity: SonataMediaMedia::class, cascade: ['persist'])]
    private mixed $imagem;

    #[ORM\OneToMany(mappedBy: "curso", targetEntity: Documento::class)]
    private Collection $documentos;

    public function __construct()
    {
        $this->documentos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }


    public function getImagem(): mixed
    {
        return $this->imagem;
    }


    public function setImagem(mixed $imagem): void
    {
        $this->imagem = $imagem;
    }

    public function getDocumentos(): mixed
    {
        return $this->documentos;
    }

    public function setDocumentos(mixed $documentos): void
    {
        $this->documentos = $documentos;
    }


}