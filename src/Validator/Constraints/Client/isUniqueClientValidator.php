<?php


namespace App\Validator\Constraints\Client;


use App\DTO\Client\UpdateClient\UpdateClientFromRequestInput;
use App\Repository\ClientRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class isUniqueClientValidator extends ConstraintValidator
{
    /**
     * @var ClientRepository
     */
    private $repository;

    public function __construct(ClientRepository $repository)
    {
        $this->repository = $repository;
    }

    public function validate($value, Constraint $constraint)
    {
        $property = $this->context->getPropertyName();

        if ($objectInDb=$this->repository->findOneBy([$property=>$value])) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $value)
                    ->addViolation();
        }
    }
}