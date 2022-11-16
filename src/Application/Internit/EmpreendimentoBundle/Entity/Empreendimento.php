<?php

namespace App\Application\Internit\EmpreendimentoBundle\Entity;

use App\Application\Internit\EmpreendimentoBundle\Repository\EmpreendimentoRepository;
use App\Application\Internit\StatusEmpreendimentoBundle\Entity\StatusEmpreendimento;

use App\Entity\SonataMediaGallery;
use App\Entity\SonataMediaMedia;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/** Info: Classe responsavel pelos empreendimentos */
#[ORM\Table(name: 'empreendimento')]
#[ORM\Entity(repositoryClass: EmpreendimentoRepository::class)]
#[UniqueEntity('id')]
#[UniqueEntity('nome')]
class Empreendimento
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer', unique: true, nullable: false)]
    private ?int $id = null;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[ORM\Column(name: 'nome', type: 'string', unique: true, nullable: false)]
    private string $nome;

    #[ORM\Column(name: 'descricao', type: 'string', unique: false, nullable: true)]
    private ?string $descricao = null;

    #[ORM\Column(name: 'visivel', type: 'boolean', unique: false, nullable: true)]
    private ?bool $visivel = null;

    #[ORM\ManyToOne(targetEntity: StatusEmpreendimento::class)]
    #[ORM\JoinColumn(name: 'status_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    private StatusEmpreendimento|null $status = null;

    #[ORM\ManyToOne(targetEntity: SonataMediaMedia::class, cascade: ['persist'])]
    private mixed $imagem;

    #[ORM\ManyToOne(targetEntity: SonataMediaGallery::class, cascade: ['persist'])]
    private mixed $galeria;

    public function __construct()
    {
        $this->blocos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao): void
    {
        $this->descricao = $descricao;
    }

    public function getVisivel(): ?bool
    {
        return $this->visivel;
    }

    public function setVisivel(?bool $visivel): void
    {
        $this->visivel = $visivel;
    }

    public function getStatus(): ?StatusEmpreendimento
    {
        return $this->status;
    }

    public function setStatus(?StatusEmpreendimento $status): void
    {
        $this->status = $status;
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



}