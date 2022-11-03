<?php

namespace App\Application\Internit\DocumentoBundle\Entity;

use App\Application\Internit\CursoBundle\Entity\Curso;
use App\Application\Internit\DocumentoBundle\Repository\DocumentoRepository;
use App\Entity\SonataMediaGallery;
use App\Entity\SonataMediaMedia;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Validator\ValidatorInterface;

## START Validation
#[UniqueEntity('titulo')]
#[UniqueEntity('subtitulo')]
## END Validation
#[ORM\Entity(repositoryClass: DocumentoRepository::class)]
class Documento
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[ORM\Column(length: 255)]
    private string $titulo;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[ORM\Column(length: 255)]
    private string $subtitulo;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $descricao = null;

    #[ORM\ManyToOne(targetEntity: SonataMediaMedia::class, cascade: ['persist'])]
    private mixed $imagem;

    #[ORM\ManyToOne(targetEntity: SonataMediaGallery::class, cascade: ['persist'])]
    private mixed $galeria;

    #[ORM\ManyToOne(targetEntity: Curso::class, inversedBy: "documentos")]
    #[ORM\JoinColumn(name: 'curso_id', referencedColumnName: "id")]
    private Curso|null $curso = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): void
    {
        $this->titulo = $titulo;
    }

    public function getSubtitulo(): string
    {
        return $this->subtitulo;
    }

    public function setSubtitulo(string $subtitulo): void
    {
        $this->subtitulo = $subtitulo;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }

    public function getImagem(): mixed
    {
        return $this->imagem;
    }

    public function setImagem(mixed $imagem): void
    {
        $this->imagem = $imagem;
    }

    public function getGaleria(): mixed
    {
        return $this->galeria;
    }

    public function setGaleria(mixed $galeria): void
    {
        $this->galeria = $galeria;
    }

    public function getCurso(): ?Curso
    {
        return $this->curso;
    }

    public function setCurso(?Curso $curso): void
    {
        $this->curso = $curso;
    }

}
