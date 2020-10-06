<?php


namespace App\Validator\Constraints\Client;


use App\DTO\Client\UpdateClient\UpdateClientFromRequestInput;
use App\Repository\ClientRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class isUniqueUpdateClientValidator extends ConstraintValidator
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
        /**
         * @var UpdateClientFromRequestInput $object
         */
        $object = $this->context->getObject();

        if ($objectInDb=$this->repository->findOneBy([$property=>$value])) {
            if ($object->getId()!==$objectInDb->getId()) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $value)
                    ->addViolation();
            }
        }
    }
}