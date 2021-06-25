<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *     security="is_granted('ROLE_USER')",
 *     collectionOperations={
 *          "get",
 *          "post"={
 *              "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')",
 *              "validation_groups"={"Default", "create"}
 *          }
 *     },
 *     itemOperations={
 *          "get",
 *          "get_me"={
 *              "method" = "GET",
 *              "route_name"="get_me",
 *              "identifiers" = {},
 *              "openapi_context" = {
 *                  "summary"     = "Get current User infos",
 *                  "description"     = "Get current User infos based on token",
 *              }
 *          },
 *          "put"={
 *              "security"="(is_granted('ROLE_USER') and object === user) or is_granted('ROLE_ADMIN')"
 *          },
 *          "delete"={
 *              "security"="is_granted('DELETE', object)",
 *              "security_message"="Only the admin can delete an user"
 *          }
 *      }
 * )
 * @ApiFilter(SearchFilter::class, properties={"email": "partial"})
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use SoftDeleteableEntity;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user:read"})
     * @ApiProperty(identifier=true)
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=180, unique=true, nullable=false)
     * @Assert\Email()
     * @Assert\NotBlank()
     * @Groups({"user:read", "user:write"})
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups({"user:read", "user:write"})
     */
    private string $firstname;

    /**
     * @ORM\Column(type="string", length=180, nullable=false)
     * @Groups({"user:read", "user:write"})
     */
    private string $lastname;
    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     * @Groups({"user:read", "user:write"})
     */
    private ?string $username;

    /**
     * @ORM\Column(type="json")
     * @Groups({"admin:read", "admin:write"})
     */
    private array $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @SerializedName("password").
     * @Groups("user:write")
     * @Assert\NotBlank(groups={"create"})
     */
    private ?string $plainPassword = null;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"admin:read", "owner:read", "user:write"})
     */
    private ?string $phoneNumber;

    /**
     * Returns true if this is the currently-authenticated user
     *
     * @Groups({"user:read"})
     */
    private bool $isMe = false;

    /**
     * @Groups({"owner:read", "owner:write", "admin:read", "admin:write"})
     * @ORM\Column(type="boolean")
     */
    private bool $allowExtraEmails = false;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="user", orphanRemoval=true)
     * @Groups({"owner:read", "owner:write", "admin:read", "admin:write"})
     */
    private PersistentCollection $products;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     * @return $this
     */
    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }
    public function getIsMe(): bool
    {
        if ($this->isMe === null) {
            throw new \LogicException('The isMe field has not been initialized');
        }
        return $this->isMe;
    }
    public function setIsMe($isMe)
    {
        $this->isMe = $isMe;
    }

    public function getAllowExtraEmails(): ?bool
    {
        return $this->allowExtraEmails;
    }

    public function setAllowExtraEmails(bool $allowExtraEmails): self
    {
        $this->allowExtraEmails = $allowExtraEmails;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setUser($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getUser() === $this) {
                $product->setUser(null);
            }
        }

        return $this;
    }
}
