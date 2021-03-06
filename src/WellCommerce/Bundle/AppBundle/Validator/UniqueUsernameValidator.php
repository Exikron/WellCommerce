<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\AppBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use WellCommerce\Bundle\AppBundle\Entity\ClientDetails;
use WellCommerce\Bundle\AppBundle\Repository\ClientRepositoryInterface;
use WellCommerce\Bundle\RoutingBundle\Helper\RouterHelperInterface;

/**
 * Class UniqueEntityValidator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class UniqueUsernameValidator extends ConstraintValidator
{
    /**
     * @var ClientRepositoryInterface
     */
    protected $clientRepository;
    
    /**
     * @var RouterHelperInterface
     */
    protected $routerHelper;
    
    /**
     * UniqueUsernameValidator constructor.
     *
     * @param ClientRepositoryInterface $clientRepository
     * @param RouterHelperInterface     $routerHelper
     */
    public function __construct(ClientRepositoryInterface $clientRepository, RouterHelperInterface $routerHelper)
    {
        $this->clientRepository = $clientRepository;
        $this->routerHelper     = $routerHelper;
    }
    
    /**
     * Validate the route entity
     *
     * @param mixed      $entity
     * @param Constraint $constraint
     */
    public function validate($entity, Constraint $constraint)
    {
        if (!$entity instanceof ClientDetails) {
            throw new \InvalidArgumentException('Expected instance of ClientDetails');
        }
        
        $username = $entity->getUsername();
        $result   = $this->clientRepository->findOneBy(['clientDetails.username' => $username]);
        
        if (null === $result) {
            return;
        }
        
        if ($this->context instanceof ExecutionContextInterface) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ url }}', $this->routerHelper->generateUrl('front.client.login'))
                ->atPath('username')
                ->setInvalidValue($username)
                ->addViolation();
        }
    }
}
